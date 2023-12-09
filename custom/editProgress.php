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
		$eng = $_POST["eng"];
        $kor = $_POST["kor"];
        $part = $_POST["part"];
        $custom_id = $_POST["custom_id"];
        $original_eng = $_POST["original_eng"];

        //토익에 존재하는 단어인지 검사
        $checksql = "SELECT * FROM words WHERE english = '$eng'";
        $checkresult = $conn->query($checksql);

        if($checkresult->num_rows < 1) {

             //커스텀에 존재하는 단어인지 검사
            $checksql2 = "SELECT * FROM customwords WHERE c_english = '$eng'";
            $checkresult2 = $conn->query($checksql2);

            if($checkresult2->num_rows < 1) {               

                $sql = "UPDATE customwords SET c_english='$eng', c_korean='$kor', c_part='$part' WHERE user_id='$identifier' AND custom_id='$custom_id' ";
        
                if (mysqli_query($conn, $sql)) {
                    echo "<script>alert('Success!'); window.location.href='customword.php';</script>";               
                } else {    
                    echo "<script>alert('Error!');</script>";    
                }	

            } else {

                //원래단어 한국어나 part만 수정할때
                if($eng==$original_eng) {
                    $sql = "UPDATE customwords SET c_english='$eng', c_korean='$kor', c_part='$part' WHERE user_id='$identifier' AND custom_id='$custom_id' ";
        
                    if (mysqli_query($conn, $sql)) {
                        echo "<script>alert('Success!'); window.location.href='customword.php';</script>";               
                    } else {    
                        echo "<script>alert('Error!');</script>";    
                    }	
                } else {
                    //커스텀에 이미 존재하는 단어
                    echo "<script>alert('Existed Word'); window.location.href = 'editword.php?id=$custom_id';</script>";
                }
            }

        } else {
            //토익에 이미 존재하는 단어
            echo "<script>alert('Existed Word'); window.location.href = 'editword.php?id=$custom_id';</script>";
        }

	}
?>