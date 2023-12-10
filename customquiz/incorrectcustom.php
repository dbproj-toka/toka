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

            $idString = $_GET['id'];
            $incorrectArray = explode(',', $idString);

            foreach ($incorrectArray as $custom_id) {
                $sql = 
                "UPDATE customwords SET iscorrect = 0 
                WHERE custom_id = '$custom_id'"
                AND isCorrect = 1;
                
                if ($conn->query($sql) !== TRUE) {
                    echo "Error updating record: " . $conn->error;
                }
            }
?>