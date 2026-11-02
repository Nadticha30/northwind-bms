<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

require_once __DIR__ . '/../../config/Database.php';

$database = new Database();
$db = $database->getConnection();

try {
    $query = "SELECT * FROM products ORDER BY ProductID ASC";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $num = $stmt->rowCount();

    $products_arr = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($products_arr, $row);
    }

    http_response_code(200);
    echo json_encode([
        "status" => true,
        "total" => $num,
        "data" => $products_arr
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => false,
        "error" => $e->getMessage()
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
?>