<!-- header.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Love It Buy It</title>
</head>

<body>
    <div class="head">
        <div class="header">
            <div class="navbar grid">
                <div class="logo"><img src="imgs/logo.png" alt="" class="logoimg"></div>
                <div class="search">
                </div>
                <div class="loginbtn">
                    <?php
                    session_start();
                    if (isset($_SESSION['user_id'])) {
                        echo '<a href="profile.php" class="button right"><i class="fa fa-user-circle"></i></a>';
                    } else {
                        echo '<a href="login.php" class="button right"><i class="fa fa-sign-in"></i></a>';
                    }
                    ?>
                </div>
                <div class="cartbtn"><a href="cartpage.php" class="button left"><i class="fa fa-shopping-cart"></i></a></div>
            </div>
        </div>
    </div>

    <div id="nav" class="background">
        <ul>
            <li><div><a href="index.php" class="active">Home</a></div></li>
            <li><div><a href="clothing.php" class="">Clothing</a></div></li>
            <li><div><a href="tech.php" class="">Tech</a></div></li>
            <li><div><a href="acessories.php" class="">Accessories</a></div></li>
            <li><div><a href="contact.php" class="">Contact Us</a></div></li>
        </ul>
    </div>
    <br><br>
</body>
</html>

<?php
// Check if user_id is set in the session
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    header('Location: login.php');
    exit();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "";
$database = "shoppping_site";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function resizeImage($imageData, $maxWidth) {
    $image = imagecreatefromstring($imageData);

    $width = imagesx($image);
    $height = imagesy($image);

    // Resize the image if it's larger than the specified width
    if ($width > $maxWidth) {
        $newWidth = $maxWidth;
        $newHeight = ($maxWidth / $width) * $height;

        $resizedImage = imagescale($image, $newWidth, $newHeight);
    } else {
        $resizedImage = $image;
    }

    // Get the image data in JPEG format
    ob_start();
    imagejpeg($resizedImage);
    $resizedImageData = ob_get_clean();

    imagedestroy($image);
    imagedestroy($resizedImage);

    return $resizedImageData;
}

// Retrieve existing customer data
$customerQuery = "SELECT * FROM `customer` WHERE `cust_id` = ?";
$customerStmt = $conn->prepare($customerQuery);
$customerStmt->bind_param('i', $user_id);
$customerStmt->execute();
$customerResult = $customerStmt->get_result();

$existingCustomerData = $customerResult->fetch_assoc();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_data'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $NUMBER = $_POST['NUMBER'];
    $province = $_POST['province'];
    $address = $_POST['address'];

    // Check if the email already exists for another customer
    $checkEmailQuery = "SELECT cust_id FROM `customer` WHERE `email` = ? AND `cust_id` <> ?";
    $checkEmailStmt = $conn->prepare($checkEmailQuery);
    $checkEmailStmt->bind_param('si', $email, $user_id);
    $checkEmailStmt->execute();
    $checkEmailResult = $checkEmailStmt->get_result();

    if ($checkEmailResult->num_rows > 0) {
        echo "Error: Email already exists for another customer. Choose a different email.";
        exit(); // Stop execution if email exists for another customer
    }

    // Check if the NUMBER is 11 digits without dashes
    if (!preg_match('/^\d{11}$/', $NUMBER)) {
        echo "Error: NUMBER should be 11 digits long without dashes.";
        exit();
    }

    // Process file uploads
    $profilePictureData = resizeImage(file_get_contents($_FILES['profile_picture']['tmp_name']), 800);

    // Update customer data
    $updateQuery = "UPDATE `customer` SET `first_name` = ?, `last_name` = ?, `email` = ?, `NUMBER` = ?, `province` = ?, `address` = ?, `profile_picture` = ? WHERE `cust_id` = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param('sssssssi', $first_name, $last_name, $email, $NUMBER, $province, $address, $profilePictureData, $user_id);

    if ($updateStmt->execute()) {
        echo "Customer data updated successfully.<br>";

        // Redirect to profile.php
        header('Location: profile.php');
        exit();
    } else {
        echo "Error updating customer data: " . $updateStmt->error . "<br>";
    }

    $updateStmt->close();
}

$customerStmt->close();

// Close the connection after all database operations
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Data</title>
    <style>
