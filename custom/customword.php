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
    <link rel="stylesheet" href="customword.css">
    <link rel="stylesheet" href="../navigator.css">
    <title>Custom Word</title>
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

        $sql = "SELECT * FROM customwords WHERE user_id = '$identifier'";
        $result = $conn->query($sql);

        //Custom단어 총 개수
        $wordCount = $result->num_rows;
        
    ?>


    <!-- 내용 -->
    <div class = "wrapper">
            <div class="container">

                <!-- Title -->
                <h3 class="title">Custom Word List</h3>
                <button id="btn" onclick="createCustom()">+Create</button>
                

                <!-- Word List -->
                <div class="wordWrapper">
                        <?php
                            if ($wordCount > 0) {
                                $count = 1;

                                // 단어가 있을 경우
                                while($row = $result->fetch_assoc()) {
                                    echo '<li class="list-group-item">';
                                    echo '<div class="word">';
                                    echo '<span class="category-number">' . $count . '</span>';
                                    echo '<span class="english-word">' . $row["c_english"] . '</span>';
                                    echo '<div class="word-translations">';
                                    echo '<span class="part-of-speech">' . $row["c_part"] . '</span>';
                                    echo '<span class="korean-word">' . $row["c_korean"] . '</span>';
                                    echo '</div>';
                                    echo '</div>';
                                    echo '</li>';
                                }

                                $count++;
                            } else {
                                // 단어가 없을 경우
                            }
                        ?>
                </div>
                
            </div>
    </div>


    <?php
        // 데이터베이스 연결 종료
        $conn->close();
    ?>

    <script>
        function createCustom() {
            window.location.href = 'createword.php';
        }
    </script>
</body>

</html>