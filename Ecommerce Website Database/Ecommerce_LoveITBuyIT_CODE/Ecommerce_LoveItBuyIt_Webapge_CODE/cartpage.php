<?php
include("header.php");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shoppping_site";

$conn = new mysqli($servername, $username, $password, $dbname);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    header('Location: login.php');
    exit();
}

// Fetch cart items from the database
$sqlCart = "SELECT c.product_id, p.product_name, p.price, p.discount, c.quantity
            FROM cart c
            JOIN product p ON c.product_id = p.product_id
            WHERE c.cust_id = ?";
$stmtCart = $conn->prepare($sqlCart);
$stmtCart->bind_param("i", $user_id);
$stmtCart->execute();
$resultCart = $stmtCart->get_result();

// Initialize total price
$totalPrice = 0;

// Handle delete item action
if (isset($_POST['delete_item'])) {
    $productId = $_POST['delete_item'];

    // Delete the item from the cart
    $sqlDelete = "DELETE FROM cart WHERE cust_id = ? AND product_id = ?";
    $stmtDelete = $conn->prepare($sqlDelete);
    $stmtDelete->bind_param("ii", $user_id, $productId);
    $stmtDelete->execute();
    $stmtDelete->close();
}

// Handle checkout action
if (isset($_POST['checkout'])) {
    // Prepare and execute insert statement for each selected item
    if (isset($_POST['checkout_items']) && is_array($_POST['checkout_items'])) {
        $sqlInsert = "INSERT INTO checkout_list (cust_id, product_id, quantity, total)
                      SELECT ?, c.product_id, c.quantity, 
                             (CASE WHEN p.discount > 0 THEN (p.price - (p.price * p.discount / 100)) * c.quantity
                                   ELSE p.price * c.quantity END)
                      FROM cart c
                      JOIN product p ON c.product_id = p.product_id
                      WHERE c.cust_id = ? AND c.product_id = ?";
        $stmtInsert = $conn->prepare($sqlInsert);

        foreach ($_POST['checkout_items'] as $productId) {
            if ($stmtInsert) {
                $stmtInsert->bind_param("iii", $user_id, $user_id, $productId);
                $stmtInsert->execute();
            }
        }

        // Clear the cart for the selected items
        $sqlClearCart = "DELETE FROM cart WHERE cust_id = ? AND product_id IN (" . implode(",", $_POST['checkout_items']) . ")";
        $stmtClearCart = $conn->prepare($sqlClearCart);
        $stmtClearCart->bind_param("i", $user_id);
        $stmtClearCart->execute();
        $stmtClearCart->close();
    }
}

// Fetch cart items again after potential modifications
$stmtCart->execute();
$resultCart = $stmtCart->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cartpage</title>
    <link rel="stylesheet" href="addtocart.css">
    <link rel="stylesheet" href="styles.css">
    <script src="https://kit.fontawesome.com/da72248209.js" crossorigin="anonymous"></script>
    <style>
        /* Styles for the small-sized webpage or box */
        .popup-box {
            display: none; /* Initially hidden */
            position: fixed;
            top: 60%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 400px;
            height: 200px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            z-index: 1; /* Ensure it's above the background */
        }
    </style>
</head>

<body>
    <center>
        <div class="handel">
            <div class="sidebar">
                <div class="head1">
                    <p>My Cart</p>
                </div>
                <form action="cartpage.php" method="post">
                    <?php
                    // Display cart items on the webpage with detailed information, checkboxes, and delete buttons
                    if ($resultCart->num_rows > 0) {
                        echo '<table>';
                        echo '<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total Price</th><th>Checkout</th><th>Delete</th></tr>';
                        while ($cartItem = $resultCart->fetch_assoc()) {
                            $productId = $cartItem['product_id'];
                            $discountedPrice = $cartItem['price'] - ($cartItem['price'] * $cartItem['discount'] / 100);
                            $displayPrice = $cartItem['discount'] > 0 ? $discountedPrice : $cartItem['price'];

                            echo '<tr>';
                            echo '<td>' . $cartItem['product_name'] . '</td>';
                            echo '<td>$' . number_format($displayPrice, 2) . '</td>';
                            echo '<td>' . $cartItem['quantity'] . '</td>';
                            echo '<td>$' . number_format($displayPrice * $cartItem['quantity'], 2) . '</td>';
                            echo '<td><input type="checkbox" name="checkout_items[]" value="' . $productId . '"></td>';
                            echo '<td><button type="submit" name="delete_item" value="' . $productId . '">Delete</button></td>';
                            echo '</tr>';
                            
                            // Update total price for each item
                            $totalPrice += $displayPrice * $cartItem['quantity'];
                        }
                        echo '</table>';
                        echo '<div class="foot">';
                        echo '<h3>Total</h3>';
                        echo '<h2 id="total">$ ' . number_format($totalPrice, 2) . '</h2>';
                        echo '</div>';
                        echo '<button type="submit" name="checkout" id="openButton" class="openButton"  height=40px style="font-size:20px; width:100%;"> <a hreff="checkout.php">Checkout</a></button> <br><br>';
                    } else {
                        echo '<div id="cartItem">Your cart is empty</div>';
                    }
                    ?>
                    <button type="submit" name="checkout" id="openButton" class="openButton"  style="font-size:15px; width:100%;">
    <a href="checkout.php"> checkout now -> </a> 
</button>

                </form>
            </div>
        </div> <center>
            <div class="footer" style="position: fixed;bottom: 0;">
                <h4>This Site Is Created As A DBMS Project</h4>
            </div>
            <script src="addtocart.js"></script>
            <script>
                // JavaScript for opening and closing the popup box
                const openButton = document.getElementById("openButton");
                const popupBox = document.getElementById("popupBox");

                openButton.addEventListener("click", () => {
                    popupBox.style.display = "block";
                });

                popupBox.addEventListener("click", (event) => {
                    if (event.target === popupBox) {
                        popupBox.style.display = "none";
                    }
                });
            </script>
</body>

</html>
