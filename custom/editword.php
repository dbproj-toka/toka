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
    <link rel="stylesheet" href="editword.css">
    <title>Edit Word</title>
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
            $pass = "qkrwnsdyd0416";
            $db = "toka";

            $conn = new mysqli($host, $user, $pass, $db);

            if ($conn->connect_error) {
                die("연결실패: " . $conn->connect_error);
            }

            $custom_id = $_GET['id'];

            // 단어 데이터를 가져오는 쿼리 실행
            $sql = "SELECT c_english, c_korean, c_part
                    FROM customwords
                    WHERE custom_id = '$custom_id' AND user_id = '$identifier'";
            
            $result = $conn->query($sql);         
            $word = $result->fetch_array(MYSQLI_ASSOC);   

            $eng = $word["c_english"];
            $kor = $word["c_korean"];
            $part = $word["c_part"];
    ?>

        <!-- 내용 -->
        <div class = "wrapper">
            <div class="container">

                <!-- Title -->
                <h3 class="title">Edit Word</h3>

                <!-- 수정 폼 -->
                <form action="editProgress.php" method="post">

                    <!-- 단어id, 원래 english 보내기 위해 -->
                    <input type="hidden" name="custom_id" value="<?php echo $custom_id; ?>">
                    <input type="hidden" name="original_eng" value="<?php echo $eng; ?>">

                    <!-- English & Korean & Part -->             
                    <div id="labelWrap"><label for="eng" id="label">English</label></div>     
                    <input type="text" id="eng" name="eng" class="box" placeholder="English Meaning" value="<?php echo $eng ?>" required><br>

                    <div id="labelWrap"><label for="kor" id="label">Korean</label></div>
                    <input type="text" id="kor" name="kor" class="box" placeholder="Korean Meaning" autocomplete='off' value="<?php echo $kor ?>" required><br>

                    <div id="labelWrap"><label for="part" id="label">Part</label></div>
                    <select id="part" name="part" class="box" required>
                        <option value="n" <?php if ($part == 'n') echo 'selected'; ?>>Noun</option>
                        <option value="pro" <?php if ($part == 'pro') echo 'selected'; ?>>Pronoun</option>
                        <option value="v" <?php if ($part == 'v') echo 'selected'; ?>>Verb</option>
                        <option value="adj" <?php if ($part == 'adj') echo 'selected'; ?>>Adjective</option>
                        <option value="ad" <?php if ($part == 'ad') echo 'selected'; ?>>Adverb</option>
                        <option value="pre" <?php if ($part == 'pre') echo 'selected'; ?>>Preposition</option>
                        <option value="conj" <?php if ($part == 'conj') echo 'selected'; ?>>Conjunction</option>
                        <option value="int" <?php if ($part == 'int') echo 'selected'; ?>>Interjection</option>
                    </select>

                    <!-- Edit -->
                    <div class="btnWrapper">
                        <button type="submit" class="btn edit">Edit</button>
                    </div>
                </form>

                <!-- Edit -->
                <div class="btnWrapper">
                    <button class="btn delete" onclick="deleteWord(<?php echo $custom_id; ?>)">Delete</button>
                </div>

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

      function deleteWord(id) {
        // 확인 창 표시
        var confirmLogout = confirm("Want to delete this word?");

        // 확인이 클릭되면 삭제 실행
        if (confirmLogout) {
            //삭제 세션
            window.location.href = 'deleteProgress.php?id=' + id;
        } else {
            // 취소가 클릭되면 아무 동작 없음
        }
      }

</script>

</body>
</html>