<?php
// 데이터베이스 연결 설정
$host = "localhost";
$user = "root";
$pass = "qkrwnsdyd0416";
$db = "toka";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("연결실패: " . $conn->connect_error);
}

// POST 요청에서 id 가져오기
$incorrect_id = $_GET['id'];

// 쉼표로 구분된 문자열을 배열로 분할
$incorrectArray = explode(',', $incorrect_id);

foreach ($incorrectArray as $word_id) {
    // INSERT 쿼리 실행
    $insertQuery = "INSERT INTO wrongword_record (word_id) VALUES ('$word_id')";
    
    // 쿼리 실행 결과 확인 및 오류 처리
    if ($conn->query($insertQuery) !== TRUE) {
        echo "레코드 업데이트 중 오류 발생: " . $conn->error;
    }
}


if ($conn->query($insertQuery) === TRUE) {
    echo "틀린 답이 성공적으로 저장되었습니다.";
} else {
    echo "에러: " . $insertQuery . "<br>" . $conn->error;
}

$conn->close();
?>