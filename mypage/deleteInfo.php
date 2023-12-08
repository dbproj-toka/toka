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
    <link rel="stylesheet" href="deleteInfo.css">
    <title>Delete Account</title>
</head>
<body>

    <!-- Navigator & Session -->
    <?php include '../navigator.php'; ?>

    <!-- 정상적으로 로그인하여 접속했을 때 -->
    <?php
      if ( $jb_login ) {
    ?>       

        <!-- 내용 -->
        <div class = "wrapper">
            <div class="container">

                <!-- Title -->
                <h3 class="title">Type the password for delete your account.</h3>

                <!-- 수정 폼 -->
                <form action="deleteInfo.php" method="post">

                    <!-- Password -->             
                    <input type="password" id="pw" name="password" class="box" placeholder="Password" required><br>

                    <!-- Delete -->
                    <div class="btnWrapper">
                        <button type="submit" id="btn">Delete Account</button>
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

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $password = $_POST["password"];
        
                $sql = "SELECT * FROM users WHERE identifier = '$identifier'";
                $result = $conn->query($sql);
        
                if($result->num_rows < 1) {
                    echo "<script>alert('No User Information');</script>";
                } else {
                    $user = $result->fetch_array(MYSQLI_ASSOC);
        
                    if($user['password'] == $password){

                        $deleteSql = "DELETE FROM users WHERE identifier = '$identifier'";
                        $deleteResult = $conn->query($deleteSql);

                        if ($deleteResult) {
                            session_start();                        
                            session_destroy();
                            header("Location: ../login/login.php"); 
                        } else {
                            echo "<script>alert('Error deleting user data');</script>";
                        }

                    } else {
                        echo "<script>alert('Wrong Password');</script>";
                    }
                }
        
            }
?>