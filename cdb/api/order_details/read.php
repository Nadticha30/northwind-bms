<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/Database.php';

$database = new Database();
$db = $database->getConnection();

try {
    $query = "SELECT OrderID, ProductID, UnitPrice, Quantity, Discount FROM order_details ORDER BY OrderID DESC";
    $stmt = $db->prepare($query);
    $stmt->execute();

    $details = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($details, $row);
    }

    http_response_code(200);
    echo json_encode(array("status" => true, "total" => count($details), "data" => $details));
} catch(PDOException $e) {
    try {
        $query = "SELECT OrderID, ProductID, UnitPrice, Quantity, Discount FROM tb_orderdetails ORDER BY OrderID DESC";
        $stmt = $db->prepare($query);
        $stmt->execute();

        $details = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($details, $row);
        }

        http_response_code(200);
        echo json_encode(array("status" => true, "total" => count($details), "data" => $details));
    } catch(PDOException $ex) {
        http_response_code(500);
        echo json_encode(array("status" => false, "message" => $ex->getMessage()));
    }
}
?>