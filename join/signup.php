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
    <link rel="stylesheet" href="signup.css">
    <title>SignUp</title>
</head>

<body>
    <div class="container-fluid wrapper">
        <div class="container">

            <!-- TOKA -->
            <div class="titleWrapper">
                <h1 class="t">T</h1>
                <h1 class="o">O</h1>
                <h1 class="k">K</h1>
                <h1 class="a">A</h1>
            </div><br>

            <form action="signup.php" method="post">

                <!-- ID & Password & Email & Name -->
                <input type="text" id="tempId" class="box" placeholder="ID" autocomplete='off' required>
                <input type="hidden" name="id" id="id" required>
                <div class="duplicateWrap">
                    <p id="checkId">Check for duplicate</p>
                    <input type="button" id="checkBtn" value="Click" onclick="checkId();">
                </div>

                <input type="password" id="pw" name="password" class="box" placeholder="Password" required><br>
                <input type="email" id="email" name="email" class="box" placeholder="Email" autocomplete='off'><br>
                <input type="text" id="name" name="name" class="box" placeholder="Name" autocomplete='off' required><br>

                <!-- SignUp -->
                <div class="btnWrapper">
                    <button type="submit" id="btn" disabled>SignUp</button>
                </div>

            </form>

            <!-- Already have an account? LogIn -->
            <p class="loginBtn">Already have an account? &nbsp;<a href="../login/login.php">Login.</a></p>

        </div>
    </div>


    <script src="signup.js"></script>
</body>

</html>

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

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$id = $_POST["id"];
		$password = $_POST["password"];
        $email = $_POST["email"];
        $name = $_POST["name"];

        $sql = "INSERT into users (id, password, email, name) values('$id', '$password', '$email', '$name')";
        
        if (mysqli_query($conn, $sql)) {
            $sql2 = "SELECT identifier, name FROM users WHERE id = '$id'";
            $result = $conn->query($sql2);
            $user = $result->fetch_array(MYSQLI_ASSOC);

            session_start();
            $_SESSION['identifier'] = $user['identifier'];
            $_SESSION['name'] = $user['name'];
            header("Location: ../main/home.php"); 
    
        } else {    
            echo "<script>alert('Error!');</script>";    
        }		

	}
?>