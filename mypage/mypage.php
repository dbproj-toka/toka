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
    <link rel="stylesheet" href="mypage.css">
    <title>My Page</title>
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
            $sql = "SELECT u.name, u.email
                    FROM users AS u
                    WHERE u.identifier = '$identifier'";
            
            $result = $conn->query($sql);         
            $user = $result->fetch_array(MYSQLI_ASSOC);   

            $username = $user["name"];
            $email = $user["email"];

            if($email=="" || $email==NULL) {
                $email = "Update your email";
            }
    ?>
        <!-- 내용 -->   
        <div class = "wrapper">
            <div class="container">

                    <!-- Name, Email -->
                    <div class="subWrapper">
                        <p class="name item user"><?php echo $username ?></p>
                        <p class="email item user"><?php echo $email ?></p>
                    </div>

                    <!-- Menu -->
                    <div class="menuWrapper">
                        <div class="editInfo item" onclick="editInfo()">
                            <p>Edit Information</p>
                        </div>

                        <div class="logOut item" onclick="logOut()">
                            <p>Log Out</p>
                        </div>

                        <div class="deleteAccount item" onclick="deleteAccount()">
                            <p>Delete Account</p>
                        </div>
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
    //애니메이션 효과
    var item = document.querySelectorAll('.item');
    var cnt = 0; 

    function activeFunc(){
        item[cnt].classList.add('active');
        cnt++;    
        if(cnt >= item.length){
            clearInterval(addActive);
        }
    }

    var addActive = setInterval(activeFunc, 130);

    //정보수정
    function editInfo() {
        window.location.href = 'editInfo.php';
    }

    //로그아웃
    function logOut() {
        // 확인 창 표시
        var confirmLogout = confirm("Do you want to Log Out?");

        // 확인이 클릭되면 로그아웃 실행
        if (confirmLogout) {
            //로그아웃 세션
            window.location.href = '../login/logout.php';
        } else {
            // 취소가 클릭되면 아무 동작 없음
        }
    }

    //탈퇴
    function deleteAccount() {
        window.location.href = 'deleteInfo.php';
    }

</script>

</body>
</html>