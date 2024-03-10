<?php
// Function to sanitize user input
function sanitizeInput($input) {
    return htmlspecialchars(trim($input));
}

$servername = "localhost";
$username = "root";
$password = "";
$database = "shoppping_site";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$signup_error = "";
$first_name_value = $last_name_value = $email_value = $number_value = $address_value = $province_value = ""; // Initialize the variable
$country_value = "Pakistan"; // Initialize the variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name_value = sanitizeInput($_POST["first_name"]);
    $last_name_value = sanitizeInput($_POST["last_name"]);
    $email_value = sanitizeInput($_POST["email"]);
    $password = $_POST["password"];
    $number_value = sanitizeInput($_POST["number"]);
    $province_value = sanitizeInput($_POST["province"]);
    $address_value = sanitizeInput($_POST["address"]);

    // Validate password
    if (strlen($password) < 8 || !preg_match("/[0-9]/", $password) || !preg_match("/[A-Z]/", $password) || !preg_match("/[a-z]/", $password)) {
        $signup_error = "Password must be at least 8 characters long and contain at least one number, one uppercase letter, and one lowercase letter.";
    }

    // Validate phone number
    if (!preg_match("/^\d{11}$/", $number_value)) {
        $signup_error = "Invalid phone number. Please enter 11 digits.";
    }

    // Validate email
    if (!filter_var($email_value, FILTER_VALIDATE_EMAIL) || !preg_match("/@gmail\.com$/", $email_value)) {
        $signup_error = "Invalid email address. Please enter a valid Gmail address.";
    }

    // Check if the email already exists
    $sql_check_email = "SELECT * FROM customer WHERE email = '$email_value'";
    $result = $conn->query($sql_check_email);

    if ($result->num_rows > 0) {
        $signup_error = "Email address already exists. Please choose a different one.";
    } else {
        // Profile picture handling
        $profile_picture = $_FILES['profile_picture'];
        $profile_picture_name = sanitizeInput($profile_picture['name']);
        $profile_picture_type = $profile_picture['type'];
        $profile_picture_tmp = $profile_picture['tmp_name'];

        // Check if a file is selected
        if (!empty($profile_picture_name) && is_uploaded_file($profile_picture_tmp)) {
            // Check if the file is an image and has the correct extension
            $allowed_extensions = array('jpg', 'jpeg', 'png');
            $file_extension = strtolower(pathinfo($profile_picture_name, PATHINFO_EXTENSION));

            if (in_array($file_extension, $allowed_extensions) && strpos($profile_picture_type, 'image') !== false) {
                $profile_picture_blob = file_get_contents($profile_picture_tmp);
            } else {
                $signup_error = "Invalid file format. Please upload a valid image (JPG or PNG).";
            }
        }

        if (empty($signup_error)) {
            $sql_insert = "INSERT INTO customer (PASSWORD, first_name, last_name, email, NUMBER, country, province, address, profile_picture)
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql_insert);
            $stmt->bind_param('sssssssss', $password, $first_name_value, $last_name_value, $email_value, $number_value, $country_value, $province_value, $address_value, $profile_picture_blob);

            if ($stmt->execute()) {
                $user_id = $conn->insert_id;
                session_start();
                $_SESSION['user_id'] = $user_id;
                header('Location: profile.php'); // Change success.php to the actual page you want to redirect to
                exit();
            } else {
                $signup_error = "Error: " . $stmt->error;
            }

            $stmt->close();
        }
    }

    if (empty($signup_error)) {
        // Redirect after successful form submission
        header("Location: success.php"); // Change success.php to the actual page you want to redirect to
        exit();
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        .box {
           display: flex;
           justify-content: space-around;
           margin: auto;
           width: 50%;
           text-align: center;
           border: 1px solid #000;
           border-radius: 20px;
           height: 90%;
           box-shadow: 4px 8px 8px rgba(0, 0, 0, 0.2); /* Add box shadow */
        }


        .left, .right {
            flex-basis: 48%; /* Adjust as needed */
            padding: 20px;
        }

        h2 {
            padding-left: 10px;
        }

        input, select {
            outline: none;
            border: 1px solid #9c9c9c;
            background: transparent;
            border-radius: 5px;
            padding: 0 10px;
            height: 35px;
            font-size: 15px;
            margin: 10px 0;
            display: block;
            width: 100%;
        }

        input:focus, select:focus {
            border: 1.5px solid #1f52f9;
        }

        input[type="file"] {
            padding:5px;
            padding-left: 30px;
            color: #b56a07;
            font : 20px;
            margin-top: 30px;
        }

        .custom-file-upload {
            border: 1px solid #ccc;
            display: inline-block;
            padding: 6px 12px;
            cursor: pointer;
        }

        button {
            background: #4971f6;
            color: #fff;
            border: none;
            padding: 10px 30px;
            border-radius: 5px;
            margin: 20px 0;
            cursor: pointer;
            font-size: 15px;
        }

        button:hover {
            background: #254fdb;
        }

        .error {
            color: red;
            margin-top: 10px;
        }

        .bt {
            color:#333;
            background-color: #fca12a;
            position:relative;
            border: none;
            text-align: center;
            transition: all 0.5s;
            cursor: pointer;
            margin: 5px;
            width : 100%;
            height : 60px;
            font-size: 20px;
        }

        .bt:hover{
            color: #fca12a;
            background-color:#333;
        }
    </style>
</head>
<body  style= "margin-top: 100px;">
    <div class="box">
        <br><br>
        <div class="left">
            <h1>Signup</h1>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
                <label for="first_name">First Name:</label>
                <input type="text" name="first_name" value="<?php echo $first_name_value; ?>" required>
                
                <label for="last_name">Last Name:</label>
                <input type="text" name="last_name" value="<?php echo $last_name_value; ?>" required>

                <label for="email">Email:</label>
                <input type="text" name="email" value="<?php echo $email_value; ?>" required>

                <label for="password">Password:</label>
                <input type="password" name="password" required>
                  
                <label for="number">Phone Number:</label>
                <input type="text" name="number" value="<?php echo $number_value; ?>" required>
            </div>
            
            <div class="right">
                <br><br><br><br>
                <label for="country">Country:</label>
                <input type="text" name="country" value="<?php echo $country_value; ?>" readonly>

                <label for="province">Province:</label>
                <select name="province">
                    <option value="Punjab" <?php if ($province_value == "Punjab") echo "selected"; ?>>Punjab</option>
                    <option value="Sindh" <?php if ($province_value == "Sindh") echo "selected"; ?>>Sindh</option>
                    <option value="KPK" <?php if ($province_value == "KPK") echo "selected"; ?>>KPK</option>
                    <option value="Balochistan" <?php if ($province_value == "Balochistan") echo "selected"; ?>>Balochistan</option>
                    <option value="Gilgit Baltistan" <?php if ($province_value == "Gilgit Baltistan") echo "selected"; ?>>Gilgit Baltistan</option>
                    <option value="Kashmir" <?php if ($province_value == "Kashmir") echo "selected"; ?>>Kashmir</option>
                </select>

                <label for="address">Address:</label>
                <input type="text" name="address" value="<?php echo $address_value; ?>" required>
                <input type="file" name="profile_picture" accept=".jpg, .jpeg, .png"> <!-- Add this line for the profile picture -->
                <br>
                <input type="submit" name="next" value="Sign-UP" class="bt">
            </div>

        </form>
        </div>
        <center>
        <?php
        if (isset($signup_error)) {
            echo '<p class="error">' . $signup_error . '</p>';
        }
        ?>
        </center>
    
</body>
</html>
