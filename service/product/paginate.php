<?php
header('Content-Type: application/json');
require_once '../connect.php';
if($_SERVER['REQUEST_METHOD']  ===  "GET" ) {
        $limit = 3;
            if(isset($_GET["pageId"])){
                $pageId = $_GET["pageId"];
            }else{
                $pageId = 1;
            }

        $start = ($pageId - 1) * $limit;
        $stmt = $conn->prepare("SELECT * FROM products LIMIT $start, $limit");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(count($result)) {
            $products = $conn->query("SELECT * FROM products");
            $rows = $products->rowCount();
            $totalPage = ceil($rows / $limit);
            http_response_code(200);
            echo json_encode([
                'status' => true,
                'response' => $result,
                'pagination' => ['totalPage' => $totalPage],
                'message' => 'Success!'
            ]);
        } else {
            http_response_code(404);
            echo json_encode(['status' => false, 'message' => 'Not found!']);
        }
} else {
    http_response_code(405);
    echo json_encode(array('status' => false, 'message' => 'Method Not Allowed!' ));
}

?>