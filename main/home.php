<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

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
    <script src="https://kit.fontawesome.com/140fb8c1aa.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="home.css">
    <title>Home</title>
</head>

<body>

    <?php include '../navigator.php'; ?>

    <!-- 내용 -->
    <div class="wrapper">
        <div class="container">
            <!-- Menu -->
            <div class="subWrapper">
                <p class="subTitle item">Menu</p>
            </div>

            <!-- WordList & Quiz & Review & Ranking -->
            <div class="menuWrapper">
                <div class="wordList item" onclick="window.location.href='../category/category.php';">
                    <div class="icon">
                        <i class="fa-regular fa-file-lines fa-3x"></i>
                    </div>
                    <p>WordList</p>
                </div>

                <div class="quiz item" onclick="">
                    <div class="icon">
                        <i class="fa-solid fa-pencil fa-3x"></i>
                    </div>
                    <p>Quiz</p>
                </div>

                <div class="review item" onclick="">
                    <div class="icon">
                        <i class="fa-solid fa-table fa-3x"></i>
                    </div>
                    <p>Review</p>
                </div>

                <div class="ranking item" onclick="">
                    <div class="icon">
                        <i class="fa-solid fa-signal fa-3x"></i>
                    </div>
                    <p>Ranking</p>
                </div>
            </div>
        </div>
    </div>

    <!-- 애니메이션 효과 -->
    <script>
    var item = document.querySelectorAll('.item');
    var cnt = 0;

    function activeFunc() {
        item[cnt].classList.add('active');
        cnt++;
        if (cnt >= item.length) {
            clearInterval(addActive);
        }
    }

    var addActive = setInterval(activeFunc, 130);
    </script>

</body>

</html>