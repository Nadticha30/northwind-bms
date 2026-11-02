<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/Database.php';

$database = new Database();
$db = $database->getConnection();

try {
    $query = "SELECT OrderID, CustomerID, EmployeeID, OrderDate, Freight FROM orders ORDER BY OrderID DESC";
    $stmt = $db->prepare($query);
    $stmt->execute();

    $orders = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($orders, $row);
    }

    http_response_code(200);
    echo json_encode(array("status" => true, "total" => count($orders), "data" => $orders));
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(array("status" => false, "message" => $e->getMessage()));
}
?>