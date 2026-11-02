<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . '/../../config/Database.php';

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if (
    !empty($data->ProductName) &&
    isset($data->UnitPrice) &&
    isset($data->UnitsInStock)
) {
    try {
        $query = "INSERT INTO products (ProductName, SupplierID, CategoryID, QuantityPerUnit, UnitPrice, UnitsInStock, UnitsOnOrder, ReorderLevel, Discontinued) 
                  VALUES (:ProductName, :SupplierID, :CategoryID, :QuantityPerUnit, :UnitPrice, :UnitsInStock, :UnitsOnOrder, :ReorderLevel, :Discontinued)";

        $stmt = $db->prepare($query);

        $stmt->bindValue(':ProductName', $data->ProductName);
        $stmt->bindValue(':SupplierID', $data->SupplierID ?? null);
        $stmt->bindValue(':CategoryID', $data->CategoryID ?? null);
        $stmt->bindValue(':QuantityPerUnit', $data->QuantityPerUnit ?? null);
        $stmt->bindValue(':UnitPrice', $data->UnitPrice);
        $stmt->bindValue(':UnitsInStock', $data->UnitsInStock);
        $stmt->bindValue(':UnitsOnOrder', $data->UnitsOnOrder ?? 0);
        $stmt->bindValue(':ReorderLevel', $data->ReorderLevel ?? 0);
        $stmt->bindValue(':Discontinued', $data->Discontinued ?? 0);

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode([
                "status" => true,
                "message" => "Product created successfully",
                "id" => $db->lastInsertId()
            ]);
        } else {
            http_response_code(503);
            echo json_encode([
                "status" => false, 
                "message" => "Unable to create product"
            ]);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            "status" => false, 
            "error" => $e->getMessage()
        ]);
    }
} else {
    http_response_code(400);
    echo json_encode([
        "status" => false, 
        "message" => "Incomplete data"
    ]);
}
?>