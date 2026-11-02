<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/Database.php';

$database = new Database();
$db = $database->getConnection();

try {
    $query = "SELECT CustomerID, CompanyName, ContactName, ContactTitle, Address, City, Country, Phone FROM customers ORDER BY CustomerID ASC";
    $stmt = $db->prepare($query);
    $stmt->execute();

    $customers = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($customers, $row);
    }

    http_response_code(200);
    echo json_encode(array("status" => true, "total" => count($customers), "data" => $customers));
} catch(PDOException $e) {
    try {
        $query = "SELECT CustomerID, CompanyName, ContactName, ContactTitle, Address, City, Country, Phone FROM tb_customers ORDER BY CustomerID ASC";
        $stmt = $db->prepare($query);
        $stmt->execute();

        $customers = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($customers, $row);
        }

        http_response_code(200);
        echo json_encode(array("status" => true, "total" => count($customers), "data" => $customers));
    } catch(PDOException $ex) {
        http_response_code(500);
        echo json_encode(array("status" => false, "message" => $ex->getMessage()));
    }
}
?>