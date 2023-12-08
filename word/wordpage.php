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

        // 카테고리 데이터를 가져오는 쿼리 실행
        $categoryIdentifier = $conn->real_escape_string($_GET['category']);

        $categorySql = "SELECT category_name FROM category WHERE identifier = '$categoryIdentifier'";
        $categoryResult = $conn->query($categorySql);
        if ($categoryResult->num_rows > 0) {
        $categoryRow = $categoryResult->fetch_assoc();
        $categoryName = $categoryRow['category_name'];
        } else {
            $categoryName = "Unknown Category"; // 카테고리가 없을 경우
        }

        $sql = "SELECT w.word_id, w.english, w.korean, w.part, w.categoryId, c.category_name 
                FROM words AS w
                INNER JOIN category AS c ON w.categoryId = c.identifier
                WHERE c.identifier = '$categoryIdentifier'";
        
        $result = $conn->query($sql);
        
    ?>


    <div class="subtitle"><?php echo $categoryName; ?></div>

    <div class="scrollable">
        <div class="back-button-container">
            <button onclick="history.back()" class="btn btn-default">Go to Category</button>
        </div>
        <!-- 카테고리 리스트 출력 -->
        <ul class="category-list">
            <?php
                    if ($result->num_rows > 0) {
                        // 결과 데이터를 출력
                        while($row = $result->fetch_assoc()) {
                            echo '<li class="list-group-item">';
                            echo '<div class="word">';
                            echo '<span class="category-number">' . $row["word_id"] . '</span>'; // Assuming 'word_id' is like a category number
                            echo '<span class="english-word">' . $row["english"] . '</span>';
                            echo '<div class="word-translations">';
                            echo '<span class="part-of-speech">' . $row["part"] . '</span>';
                            echo '<span class="korean-word">' . $row["korean"] . '</span>';
                            echo '</div>';
                            echo '</div>';
                            echo '</li>';
                        }
                    } else {
                        echo "<li>0 results</li>";
                    }
                ?>
        </ul>
    </div>
    <?php
        // 데이터베이스 연결 종료
        $conn->close();
    ?>
</body>

</html>