<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/Database.php';

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->FirstName) && !empty($data->LastName)) {
    try {
        $query = "INSERT INTO employees (FirstName, LastName, Title, HomePhone) VALUES (:first_name, :last_name, :title, :phone)";
        $stmt = $db->prepare($query);

        $stmt->bindParam(":first_name", $data->FirstName);
        $stmt->bindParam(":last_name", $data->LastName);
        $stmt->bindParam(":title", $data->Title);
        $stmt->bindParam(":phone", $data->HomePhone);

        if($stmt->execute()) {
            http_response_code(201);
            echo json_encode(array("status" => true, "message" => "เพิ่มข้อมูลพนักงานเรียบร้อยแล้ว"));
        } else {
            http_response_code(503);
            echo json_encode(array("status" => false, "message" => "ไม่สามารถเพิ่มข้อมูลพนักงานได้"));
        }
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(array("status" => false, "message" => $e->getMessage()));
    }
} else {
    http_response_code(400);
    echo json_encode(array("status" => false, "message" => "กรอกชื่อและนามสกุลให้ครบถ้วน"));
}
?>