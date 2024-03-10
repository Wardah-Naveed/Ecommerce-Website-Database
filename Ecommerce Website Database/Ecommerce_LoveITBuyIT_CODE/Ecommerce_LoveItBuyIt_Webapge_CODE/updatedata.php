<?php
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

function checkCategoriesExist($conn, $categoryIds) {
    // Function to check if categories exist (unchanged)
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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['insert_data'])) {
    $productName = $_POST['product_name'];
    $category1 = $_POST['category_id_type1'];
    $category2 = $_POST['category_id_type2'];
    $category3 = $_POST['category_id_type3'];
    $inventory = $_POST['inventory'];
    $price = $_POST['price'];
    $description = $_POST['product_description'];
    $discount = $_POST['discount'];

    // Validate file types
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];

    $image1Type = $_FILES['image1']['type'];
    $image2Type = $_FILES['image2']['type'];
    $image3Type = $_FILES['image3']['type'];

    if (!in_array($image1Type, $allowedTypes) || !in_array($image2Type, $allowedTypes) || !in_array($image3Type, $allowedTypes)) {
        echo "Error: Only JPEG, JPG, or PNG files are allowed.<br>";
    } else {
        // Check if the product name already exists
        $checkProductQuery = "SELECT * FROM `product` WHERE `product_name` = ?";
        $checkProductStmt = $conn->prepare($checkProductQuery);
        $checkProductStmt->bind_param('s', $productName);
        $checkProductStmt->execute();
        $checkProductResult = $checkProductStmt->get_result();

        if ($checkProductResult->num_rows > 0) {
            echo "Error: Product '{$productName}' already exists.<br>";
        } else {
            // Process file uploads
            $image1Data = resizeImage(file_get_contents($_FILES['image1']['tmp_name']), 800);
            $image2Data = resizeImage(file_get_contents($_FILES['image2']['tmp_name']), 800);
            $image3Data = resizeImage(file_get_contents($_FILES['image3']['tmp_name']), 800);

            // Insert or update product
            $sql = "INSERT INTO `product` (`product_name`, `category_id_type1`, `category_id_type2`, `category_id_type3`, `inventory`, `price`, `product_description`, `image1`, `image2`, `image3`, `discount`)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ON DUPLICATE KEY UPDATE `inventory` = `inventory` + VALUES(`inventory`)";

            $stmt = $conn->prepare($sql);

            $stmt->bind_param('sssiidsssss', $productName, $category1, $category2, $category3, $inventory, $price, $description, $image1Data, $image2Data, $image3Data, $discount);

            if ($stmt->execute()) {
                echo "Product '{$productName}' inserted/updated successfully.<br>";
            } else {
                echo "Error: " . $stmt->error . "<br>";
            }

            $stmt->close();
        }

        $checkProductStmt->close();
    }
}

$result = $conn->query("SELECT * FROM `product`");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Data</title>
</head>
<body>
    <h2>Product Data</h2>
    
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<p>{$row['product_name']} - {$row['inventory']} units</p>";

            // Display image stored in BLOB format
            echo "<img src='data:image/png;base64," . base64_encode($row['image1']) . "' alt='Image 1'><br>";
            echo "<img src='data:image/png;base64," . base64_encode($row['image2']) . "' alt='Image 2'><br>";
            echo "<img src='data:image/png;base64," . base64_encode($row['image3']) . "' alt='Image 3'><br>";
        }
    } else {
        echo "No products found.";
    }
    ?>

    <hr>

    <h2>Insert Data</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
        <label>Product Name: <input type="text" name="product_name" required></label><br>
        <label>Category 1: <input type="text" name="category_id_type1" required></label><br>
        <label>Category 2: <input type="text" name="category_id_type2" required></label><br>
        <label>Category 3: <input type="text" name="category_id_type3" required></label><br>
        <label>Inventory: <input type="number" name="inventory" required></label><br>
        <label>Price: <input type="text" name="price" required></label><br>
        <label>Description: <textarea name="product_description" required></textarea></label><br>
        <input type="file" name="image1" accept="image/jpeg, image/jpg, image/png" required><br>
        <input type="file" name="image2" accept="image/jpeg, image/jpg, image/png" required><br>
        <input type="file" name="image3" accept="image/jpeg, image/jpg, image/png" required><br>
        <label>Discount: <input type="text" name="discount" required></label><br>
        <button type="submit" name="insert_data">Insert Data</button>
    </form>
</body>
</html>

<?php
$conn->close();
?>
