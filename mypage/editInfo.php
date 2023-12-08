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
    <script src="https://kit.fontawesome.com/140fb8c1aa.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="editInfo.css">
    <title>Edit Information</title>
</head>
<body>

    <!-- Navigator & Session -->
    <?php include '../navigator.php'; ?>

    <!-- 정상적으로 로그인하여 접속했을 때 -->
    <?php
      if ( $jb_login ) {
    ?>       

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

            // 사용자 데이터를 가져오는 쿼리 실행
            $sql = "SELECT u.name, u.password, u.email
                    FROM users AS u
                    WHERE u.identifier = '$identifier'";
            
            $result = $conn->query($sql);         
            $user = $result->fetch_array(MYSQLI_ASSOC);   

            $username = $user["name"];
            $email = $user["email"];
            $password = $user["password"];
    ?>

        <!-- 내용 -->
        <div class = "wrapper">
            <div class="container">

                <!-- Title -->
                <h3 class="title">Edit Information</h3>

                <!-- 수정 폼 -->
                <form action="infoProgress.php" method="post">

                    <!-- ID & Password & Email & Name -->             
                    <div id="labelWrap"><label for="pw" id="label">Password</label></div>     
                    <input type="password" id="pw" name="password" class="box" placeholder="Password" value="<?php echo $password ?>" required><br>

                    <div id="labelWrap"><label for="email" id="label">Email</label></div>
                    <input type="email" id="email" name="email" class="box" placeholder="Email" autocomplete='off' value="<?php echo $email ?>"><br>

                    <div id="labelWrap"><label for="name" id="label">Name</label></div>
                    <input type="text" id="name" name="name" class="box" placeholder="Name" autocomplete='off' value="<?php echo $username ?>" required><br>

                    <!-- Edit -->
                    <div class="btnWrapper">
                        <button type="submit" id="btn">Edit</button>
                    </div>

                </form>

            </div>
        </div>

    <!-- 그냥 접속했을 때 -->
    <?php
      } else {
    ?>
      <h1>Invalid Access</h1>
    <?php
      }
    ?>


<script>


</script>

</body>
</html>