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
    <link rel="stylesheet" href="createword.css">
    <title>Create Word</title>
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
                <h3 class="title">Create New Word</h3>

                <!-- 수정 폼 -->
                <form action="createword.php" method="post">

                    <!-- English & Korean & Part -->             
                    <div id="labelWrap"><label for="eng" id="label">English</label></div>     
                    <input type="text" id="eng" name="eng" class="box" placeholder="English Meaning" required><br>

                    <div id="labelWrap"><label for="kor" id="label">Korean</label></div>
                    <input type="text" id="kor" name="kor" class="box" placeholder="Korean Meaning" autocomplete='off' required><br>

                    <div id="labelWrap"><label for="part" id="label">Part</label></div>
                    <select id="part" name="part" class="box" required>
                        <option value="n">Noun</option>
                        <option value="pro">Pronoun</option>
                        <option value="v">Verb</option>
                        <option value="adj">Adjective</option>
                        <option value="ad">Adverb</option>
                        <option value="pre">Preposition</option>
                        <option value="conj">Conjunction</option>
                        <option value="int">Interjection</option>
                    </select>

                    <!-- Edit -->
                    <div class="btnWrapper">
                        <button type="submit" id="btn">Create</button>
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
                $eng = $_POST["eng"];
                $kor = $_POST["kor"];
                $part = $_POST["part"];

                //토익에 존재하는 단어인지 검사
                $checksql = "SELECT * FROM words WHERE english = '$eng'";
                $checkresult = $conn->query($checksql);
    
                if($checkresult->num_rows < 1) {

                     //커스텀에 존재하는 단어인지 검사
                    $checksql2 = "SELECT * FROM customwords WHERE c_english='$eng' AND user_id='$identifier'";
                    $checkresult2 = $conn->query($checksql2);

                    if($checkresult2->num_rows < 1) {

                        $sql = "INSERT into customwords (user_id, c_english, c_korean, c_part) values('$identifier', '$eng', '$kor', '$part')";
                
                        if (mysqli_query($conn, $sql)) {
                            echo "<script>alert('Success!'); window.location.href='customword.php';</script>";               
                        } else {    
                            echo "<script>alert('Error!');</script>";    
                        }	

                    } else {
                        //커스텀에 이미 존재하는 단어
                        echo "<script>alert('Existed Word');</script>";
                    }
	
                } else {
                    //토익에 이미 존재하는 단어
                    echo "<script>alert('Existed Word');</script>";
                }
        
            }
?>