<!-- 로그인을 통해 접속했는지 확인을 위한 include -->
<!-- $username이랑 $identifier로 변수 받을 수 있음 -->
<?php 
  include '../check/inc_head.php';
?>

<!-- 뒤로가기 캐시제거 -->
<?php
	header("Progma:no-cache");
	header("Cache-Control: no-store, no-cache ,must-revalidate");
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
    <link rel="stylesheet" href="navigator.css">
    <title>Navigator</title>
</head>
<body>
    
    <!-- 정상적으로 로그인하여 접속했을 때 -->
    <?php
      if ( $jb_login ) {
    ?>

<!-- 네비게이션바 -->
<div class="wrap">
        <nav id="nav">
            <div class="titleWrapper">
                    <h1 class="t">T</h1>
                    <h1 class="o">O</h1>
                    <h1 class="k">K</h1>
                    <h1 class="a">A</h1>
            </div>
            <label class="navicon" for="nav-toggle"><span class="navicon-bar"></span></label>
            <ul class="nav-items">
                <li><a href="#">QUEST</a></li>
                <li><a href="#">MYPAGE</a></li>
            </ul>
        </nav>
</div>


    <!-- 그냥 접속했을 때 -->
    <?php
      } else {
    ?>
      <h1>Invalid Access</h1>
    <?php
      }
    ?>
    
</body>
</html>