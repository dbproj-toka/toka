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
    <link rel="stylesheet" href="questpage.css">
    <link rel="stylesheet" href="../navigator.css">
    <title>Quest</title>
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

    <div class="subWrapper">
        <p class="subTitle item">Quest</p>
    </div>

    <div id="quest-container">
        <?php
    $sql = "SELECT * FROM quest";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            // 퀘스트 완료 여부에 따라 스타일 클래스를 결정합니다.
            $iconClass = $row["completed"] ? 'quest-icon-completed' : 'quest-icon';
            $titleClass = $row["completed"] ? 'quest-title-completed' : 'quest-title';

            echo "<div class='quest-item'>";
            // 완료 여부에 따라 다른 아이콘을 표시합니다.
            $icon = $row["completed"] ? '&#x1F3C6;' : '&#x1F512;';
            echo "<span class='$iconClass'>$icon</span>";
            echo "<h2 class='$titleClass'>".$row["title"]."</h2>";
            echo "<p class='quest-description'>".$row["content"]."</p>";
            echo "</div>";
        }
    } else {
        echo "No quests found";
    }
    $conn->close();
    ?>
    </div>

</body>

</html>