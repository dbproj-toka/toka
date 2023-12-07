<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="wordpage.css">
    <link rel="stylesheet" href="../navigator.css">
    <title>Word List</title>
</head>

<body>
    <?php include '../navigator.php'; ?>
    <?php
        # 본인 sql 서버에 맞게 수정하기 #
	$host = "localhost";
	$user = "root";
	$pass = "root";
	$db = "toka";

	$conn = new mysqli($host, $user, $pass, $db);

	if ($conn->connect_error) {
		die("연결실패: " . $conn->connect_error);
	}
    
?>

    <?php
            
        // 카테고리 데이터를 가져오는 쿼리 실행
        $categoryName = 'Business';
        $sql = "SELECT w.word_id, w.english, w.korean, w.part, w.categoryId, c.category_name 
                FROM words AS w
                INNER JOIN category AS c ON w.categoryId = c.identifier
                WHERE c.category_name = '$categoryName'";  // 특정 카테고리에 대한 조건 추가
        
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            
            // 결과 데이터를 출력
            while($row = $result->fetch_assoc()) {
                echo "word_id: " . $row["word_id"]. 
                    " - English: " . $row["english"]. 
                    " - Korean: " . $row["korean"]. 
                    " - Part: " . $row["part"]. 
                    " - CategoryId: " . $row["categoryId"].
                    " - CategoryName: " . $row["category_name"]. "<br>";
            }
        }else {
            echo "0 results";
        }
        // 데이터베이스 연결 종료
        $conn->close();
    ?>

    <div class="scrollable">
        <!-- 카테고리 제목 출력 -->
        <h2><?php echo $categoryName; ?> (<?php echo $result->num_rows; ?>)</h2>
        <!-- 카테고리 리스트 출력 -->
        <ul>
            <?php
            if ($result->num_rows > 0) {
                // 결과 데이터를 출력
                while($row = $result->fetch_assoc()) {
                    echo '<li>';
                    echo '<div class="word"><span class="english">' . $row["english"] . '</span>';
                    echo '<span class="part">' . $row["part"] . '</span>';
                    echo '<span class="korean">' . $row["korean"] . '</span></div>';
                    echo '</li>';
                }
            } else {
                echo "<li>0 results</li>";
            }
            ?>
        </ul>
    </div>

</body>

</html>