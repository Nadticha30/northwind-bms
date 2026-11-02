<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/Database.php';

$database = new Database();
$db = $database->getConnection();

try {
    $query = "SELECT RegionID, RegionDescription FROM region ORDER BY RegionID ASC";
    $stmt = $db->prepare($query);
    $stmt->execute();

    $regions = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($regions, $row);
    }

    http_response_code(200);
    echo json_encode(array("status" => true, "total" => count($regions), "data" => $regions));
} catch(PDOException $e) {
    try {
        $query = "SELECT RegionID, RegionDescription FROM tb_region ORDER BY RegionID ASC";
        $stmt = $db->prepare($query);
        $stmt->execute();

        $regions = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($regions, $row);
        }

        http_response_code(200);
        echo json_encode(array("status" => true, "total" => count($regions), "data" => $regions));
    } catch(PDOException $ex) {
        http_response_code(500);
        echo json_encode(array("status" => false, "message" => $ex->getMessage()));
    }
}
?>