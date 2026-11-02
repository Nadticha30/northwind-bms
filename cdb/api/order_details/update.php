<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/Database.php';

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->OrderID) && !empty($data->ProductID)) {
    try {
        $query = "UPDATE order_details SET UnitPrice = :unit_price, Quantity = :quantity, Discount = :discount WHERE OrderID = :order_id AND ProductID = :product_id";
        $stmt = $db->prepare($query);

        $stmt->bindParam(":unit_price", $data->UnitPrice);
        $stmt->bindParam(":quantity", $data->Quantity);
        $stmt->bindParam(":discount", $data->Discount);
        $stmt->bindParam(":order_id", $data->OrderID);
        $stmt->bindParam(":product_id", $data->ProductID);

        if($stmt->execute()) {
            http_response_code(200);
            echo json_encode(array("status" => true, "message" => "อัปเดตรายการเรียบร้อยแล้ว"));
        } else {
            http_response_code(503);
            echo json_encode(array("status" => false, "message" => "ไม่สามารถอัปเดตข้อมูลได้"));
        }
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(array("status" => false, "message" => $e->getMessage()));
    }
} else {
    http_response_code(400);
    echo json_encode(array("status" => false, "message" => "ข้อมูลไม่ครบถ้วน"));
}
?>