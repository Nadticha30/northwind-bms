<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/Database.php';

$database = new Database();
$db = $database->getConnection();

try {
    $query = "SELECT EmployeeID, FirstName, LastName, Title, HomePhone FROM employees ORDER BY EmployeeID ASC";
    $stmt = $db->prepare($query);
    $stmt->execute();

    $employees = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($employees, $row);
    }

    http_response_code(200);
    echo json_encode(array("status" => true, "total" => count($employees), "data" => $employees));
} catch(PDOException $e) {
    try {
        $query = "SELECT EmployeeID, FirstName, LastName, Title, HomePhone FROM tb_employees ORDER BY EmployeeID ASC";
        $stmt = $db->prepare($query);
        $stmt->execute();

        $employees = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($employees, $row);
        }

        http_response_code(200);
        echo json_encode(array("status" => true, "total" => count($employees), "data" => $employees));
    } catch(PDOException $ex) {
        http_response_code(500);
        echo json_encode(array("status" => false, "message" => $ex->getMessage()));
    }
}
?>