<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/Database.php';

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->TerritoryID)) {
    try {
        $query = "DELETE FROM territories WHERE TerritoryID = :id";
        $stmt = $db->prepare($query);

        $stmt->bindParam(":id", $data->TerritoryID);

        if($stmt->execute()) {
            http_response_code(200);
            echo json_encode(array("status" => true, "message" => "ลบข้อมูลเขตการขายเรียบร้อยแล้ว"));
        } else {
            http_response_code(503);
            echo json_encode(array("status" => false, "message" => "ไม่สามารถลบข้อมูลได้"));
        }
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(array("status" => false, "message" => $e->getMessage()));
    }
} else {
    http_response_code(400);
    echo json_encode(array("status" => false, "message" => "ระบุ TerritoryID ที่ต้องการลบ"));
}
?>