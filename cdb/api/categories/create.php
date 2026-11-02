<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/Database.php';

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->CategoryName)) {
    try {
        $query = "INSERT INTO categories (CategoryName, Description) VALUES (:name, :description)";
        $stmt = $db->prepare($query);

        $stmt->bindParam(":name", $data->CategoryName);
        $stmt->bindParam(":description", $data->Description);

        if($stmt->execute()) {
            http_response_code(201);
            echo json_encode(array("status" => true, "message" => "เพิ่มหมวดหมู่เรียบร้อยแล้ว"));
        } else {
            http_response_code(503);
            echo json_encode(array("status" => false, "message" => "ไม่สามารถเพิ่มหมวดหมู่ได้"));
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