body {
    font-family: Arial, sans-serif;
    margin: 20px;
}

h2 {
    color: #333;
}

form {
    display: flex;
    flex-wrap: wrap;
    gap: 10px; /* Reduced gap */
    max-width: 600px;
}

label {
    width: 100%;
    box-sizing: border-box;
}

input,
select,
button {
    width: calc(100% - 10px); /* Adjusted width */
    padding: 10px;
    margin-top: 5px;
}

button {
    background-color: #4caf50;
    color: white;
    border: none;
    padding: 10px 20px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    cursor: pointer;
    border-radius: 4px;
}

.left-column,
.right-column {
    width: 48%; /* Adjusted width */
}

.container {
    display: flex;
    justify-content: space-between;
}

/* Added styles for better spacing */
.container > div {
    margin-left: 40px; /* Adjusted margin */
}

/* Clearfix for container */
.container::after {
    content: "";
    display: table;
    clear: both;
}


    </style>
</head>

<body>
<div class="container">
        <div class="left-column">
    <h2>Customer Data</h2>

    <a href ="profile.php">GO BACK TO PROFILE</a>
    <?php
    if ($existingCustomerData) {
        echo "<p>Name: {$existingCustomerData['first_name']} {$existingCustomerData['last_name']}</p>";
        echo "<p>Email: {$existingCustomerData['email']}</p>";
        echo "<p>Number: {$existingCustomerData['NUMBER']}</p>";
        echo "<p>Country: {$existingCustomerData['country']}</p>";
        echo "<p>Province: {$existingCustomerData['province']}</p>";
        echo "<p>Address: {$existingCustomerData['address']}</p>";

        // Display profile picture stored in BLOB format
        echo "<img src='data:image/png;base64," . base64_encode($existingCustomerData['profile_picture']) . "' alt='Profile Picture'><br>";
    } else {
        echo "Customer not found.";
    }
    ?>

    <hr>
</div>
        <div class="right-column">
    <h2>Update Data</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
        <!-- Populate input fields with existing customer data -->
        <label>First Name: <input type="text" name="first_name" value="<?php echo $existingCustomerData['first_name'] ?? ''; ?>" required></label>
        <label>Last Name: <input type="text" name="last_name" value="<?php echo $existingCustomerData['last_name'] ?? ''; ?>" required></label>
        <label>Email: <input type="email" name="email" value="<?php echo $existingCustomerData['email'] ?? ''; ?>" required></label>
        <label>Number: <input type="tel" name="NUMBER" pattern="[0-9]{11}" value="<?php echo $existingCustomerData['NUMBER'] ?? ''; ?>" required></label>
        <input type="hidden" name="country" value="Pakistan"> <!-- Country is fixed as Pakistan -->
        <label>Province:
            <select name="province" required>
                <option value="Punjab" <?php echo isset($existingCustomerData['province']) && $existingCustomerData['province'] == 'Punjab' ? 'selected' : ''; ?>>Punjab</option>
                <option value="Sindh" <?php echo isset($existingCustomerData['province']) && $existingCustomerData['province'] == 'Sindh' ? 'selected' : ''; ?>>Sindh</option>
                <option value="Khyber Pakhtunkhwa" <?php echo isset($existingCustomerData['province']) && $existingCustomerData['province'] == 'Khyber Pakhtunkhwa' ? 'selected' : ''; ?>>Khyber Pakhtunkhwa</option>
                <option value="Balochistan" <?php echo isset($existingCustomerData['province']) && $existingCustomerData['province'] == 'Balochistan' ? 'selected' : ''; ?>>Balochistan</option>
                <option value="Gilgit-Baltistan" <?php echo isset($existingCustomerData['province']) && $existingCustomerData['province'] == 'Gilgit-Baltistan' ? 'selected' : ''; ?>>Gilgit-Baltistan</option>
            </select>
        </label>
        <label>Address: <input type="text" name="address" value="<?php echo $existingCustomerData['address'] ?? ''; ?>" required></label>

        <!-- Add a new input field for the profile picture -->
        <label>Profile Picture: <input type="file" name="profile_picture" accept="image/jpeg, image/jpg, image/png" required></label>

        <button type="submit" name="update_data">Update Data</button>
    </form>

    </div>
    </div>
</body>
</html>
