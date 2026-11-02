<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . '/../../config/Database.php';

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->ProductID)) {
    try {
        $query = "DELETE FROM products WHERE ProductID = :ProductID";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':ProductID', $data->ProductID);

        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(["status" => true, "message" => "Product deleted successfully"]);
        } else {
            http_response_code(503);
            echo json_encode(["status" => false, "message" => "Unable to delete product"]);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["status" => false, "error" => $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(["status" => false, "message" => "Incomplete data. ProductID is required."]);
}
?>