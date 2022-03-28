<?php
header('Content-Type: application/json');
require_once '../connect.php';
if($_SERVER['REQUEST_METHOD']  ===  "POST" ) {
    if (isset($_POST['request']) ) {
        $_POST = json_decode($_POST['request'], true);
        try{
            foreach($_POST as $value) {
                $stmt = $conn->prepare( "INSERT INTO products ( name, price )
                                        VALUES ( :name, :price ) ");
                $stmt->execute([
                    "name" => (string)$value['name'],
                    "price" => (int)$value['price']
                ]);
            }
            http_response_code(200);
            echo json_encode(array('status' => true, 'message' => 'Created Success!' ));
        }catch (Throwable $e){
            http_response_code(500);
            echo "การประมวลผลข้อมูลล้มเหลว:" .$e->getMessage();
        }
    }else{
        http_response_code(400);
        echo json_encode(array('status' => false, 'message' => 'Create failed!' ));
    }
} else {
    http_response_code(405);
    echo json_encode(array('status' => false, 'message' => 'Method Not Allowed!' ));
}

?>