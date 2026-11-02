<?php
$host     = 'sqlXXX.infinityfree.com'; 
$dbname   = 'if0_43337554_northwind';  
$user     = 'if0_43337554';            
$password = 'รหัสผ่านของคุณ';         

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'success' => false,
        'message' => 'เชื่อมต่อฐานข้อมูลล้มเหลว: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    exit;
}
?>
