<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/Database.php';

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->EmployeeID) && !empty($data->FirstName) && !empty($data->LastName)) {
    try {
        $query = "UPDATE employees SET FirstName = :first_name, LastName = :last_name, Title = :title, HomePhone = :phone WHERE EmployeeID = :id";
        $stmt = $db->prepare($query);

        $stmt->bindParam(":first_name", $data->FirstName);
        $stmt->bindParam(":last_name", $data->LastName);
        $stmt->bindParam(":title", $data->Title);
        $stmt->bindParam(":phone", $data->HomePhone);
        $stmt->bindParam(":id", $data->EmployeeID);

        if($stmt->execute()) {
            http_response_code(200);
            echo json_encode(array("status" => true, "message" => "อัปเดตข้อมูลพนักงานเรียบร้อยแล้ว"));
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