<?php
header('Content-Type: application/json');
require_once '../connect.php';
if($_SERVER['REQUEST_METHOD']  ===  "GET" ) {
        $stmt = $conn->prepare("SELECT * FROM products");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(count($result)) {
            http_response_code(200);
            echo json_encode(array('status' => true, 'response' => $result, 'message' => 'Success!' ));
        } else {
            http_response_code(200);
            echo json_encode(array('status' => false, 'message' => 'Not found!' ));
        }
} else {
    http_response_code(405);
    echo json_encode(array('status' => false, 'message' => 'Method Not Allowed!' ));
}

?>