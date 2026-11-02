<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/Database.php';

$database = new Database();
$db = $database->getConnection();

try {
  
    $totalOrders = $db->query("SELECT COUNT(*) FROM orders")->fetchColumn();
    $totalCustomers = $db->query("SELECT COUNT(*) FROM customers")->fetchColumn();
    $totalProducts = $db->query("SELECT COUNT(*) FROM products")->fetchColumn();
    $totalRevenue = $db->query("SELECT SUM(UnitPrice * Quantity * (1 - Discount)) FROM order_details")->fetchColumn() ?: 0;

    $queryCat = "
        SELECT c.CategoryName, COUNT(DISTINCT od.OrderID) as OrderCount, SUM(od.UnitPrice * od.Quantity * (1 - od.Discount)) AS TotalSales
        FROM order_details od
        JOIN products p ON od.ProductID = p.ProductID
        JOIN categories c ON p.CategoryID = c.CategoryID
        GROUP BY c.CategoryID, c.CategoryName
        ORDER BY TotalSales DESC
    ";
    $stmtCat = $db->prepare($queryCat);
    $stmtCat->execute();
    $categorySales = $stmtCat->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200);
    echo json_encode(array(
        "status" => true,
        "summary" => array(
            "totalOrders" => (int)$totalOrders,
            "totalCustomers" => (int)$totalCustomers,
            "totalProducts" => (int)$totalProducts,
            "totalRevenue" => (float)$totalRevenue
        ),
        "categorySales" => $categorySales
    ));
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(array("status" => false, "message" => $e->getMessage()));
}
?>