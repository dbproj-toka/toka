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
    <link rel="stylesheet" href="category.css">
    <link rel="stylesheet" href="../navigator.css">
    <title>categoryPage</title>
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
    <div class="wrapper">
        <div class="container">

            <!-- title -->
            <div class="titleWrap">
                <h3 class="title">Category List</h3>
                <div class="btnWrapper">
                    <button onclick="location.href='../word/allwordspage.php'" id="btn" class="btn btn-default">Search All Words</button>
                </div>
            </div>

            <div class="scrollable">
                <div class="wordWrapper">
                    <ul class="list">
                        <!-- 카테고리 리스트 출력 -->
                        <?php
                        
                            // 카테고리 데이터를 가져오는 쿼리 실행                   
                            $sql = "SELECT identifier, category_name FROM category";
                            $result = $conn->query($sql);
                            
                            // 결과가 있으면 리스트 아이템으로 출력
                            if ($result->num_rows > 0) {

                                while($row = $result->fetch_assoc()) {
                                    echo '<li class="list-group-item" onclick="goWord(' . $row["identifier"] . ')">';
                                        echo '<div class="word">';
                                            echo '<div class="left-side">';
                                                echo '<span class="number">' . sprintf("%02d", $row["identifier"]) . '</span>';
                                                echo '<span class="english-word">' . $row["category_name"] . '</span>';
                                            echo '</div>';
                                    echo '</li>';

                                }
                            } else {
                                echo "0 results";
                            }
                            // 데이터베이스 연결 종료
                            $conn->close();
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>

        //애니메이션 효과
        showListItems();

        function showListItems() {
            var listItems = document.querySelectorAll('.list-group-item');

            listItems.forEach(function (item, index) {
                setTimeout(function () {
                    item.classList.add('show');
                }, index * 100);
            });
        }

        //단어 생성
        function createCustom() {
            window.location.href = 'createword.php';
        }

        //카테고리단어목록으로
        function goWord(id) {
            //window.location.href = 'editword.php';
            window.location.href = '../word/wordpage.php?category=' + id;
        }

    </script>
</body>

</html>