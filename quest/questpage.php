<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<link rel="stylesheet" href="questpage.css">
<link rel="stylesheet" href="../navigator.css">
<title>Quest page</title>

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

    <div id="quest-container">
        <?php 
         $sql = "SELECT q.idquest, q.title, q.content, a.isCompleted
         FROM quest q
         LEFT JOIN achieves a ON q.idquest = a.quest_id AND a.user_id = '$identifier'
         ORDER BY a.isCompleted DESC, q.title ASC";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
        $isCompleted = $row["isCompleted"];
        $iconClass = $isCompleted ? 'quest-icon-completed' : 'quest-icon';
        $cardClass = $isCompleted ? 'quest-item-completed' : 'quest-item-incomplete';
        $titleClass = $isCompleted ? 'quest-title-completed' : 'quest-title';
        $iconHTML = $isCompleted ? '<i class="fa-solid fa-trophy"></i>' : '<i class="fa-solid fa-lock"></i>';

        echo "<div class='$cardClass'>";
            echo "<span class='$iconClass'>$iconHTML</span>";
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