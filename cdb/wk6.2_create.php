<?php
// =======================================================
// ส่วนที่ 1: การประมวลผลฝั่ง Server (PHP) ทำงานเมื่อมีการ POST
// =======================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // กำหนดให้ตอบกลับเป็น JSON
    header('Content-Type: application/json; charset=utf-8');
    
    // ดึงไฟล์เชื่อมต่อฐานข้อมูล
    require_once "ConnDB.php";

    $productName = trim($_POST['ProductName'] ?? '');
    $supplierID  = trim($_POST['SupplierID'] ?? '');
    $catID       = trim($_POST['CatID'] ?? '');
    $unit        = trim($_POST['Unit'] ?? '');
    $price       = trim($_POST['Price'] ?? '');

    // ตรวจสอบข้อมูลเบื้องต้น
    if (empty($productName) || empty($supplierID) || empty($catID) || empty($unit) || empty($price)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "กรุณากรอกข้อมูลให้ครบถ้วน"]);
        exit;
    } 
    
    try {
        // แก้ไข: ชื่อ Table และชื่อ Column ให้ตรงกับฐานข้อมูลเป๊ะๆ ตามรูป
        $sql = "INSERT INTO tb_products (c_ProductName, i_SupplierID, i_CategoryID, c_Unit, i_Price) 
                VALUES (:ProductName, :SupplierID, :CatID, :Unit, :Price)";
        
        $stmt = $conn->prepare($sql);
        
        // ผูกค่าพารามิเตอร์เข้ากับตัวแปร
        $stmt->bindParam(':ProductName', $productName, PDO::PARAM_STR);
        $stmt->bindParam(':SupplierID', $supplierID, PDO::PARAM_INT);
        $stmt->bindParam(':CatID', $catID, PDO::PARAM_INT);
        $stmt->bindParam(':Unit', $unit, PDO::PARAM_STR);
        $stmt->bindParam(':Price', $price, PDO::PARAM_STR); 
        
        if ($stmt->execute()) {
            http_response_code(200); 
            echo json_encode(["status" => "success", "message" => "บันทึกข้อมูลสินค้าเรียบร้อยแล้ว!"]);
            exit;
        }
    } catch (PDOException $e) {
        http_response_code(500); 
        echo json_encode(["status" => "error", "message" => "เกิดข้อผิดพลาด: " . $e->getMessage()]);
        exit;
    }
}
// =======================================================
// สิ้นสุดส่วน PHP (ถ้าไม่ใช่ POST จะทำงานส่วน HTML ด้านล่าง)
// =======================================================
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มข้อมูลสินค้า (Add Product)</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .form-card { max-width: 600px; margin: 40px auto; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

<div class="container">
    <div class="card form-card bg-white p-4 p-md-5">
        <h3 class="text-center mb-4 text-primary fw-bold">
            <i class="bi bi-box-seam me-2"></i>เพิ่มข้อมูลสินค้า
        </h3>

        <!-- จุดสำหรับแสดง Error หากติดต่อ Server ไม่ได้ -->
        <div id="errorAlert" class="alert alert-danger d-none" role="alert"></div>

        <!-- ฟอร์ม -->
        <form id="productForm" method="POST" class="needs-validation" novalidate>
            <!-- 1. ชื่อสินค้า -->
            <div class="mb-3">
                <label for="ProductName" class="form-label fw-semibold">ชื่อสินค้า (ProductName)</label>
                <input type="text" class="form-control" id="ProductName" name="ProductName" required>
                <div class="invalid-feedback">กรุณากรอกชื่อสินค้า</div>
            </div>

            <!-- 2. ผู้จัดจำหน่าย -->
            <div class="mb-3">
                <label for="SupplierID" class="form-label fw-semibold">ผู้จัดจำหน่าย (SupplierID)</label>
                <select class="form-select" id="SupplierID" name="SupplierID" required>
                    <option value="" selected disabled>-- เลือกผู้จัดจำหน่าย --</option>
                    <option value="2">Supplier B (ID: 2)</option>
                    <option value="12">Supplier 12 (ID: 12)</option>
                    <option value="23">Supplier 23 (ID: 23)</option>
                    <option value="99">Supplier 99 (ID: 99)</option>
                </select>
                <div class="invalid-feedback">กรุณาเลือกผู้จัดจำหน่าย</div>
            </div>

            <!-- 3. หมวดหมู่สินค้า -->
            <div class="mb-3">
                <label for="CatID" class="form-label fw-semibold">หมวดหมู่สินค้า (CategoryID)</label>
                <select class="form-select" id="CatID" name="CatID" required>
                    <option value="" selected disabled>-- เลือกหมวดหมู่ --</option>
                    <option value="1">หมวดหมู่ 1 (ID: 1)</option>
                    <option value="2">หมวดหมู่ 2 (ID: 2)</option>
                    <option value="9">หมวดหมู่ 9 (ID: 9)</option>
                    <option value="98">หมวดหมู่ 98 (ID: 98)</option>
                </select>
                <div class="invalid-feedback">กรุณาเลือกหมวดหมู่สินค้า</div>
            </div>

            <!-- 4. หน่วยนับ -->
            <div class="mb-3">
                <label for="Unit" class="form-label fw-semibold">หน่วยนับ (Unit)</label>
                <input type="text" class="form-control" id="Unit" name="Unit" placeholder="เช่น 500 ml, 12 boxes, box" required>
                <div class="invalid-feedback">กรุณากรอกหน่วยนับ</div>
            </div>

            <!-- 5. ราคา -->
            <div class="mb-3">
                <label for="Price" class="form-label fw-semibold">ราคาสินค้า (Price)</label>
                <div class="input-group">
                    <input type="number" step="1" min="1" class="form-control" id="Price" name="Price" required>
                    <span class="input-group-text">บาท</span>
                    <div class="invalid-feedback">กรุณากรอกราคาให้ถูกต้อง (ต้องมากกว่า 0)</div>
                </div>
            </div>

            <!-- ปุ่มบันทึก -->
            <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                    <i class="bi bi-save me-2"></i>บันทึกข้อมูล
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal หน้าต่างเด้งแสดงความสำเร็จ -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="successModalLabel"><i class="bi bi-check-circle-fill me-2"></i>สำเร็จ</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center py-4">
        <h5 class="text-success mb-3">บันทึกข้อมูลสินค้าสำเร็จ!</h5>
        <p class="mb-0 text-muted">ระบบได้รับข้อมูลเรียบร้อยแล้ว</p>
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-success px-4" data-bs-dismiss="modal">ตกลง</button>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('productForm');
    const submitBtn = document.getElementById('submitBtn');
    const errorAlert = document.getElementById('errorAlert');
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));

    form.addEventListener('submit', function (event) {
        event.preventDefault(); 
        errorAlert.classList.add('d-none'); 

        if (!form.checkValidity()) {
            event.stopPropagation();
            form.classList.add('was-validated');
            return; 
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>กำลังบันทึก...';
        
        const formData = new FormData(form);

        fetch(window.location.href, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (response.status === 200) {
                return response.json();
            } else {
                return response.text().then(text => { throw new Error(text) });
            }
        })
        .then(data => {
            successModal.show();
            form.reset(); 
            form.classList.remove('was-validated');
        })
        .catch(error => {
            console.error('Error:', error);
            errorAlert.classList.remove('d-none');
            errorAlert.innerHTML = `<strong>เกิดข้อผิดพลาด!</strong> ไม่สามารถบันทึกข้อมูลได้ เช็คฐานข้อมูลหรือไฟล์ ConnDB.php อีกครั้ง`;
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bi bi-save me-2"></i>บันทึกข้อมูล';
        });
    });
});
</script>
</body>
</html>