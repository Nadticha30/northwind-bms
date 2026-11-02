<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/Database.php';

$database = new Database();
$db = $database->getConnection();

try {
    $query = "SELECT TerritoryID, TerritoryDescription, RegionID FROM territories ORDER BY TerritoryID ASC";
    $stmt = $db->prepare($query);
    $stmt->execute();

    $territories = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($territories, $row);
    }

    http_response_code(200);
    echo json_encode(array("status" => true, "total" => count($territories), "data" => $territories));
} catch(PDOException $e) {
    try {
        $query = "SELECT TerritoryID, TerritoryDescription, RegionID FROM tb_territories ORDER BY TerritoryID ASC";
        $stmt = $db->prepare($query);
        $stmt->execute();

        $territories = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($territories, $row);
        }

        http_response_code(200);
        echo json_encode(array("status" => true, "total" => count($territories), "data" => $territories));
    } catch(PDOException $ex) {
        http_response_code(500);
        echo json_encode(array("status" => false, "message" => $ex->getMessage()));
    }
}
?>