<?php 
error_reporting(E_ALL);
$host = "localhost";
$dbname = "ajax_products";
$username = "root";
$password = "1234";
try{
    $conn = new PDO('mysql:host='.$host.'; dbname='.$dbname.'; charset=utf8', $username, $password );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $exception) {
    echo "ไม่สามารถเชื่อมต่อฐานข้อมูลได้:" . $exception->getMessage();
    exit();
}
