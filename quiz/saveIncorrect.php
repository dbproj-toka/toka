<?php
// 데이터베이스 연결 설정
$host = "localhost";
$user = "root";
$pass = "root";
$db = "toka";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("연결실패: " . $conn->connect_error);
}

// POST 요청에서 id 가져오기
$incorrect_id = $_GET['id'];
$user_id = $_GET['identifier'];
$category_id = $_GET['category_id'];
$score =  $_GET['score'];

// 쉼표로 구분된 문자열을 배열로 분할
$incorrectArray = explode(',', $incorrect_id);
$insertQuery2 = "INSERT INTO records (user_id, category_id, score, timestamp) VALUES ('$user_id', '$category_id', '$score', CURRENT_TIMESTAMP)";

$selectQuery = "SELECT recordId FROM records WHERE category_id = $category_id AND user_id = $user_id";
$result = $conn->query($selectQuery);

if ($result) {
    if ($result->num_rows > 0) {
        // Fetch the recordId
        $row = $result->fetch_assoc();
        $lastRecordId = $row['recordId'];
        echo "Information saved successfully. Last inserted recordid: " . $lastRecordId;
        // Execute DELETE query
        $deleteQuery = "DELETE FROM records WHERE recordId = $lastRecordId";

        if ($conn->query($deleteQuery) === TRUE) {
            echo "Record with recordId $lastRecordId deleted successfully.";
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    } else {
        echo "No records found for the given category_id and user_id.";
    }
} else {
    echo "Error: " . $selectQuery . "<br>" . $conn->error;
}


if ($conn->query($insertQuery2) === TRUE) { 
    $lastRecordId = $conn->insert_id;  // Retrieve the last inserted recordid
    echo "Information saved successfully. Last inserted recordid: " . $lastRecordId;
} else {

    echo "Error: " . $insertQuery2 . "<br>" . $conn->error;
}

    foreach ($incorrectArray as $word_id) {
        // INSERT 쿼리 실행
        $insertQuery1 = "INSERT INTO incorrectwords (recordId,wordId) VALUES ('$lastRecordId','$word_id')";
        // 쿼리 실행 결과 확인 및 오류 처리
        if ($conn->query($insertQuery1) === TRUE) {
            header("Location: ../main/home.php"); 
            //echo "틀린 답이 성공적으로 저장되었습니다.";
        } else {
            echo "에러: " . $insertQuery1 . "<br>" . $conn->error;
        }
    }
   
$conn->close();
?>