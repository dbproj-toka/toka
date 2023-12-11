<?php
    $host = "localhost";
    $user = "root";
    $pass = "qkrwnsdyd0416";
    $db = "toka";

	$conn = new mysqli($host, $user, $pass, $db);

    $id= $_GET["userid"];
    $sql= "SELECT * FROM users where id='$id'";
    $result = mysqli_fetch_array(mysqli_query($conn, $sql));

    if(!$result){
        echo "<span style='color:blue;'>$id</span> is available.";
       ?><p><input type=button value="Use this ID" onclick="opener.parent.decide(); window.close();"></p>
        
    <?php
    } else {
        echo "<span style='color:red;'>$id</span> not available.";
        ?><p><input type=button value="Use different ID" onclick="opener.parent.change(); window.close()"></p>
    <?php
    }
?>