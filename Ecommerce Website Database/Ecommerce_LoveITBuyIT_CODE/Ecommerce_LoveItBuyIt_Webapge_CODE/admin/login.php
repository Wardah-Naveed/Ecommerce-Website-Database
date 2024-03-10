<?php

// Check if the session is not active before starting it
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is already logged in
if (isset($_SESSION['admin'])) {
    header('Location: dashboard.php');
    exit();
}

include("includeDB.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $sql_check = "SELECT * FROM admin WHERE admin_name = '$email' AND admin_password = '$password'";
    $result = $conn->query($sql_check);

    if ($result->num_rows > 0) {
        // User exists, set session and redirect to profile
        $row = $result->fetch_assoc();
        $_SESSION['admin'] = $row['admin_name'];
        header('Location: dashboard.php');
        exit();
    } else {
        $login_error = "Invalid email or password";
    }
}

$conn->close();
?>


<!DOCTYPE html>
<head>
    <title>Login | Admin Panel</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        .box {
            margin: auto;
            margin-top: -0.7 rem ;
            width: 25%;
            text-align: center;
            min-width: 400px;
            max-width: 600px;
            border: 1px solid #dadce0;
            border-radius: 8px;
        }

        .extra {
            background-color: white;
            outline: none;
            box-sizing: border-box;
            border-style: none;
            color: black;
        }

        .box h2 {
            font-family: sans-serif;
            font-weight: bold;
            margin-top: 2px;
        }

        .box p {
            font-family: sans-serif;
            margin-top: -5px;
        }

        .boxText {
            text-align: left;
        }

        a {
            text-decoration: none;
            font-weight: bold;
            color: #fca12a;
        }

        .outterText {
            padding-right: 8%;
            text-align: center;
            margin-top: -2%;
        }

        .text {
            font-family: sans-serif;
            font-weight: normal;
            font-size: small;
            padding-left: 7%;
            padding-top: 3%;
        }

        .box .text .button {
            margin: -15% 0 10% 45%;
            border: 1px solid #fca12a;
            background: #fca12a;
            font-family: sans-serif;
            font-weight: bold;
            font-size: small;
            text-align: center;
            padding: 12px 24px;
            border-radius: 4px;
            transition: color .15s ease-in-out;
        }

        .box .text .button:hover {
            background-color: #000000;
            border-color: #fc9129;
        }

        .box img {
            width: 20%;
            margin-top: 50px;
        }

        .inputBox {
            position: relative;
        }

        .inputBox input {
            width: 80%;
            height: 35px;
            padding-left: 5%;
            color:white;
            border: 1px solid #ccc;
            background: transparent;
            border-radius: 4px;
        }

        .inputBox label {
            position: absolute;
            top: 0;
            left: 10%;
            padding: 10px 0;
            font-size: 100%;
            color: grey;
            pointer-events: none;
            transition: 0.2s;
        }

        .inputBox input:focus~label,
        .inputBox input:valid~label,
        .inputBox input:not([value=""])~label {
            top: -18px;
            left: 10%;
            color: #1a73e8;
            font-size: 75%;
            background-color: black;
            height: 10px;
            padding-left: 5px;
            padding-right: 5px;
        }

        .inputBox input:focus {
            outline: none;
            border: 2px solid #1a73e8;
        }

.bt {
    color:#333;
    background-color: #fca12a;
	position:relative;
    border: none;
    text-align: center;
    padding: 10px;
    transition: all 0.5s;
    cursor: pointer;
    margin: 5px;
    width : 80%;
}

.bt:hover{
    color: #fca12a;
    background-color:#333;
}
    </style>
</head>

<body>
    <br><br><br><br>
    <div class="box">
        <br>
        <h2 style="color:white">Admin Panel</h2>
        <p style="color:white">Login to continue</p>
        <form method="POST">
            <div class="inputBox">
                <input type="text" name="email" id="email" required onkeyup="this.setAttribute('value', this.value);"
                    value="">
                <label> &nbsp;&nbsp; Username</label>
            </div>
            <br>
            <div class="inputBox">
                <input type="password" name="password" id="password" required
                    onkeyup="this.setAttribute('value', this.value);" value="">
                <label>&nbsp;&nbsp; Password</label>
            </div>
            
            <br>
            <div>
            <input type="submit" name="next"
                    value="Login" class="bt"></div>
                    <br>
        </form>
    </div>
    <center>
        <?php
        if (isset($login_error)) {
            echo '<p class="error">' . $login_error . '</p>';
        }
        ?>
        </center>
</body>
</html>
