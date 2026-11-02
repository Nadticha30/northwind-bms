<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/Database.php';

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->OrderID) && !empty($data->ProductID)) {
    try {
        $query = "INSERT INTO order_details (OrderID, ProductID, UnitPrice, Quantity, Discount) VALUES (:order_id, :product_id, :unit_price, :quantity, :discount)";
        $stmt = $db->prepare($query);

        $stmt->bindParam(":order_id", $data->OrderID);
        $stmt->bindParam(":product_id", $data->ProductID);
        $stmt->bindParam(":unit_price", $data->UnitPrice);
        $stmt->bindParam(":quantity", $data->Quantity);
        $stmt->bindParam(":discount", $data->Discount);

        if($stmt->execute()) {
            http_response_code(201);
            echo json_encode(array("status" => true, "message" => "เพิ่มรายละเอียดรายการสั่งซื้อเรียบร้อยแล้ว"));
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
    echo json_encode(array("status" => false, "message" => "กรอก OrderID และ ProductID ให้ครบถ้วน"));
}
?>