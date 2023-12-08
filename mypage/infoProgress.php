<!-- 로그인을 통해 접속했는지 확인을 위한 include -->
<!-- $username이랑 $identifier로 변수 받을 수 있음 -->
<?php 
  include '../check/inc_head.php';
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

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$password = $_POST["password"];
        $email = $_POST["email"];
        $name = $_POST["name"];

        $sql = "UPDATE users SET password='$password', email='$email', name='$name' WHERE identifier = '$identifier'";
		
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Success!'); window.location.href='mypage.php';</script>";
    
        } else {    
            echo "<script>alert('Error!');</script>";    
        }	

	}
?>