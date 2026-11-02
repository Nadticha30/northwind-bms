<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/Database.php';

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->CustomerID)) {
    try {
        $query = "INSERT INTO orders (CustomerID, EmployeeID, OrderDate, Freight) VALUES (:customer_id, :employee_id, :order_date, :freight)";
        $stmt = $db->prepare($query);

        $stmt->bindParam(":customer_id", $data->CustomerID);
        $stmt->bindParam(":employee_id", $data->EmployeeID);
        $stmt->bindParam(":order_date", $data->OrderDate);
        $stmt->bindParam(":freight", $data->Freight);

        if($stmt->execute()) {
            http_response_code(201);
            echo json_encode(array("status" => true, "message" => "เพิ่มข้อมูลคำสั่งซื้อเรียบร้อยแล้ว"));
        } else {
            http_response_code(503);
            echo json_encode(array("status" => false, "message" => "ไม่สามารถเพิ่มข้อมูลได้"));
        }
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(array("status" => false, "message" => $e->getMessage()));
    }
} else {
    http_response_code(400);
    echo json_encode(array("status" => false, "message" => "กรอกรหัสลูกค้าให้ครบถ้วน"));
}
?>