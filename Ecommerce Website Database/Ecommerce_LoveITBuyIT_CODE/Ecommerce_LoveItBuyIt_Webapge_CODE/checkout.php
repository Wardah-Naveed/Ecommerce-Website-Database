<?php
include("header.php");
$servername = "localhost";
$username = "root";
$password = "";
$database = "shoppping_site";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the session is not already active before starting it
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$updatedSold = 0;
// Function to fetch product information from the database
function getProductsFromDatabase($conn, $user_id) {
    $products = array();

    $sql = "SELECT p.product_id, p.product_name, p.price, p.discount, p.inventory, cl.quantity
            FROM product p
            JOIN checkout_list cl ON p.product_id = cl.product_id
            WHERE cl.cust_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }

    return $products;
}

// Fetch products from the database
$products = getProductsFromDatabase($conn, $user_id);

// Calculate total price and quantity
$totalPrice = 0;
$totalQuantity = 0;

foreach ($products as $product) {
    $discountedPrice = $product['price'] - ($product['price'] * $product['discount'] / 100);
    $totalPrice += $discountedPrice * $product['quantity'];
    $totalQuantity += $product['quantity'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process the form submission (checkout)
    $paymentMethod = $_POST['pay'];

    // Insert data into each_order and order_cust tables
    $date_time = date("Y-m-d H:i:s");
    $no_of_products = count($products); // Corrected counting of products
    $price_total = $totalPrice + 5; // Total price including delivery fee

    $sqlOrderCust = "INSERT INTO order_cust (cust_id, date_time, no_of_products, completed, price_total)
                     VALUES (?, ?, ?, 0, ?)";
    $stmtOrderCust = $conn->prepare($sqlOrderCust);
    $stmtOrderCust->bind_param("issd", $user_id, $date_time, $no_of_products, $price_total);
    $stmtOrderCust->execute();
    $order_id = $stmtOrderCust->insert_id;

    $sqlEachOrder = "INSERT INTO each_order (order_id, product_id, quantity) VALUES (?, ?, ?)";
    $stmtEachOrder = $conn->prepare($sqlEachOrder);

    foreach ($products as $product) {
        $productId = $product['product_id'];
        $quantity = $product['quantity'];
        $stmtEachOrder->bind_param("iii", $order_id, $productId, $quantity);
        $stmtEachOrder->execute();

        // Update inventory and sold values
        $updatedInventory = $product['inventory'] - $quantity;
        $updatedSold = $product['sold'] + $quantity;
        
        // Update product table with new inventory and sold values
        $sqlUpdateProduct = "UPDATE product SET inventory = ?, sold = ? WHERE product_id = ?";
        $stmtUpdateProduct = $conn->prepare($sqlUpdateProduct);
        $stmtUpdateProduct->bind_param("iii", $updatedInventory, $updatedSold, $productId);
        $stmtUpdateProduct->execute();
    }

    // Delete data from checkout_list
    $sqlDeleteCheckout = "DELETE FROM checkout_list WHERE cust_id = ?";
    $stmtDeleteCheckout = $conn->prepare($sqlDeleteCheckout);
    $stmtDeleteCheckout->bind_param("i", $user_id);
    $stmtDeleteCheckout->execute();

    // Redirect to the confirmation page
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout Page</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f2f2f2;
        }

        select option {
            font-size: 14px;
            background-color: white;
        }

        select option:checked {
            background-color: #4CAF50;
            color: white;
        }

        .right1 span{
            font-size: 18px;
        }

        /* Style the table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <center>
        <div class="container">
            <h3> CART TOTALS</h3>
            <hr color="orange" width="20%" align="left"><br>
            <form method="post" onsubmit="return validateForm()">
                <table>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Discount</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                    <?php 
                        $subtotal = 0; // Initialize subtotal outside the loop
                        foreach ($products as $product): 
                            $productId = $product['product_id'];
                            $quantity = $product['quantity'];
                            $price = $product['discount'] > 0 ? $product['price'] - ($product['price'] * $product['discount'] / 100) : $product['price'];

                            $subtotal += $price * $quantity; // Update subtotal here
                    ?>
                        <tr>
                            <td><?php echo $product['product_name']; ?></td>
                            <td><?php echo '$' . number_format($price, 2); ?></td>
                            <td><?php echo $product['discount'] > 0 ? $product['discount'] . '%' : '0%'; ?></td>
                            <td><?php echo $quantity; ?></td>
                            <td><?php echo '$' . number_format($price * $quantity, 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <!-- Display subtotal after the loop -->
                <span><div id="subtotal">Subtotal: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$<?php echo $subtotal; ?></span></div>
                <span><div id="delivery-fee">(+) Delivery Fee: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$&nbsp;5</div></span>
                <hr color="orange">
                <span><div id="total">Total:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span id="total-value">$<?php echo $subtotal + 5; ?></span></div></span>
                <hr color="orange" width="20%" align="left"><br>
                Payment_method:
                <label>
                    <input type="radio" name="pay" value="cod"> Cash on delivery
                </label>
                <br><br>
                <br><br>
                <center><button type="submit" class="check" style="width: 80%; border-radius: 5px;">CHECKOUT</button></center>
            </form>
        </div>
    </center>
    <div class="footer">
        <h4>This Site Is Created As A DBMS Project</h4>
    </div>
    <script>
        function validateForm() {
            var payment = document.querySelector('input[name="pay"]:checked');

            if (payment === null) {
                alert("Please select a payment method before placing the order.");
                return false; // Prevent form submission
            } else {
                alert("Checkout successful! <?php echo $updatedSold; ?>");
                // Additional logic or redirection can be added here

                // Remove checkout_list and insert data into each_order and order_cust tables

                // Redirect to the confirmation page
                alert("SUCCESS!");
                return true; // Allow form submission
            }
        }
    </script>
</body>
</html>
