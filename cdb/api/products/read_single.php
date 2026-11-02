<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

require_once __DIR__ . '/../../config/Database.php';

$database = new Database();
$db = $database->getConnection();

$id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id) {
    http_response_code(400);
    echo json_encode(["status" => false, "message" => "ID is required"]);
    exit;
}

try {
    $query = "SELECT * FROM products WHERE ProductID = ? LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        http_response_code(200);
        echo json_encode([
            "status" => true,
            "data" => $row
        ]);
    } else {
        http_response_code(404);
        echo json_encode([
            "status" => false,
            "message" => "Product not found"
        ]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => false,
        "error" => $e->getMessage()
    ]);
}
?>