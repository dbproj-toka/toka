<?php
    // 데이터베이스 연결 설정
    $host = "localhost";
    $user = "root";
    $pass = "root";
    $db = "toka"; 

    $conn = new mysqli($host, $user, $pass, $db);

    if ($conn->connect_error) {
        echo "<script>alert('연결 실패: " . $conn->connect_error . "');</script>";
        exit(); // 연결 실패 시 스크립트 종료
    }

    // 입력값이 존재하는지 확인
    $userId = isset($_POST['user_id']) ? $_POST['user_id'] : null;
    $questId = isset($_POST['quest_id']) ? $_POST['quest_id'] : null;

     // 입력값 검증
     if (is_null($userId) || is_null($questId)) {
        echo "필요한 데이터가 누락되었습니다.";
        exit();
    }

    // quest, user, archive 테이블 조인 쿼리
    $sql = "SELECT u.identifier, u.name, q.idquest, q.title, a.isCompleted 
            FROM users u
            JOIN achieves a ON u.identifier = a.user_id
            JOIN quest q ON q.idquest = a.quest_id
            WHERE u.identifier = '$userId' AND q.idquest = '$questId'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // 결과 데이터 출력 (팝업 대신)
        while ($row = $result->fetch_assoc()) {
            echo "<script>alert('User ID: " . $row["identifier"] . " - Name: " . $row["name"] . " - Quest ID: " . $row["idquest"] . " - Title: " . $row["title"] . " - Completed: " . $row["isCompleted"] . "');</script>";
        }
    } else {

        // 쿼리 실행 전 입력값 검증
        if (!is_numeric($userId) || !is_numeric($questId)) {
        echo "유효하지 않은 입력값입니다.";
        exit();
}
        // 결과가 0인 경우 archives 테이블에 기록
        $insertSql = "INSERT INTO achieves (user_id, quest_id, isCompleted) VALUES ('$userId', '$questId', 1)";
        if ($conn->query($insertSql) === TRUE) {
            echo "<script>alert('새 기록이 archives 테이블에 추가되었습니다.');</script>";
        } else {
            echo "<script>alert('오류: " . $insertSql . " - " . $conn->error . "');</script>";
        }
    }

    // 데이터베이스 연결 종료
    $conn->close();
?>