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
    <link rel="stylesheet" href="quizcategory.css">
    <link rel="stylesheet" href="../navigator.css">
    <title>quizcategoryPage</title>
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

    $user_id = $_GET['identifier']
?>
    <div class="Wrapper">
        <!-- Menu -->
        <div class="subWrapper">
            <p class="subTitle item">quizCategory List</p>
        </div>

        <div class="scrollable">
            <!-- 카테고리 리스트 출력 -->
            <ul>
                <?php
            
        // 카테고리 데이터를 가져오는 쿼리 실행
    
        $sql = "SELECT identifier, category_name FROM category";
        $result = $conn->query($sql);
        
        // 결과가 있으면 리스트 아이템으로 출력
        if ($result->num_rows > 0) {
            echo '<ul class="category-list">'; // 클래스 이름을 'category-list'로 지정
            while($row = $result->fetch_assoc()) {
                // 카테고리 번호와 이름을 분리하여 출력
                echo '<a href="../quiz/quizpage.php?category='.$row["identifier"] . '&user_id=' . $user_id . '" class="category-link">';
                echo '<li>';
                echo '<span class="category-number">' . sprintf("%02d", $row["identifier"]) . '</span>';
                echo '<span class="category-name">' . $row["category_name"] . '</span>';
                //각 카테고리 버튼 추가
                echo '</li>';
                echo '</a>';

            }
            echo '</ul>';
        } else {
            echo "0 results";
        }
        // 데이터베이스 연결 종료
        $conn->close();
    ?>
            </ul>
        </div>
    </div>

</body>

</html>