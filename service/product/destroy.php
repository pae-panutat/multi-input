<?php
header('Content-Type: application/json');
require_once '../connect.php';
if($_SERVER['REQUEST_METHOD']  ===  "DELETE" ) {
        try{
            $conn->exec("TRUNCATE TABLE `products`");
            http_response_code(200);
            echo json_encode(array('status' => true, 'message' => 'Destroy Success!' ));
        }catch (Throwable $e){
            http_response_code(500);
            echo "การประมวลผลข้อมูลล้มเหลว:" .$e->getMessage();
        }
} else {
    http_response_code(405);
    echo json_encode(array('status' => false, 'message' => 'Method Not Allowed!' ));
}

?>