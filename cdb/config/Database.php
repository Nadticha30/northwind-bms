<?php
class Database {
    private $host = "localhost";
    private $db_name = "northwind";
    private $username = "root";
    private $password = "root";
    private $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";charset=utf8",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->conn->exec("CREATE DATABASE IF NOT EXISTS `" . $this->db_name . "`");
            $this->conn->exec("USE `" . $this->db_name . "`");

            $createTable = "CREATE TABLE IF NOT EXISTS `products` (
                `ProductID` INT AUTO_INCREMENT PRIMARY KEY,
                `ProductName` VARCHAR(40) NOT NULL,
                `SupplierID` INT NULL,
                `CategoryID` INT NULL,
                `QuantityPerUnit` VARCHAR(20) NULL,
                `UnitPrice` DECIMAL(10,2) DEFAULT 0.00,
                `UnitsInStock` SMALLINT DEFAULT 0,
                `UnitsOnOrder` SMALLINT DEFAULT 0,
                `ReorderLevel` SMALLINT DEFAULT 0,
                `Discontinued` TINYINT(1) DEFAULT 0
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

            $this->conn->exec($createTable);

            $checkData = $this->conn->query("SELECT COUNT(*) FROM `products`");
            if ($checkData->fetchColumn() == 0) {
                $sqlFile = __DIR__ . '/../dbNorthwind.sql';
                if (file_exists($sqlFile)) {
                    $sql = file_get_contents($sqlFile);
                    try {
                        $this->conn->exec($sql);
                    } catch (PDOException $ex) {
                        $this->conn->exec("INSERT INTO `products` (`ProductName`, `UnitPrice`, `UnitsInStock`) VALUES 
                            ('Chai', 18.00, 39),
                            ('Chang', 19.00, 17),
                            ('Aniseed Syrup', 10.00, 13);");
                    }
                } else {
                    $this->conn->exec("INSERT INTO `products` (`ProductName`, `UnitPrice`, `UnitsInStock`) VALUES 
                        ('Chai', 18.00, 39),
                        ('Chang', 19.00, 17),
                        ('Aniseed Syrup', 10.00, 13);");
                }
            }

        } catch(PDOException $e) {
            echo "Connection error: " . $e->getMessage();
        }

        return $this->conn;
    }
}
?>