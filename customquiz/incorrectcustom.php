<?php 
include '../check/inc_head.php';

$custom_id = $_POST['custom_id'];
if (isset($custom_id)) {
    // 데이터베이스 연결 설정
    $host = "localhost";
    $user = "root";
    $pass = "root";
    $db = "toka";
    $conn = new mysqli($host, $user, $pass, $db);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // isCorrect를 0으로 업데이트
    $sql = "UPDATE customwords SET isCorrect = 0 WHERE custom_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $custom_id);
    
    if ($stmt->execute()) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
}

?>