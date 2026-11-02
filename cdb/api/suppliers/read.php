<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/Database.php';

$database = new Database();
$db = $database->getConnection();

try {
    $query = "SELECT SupplierID, CompanyName, ContactName, ContactTitle, Phone FROM suppliers ORDER BY SupplierID ASC";
    $stmt = $db->prepare($query);
    $stmt->execute();

    $suppliers = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($suppliers, $row);
    }

    http_response_code(200);
    echo json_encode(array("status" => true, "total" => count($suppliers), "data" => $suppliers));
} catch(PDOException $e) {
    try {
        $query = "SELECT SupplierID, CompanyName, ContactName, ContactTitle, Phone FROM tb_suppliers ORDER BY SupplierID ASC";
        $stmt = $db->prepare($query);
        $stmt->execute();

        $suppliers = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($suppliers, $row);
        }

        http_response_code(200);
        echo json_encode(array("status" => true, "total" => count($suppliers), "data" => $suppliers));
    } catch(PDOException $ex) {
        http_response_code(500);
        echo json_encode(array("status" => false, "message" => $ex->getMessage()));
    }
}
?>