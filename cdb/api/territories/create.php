<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/Database.php';

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->TerritoryID) && !empty($data->TerritoryDescription)) {
    try {
        $query = "INSERT INTO territories (TerritoryID, TerritoryDescription, RegionID) VALUES (:id, :description, :region_id)";
        $stmt = $db->prepare($query);

        $stmt->bindParam(":id", $data->TerritoryID);
        $stmt->bindParam(":description", $data->TerritoryDescription);
        $stmt->bindParam(":region_id", $data->RegionID);

        if($stmt->execute()) {
            http_response_code(201);
            echo json_encode(array("status" => true, "message" => "เพิ่มข้อมูลเขตการขายเรียบร้อยแล้ว"));
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
    echo json_encode(array("status" => false, "message" => "กรอก TerritoryID และชื่อเขตการขายให้ครบถ้วน"));
}
?>