<?php 
  include '../check/inc_head.php';
?>

<?php
            # 본인 sql 서버에 맞게 수정하기 #
            $host = "localhost";
            $user = "root";
            $pass = "qkrwnsdyd0416";
            $db = "toka";

            $conn = new mysqli($host, $user, $pass, $db);

            if ($conn->connect_error) {
                die("연결실패: " . $conn->connect_error);
            }

            $custom_id = $_GET['id'];

            $deleteSql = "DELETE FROM customwords WHERE user_id='$identifier' AND custom_id=$custom_id";

            $deleteResult = $conn->query($deleteSql);

            if ($deleteResult) {
                header("Location: customword.php"); 
            } else {
                 echo "<script>alert('Error deleting word data');</script>";
            }

?>