<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/Database.php';

$database = new Database();
$db = $database->getConnection();

try {
    $query = "SELECT ShipperID, CompanyName, Phone FROM shippers ORDER BY ShipperID ASC";
    $stmt = $db->prepare($query);
    $stmt->execute();

    $shippers = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($shippers, $row);
    }

    http_response_code(200);
    echo json_encode(array("status" => true, "total" => count($shippers), "data" => $shippers));
} catch(PDOException $e) {
    try {
        $query = "SELECT ShipperID, CompanyName, Phone FROM tb_shippers ORDER BY ShipperID ASC";
        $stmt = $db->prepare($query);
        $stmt->execute();

        $shippers = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($shippers, $row);
        }

        http_response_code(200);
        echo json_encode(array("status" => true, "total" => count($shippers), "data" => $shippers));
    } catch(PDOException $ex) {
        http_response_code(500);
        echo json_encode(array("status" => false, "message" => $ex->getMessage()));
    }
}
?>