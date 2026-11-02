<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/Database.php';

$database = new Database();
$db = $database->getConnection();

try {
    $totalOrders = $db->query("SELECT COUNT(*) FROM orders")->fetchColumn() ?: 0;
    $totalCustomers = $db->query("SELECT COUNT(*) FROM customers")->fetchColumn() ?: 0;
    $totalProducts = $db->query("SELECT COUNT(*) FROM products")->fetchColumn() ?: 0;
    $totalEmployees = $db->query("SELECT COUNT(*) FROM employees")->fetchColumn() ?: 0;
    $totalSuppliers = $db->query("SELECT COUNT(*) FROM suppliers")->fetchColumn() ?: 0;
    $totalShippers = $db->query("SELECT COUNT(*) FROM shippers")->fetchColumn() ?: 0;
    $totalRevenue = $db->query("SELECT SUM(UnitPrice * Quantity * (1 - Discount)) FROM order_details")->fetchColumn() ?: 0;

    $recentOrders = $db->query("SELECT OrderID, CustomerID, OrderDate, Freight FROM orders ORDER BY OrderID DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200);
    echo json_encode(array(
        "status" => true,
        "summary" => array(
            "totalOrders" => (int)$totalOrders,
            "totalCustomers" => (int)$totalCustomers,
            "totalProducts" => (int)$totalProducts,
            "totalEmployees" => (int)$totalEmployees,
            "totalSuppliers" => (int)$totalSuppliers,
            "totalShippers" => (int)$totalShippers,
            "totalRevenue" => (float)$totalRevenue
        ),
        "recentOrders" => $recentOrders
    ));
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(array("status" => false, "message" => $e->getMessage()));
}
?>