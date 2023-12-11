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
    <link rel="stylesheet" href="login.css">
    <title>Login</title>
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

            <form action="login.php" method="post">

                <!-- ID & Password -->
                <input type="text" id="id" name="id" class="box" placeholder="ID" autocomplete='off' required><br>
                <input type="password" id="pw" name="password" class="box" placeholder="Password" required>

                <!-- Forget Password? -->
                <div class="forgetpw">
                    <p class="forget"><a href="findpw.php">Forget Password?</a></p>
                </div>

                <!-- LogIn -->
                <div class="btnWrapper">
                    <button type="submit" id="btn">LogIn</button>
                </div>

            </form>

            <!-- Not a member? SignUp -->
            <p class="signupBtn">Not a member? &nbsp;<a href="../join/signup.php">Sign Up</a></p>

        </div>
    </div>

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
		$id = $_POST["id"];
		$password = $_POST["password"];

		//$sql = "SELECT id FROM users WHERE id = '$id' and password = '$password'";
        $sql = "SELECT * FROM users WHERE id = '$id'";
		$result = $conn->query($sql);

        if($result->num_rows < 1) {
            echo "<script>alert('No User Information');</script>";
        } else {
            $user = $result->fetch_array(MYSQLI_ASSOC);

            if($user['password'] == $password){
                session_start();
                $_SESSION['identifier'] = $user['identifier'];
                $_SESSION['name'] = $user['name'];
                header("Location: ../main/home.php?identifier=" . $user['identifier']); 
            } else {
                echo "<script>alert('Wrong Password');</script>";
            }
        }

	}
?>