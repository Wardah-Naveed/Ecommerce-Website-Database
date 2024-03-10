<?php
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
    header('Location: index.php'); // Change this line to redirect to index.php
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch order history with products
$sql_order_history = "
    SELECT oc.order_id, oc.date_time, oc.no_of_products, oc.price_total,
           p.product_id, p.product_name, p.image1, eo.quantity
    FROM order_cust oc
    LEFT JOIN each_order eo ON oc.order_id = eo.order_id
    LEFT JOIN product p ON eo.product_id = p.product_id
    WHERE oc.cust_id = $user_id AND oc.completed = 1
    ORDER BY oc.date_time DESC";

$result_order_history = $conn->query($sql_order_history);

if ($result_order_history->num_rows > 0) {
    $rows = $result_order_history->fetch_all(MYSQLI_ASSOC);

    // Transform the result into a structured array
    $order_history = [];
    foreach ($rows as $row) {
        $order_id = $row['order_id'];
        if (!isset($order_history[$order_id])) {
            $order_history[$order_id] = [
                'order_id' => $order_id,
                'date_time' => $row['date_time'],
                'no_of_products' => $row['no_of_products'],
                'price_total' => $row['price_total'],
                'products' => [],
            ];
        }

        // Add product details to the order
        if (!empty($row['product_id'])) {
            $order_history[$order_id]['products'][] = [
                'product_id' => $row['product_id'],
                'product_name' => $row['product_name'],
                'image1' => $row['image1'],
                'quantity' => $row['quantity'],
            ];
        }
    }
} else {
    $order_history = [];
}
// The rest of your code remains unchanged
$sql_select_user = "SELECT * FROM customer WHERE cust_id = $user_id";
$result_user = $conn->query($sql_select_user);

if ($result_user->num_rows > 0) {
    $user_data = $result_user->fetch_assoc();
} else {
    echo "User not found";
    exit();
}

// Fetch wishlist items
$sql_wishlist = "SELECT p.product_id, p.product_name, p.product_description, p.price, p.image1
                FROM wishlist w
                JOIN product p ON w.product_id = p.product_id
                WHERE w.cust_id = $user_id";

$result_wishlist = $conn->query($sql_wishlist);

if ($result_wishlist->num_rows > 0) {
    $wishlist_items = $result_wishlist->fetch_all(MYSQLI_ASSOC);
} else {
    $wishlist_items = [];
}

$sql_pending_orders = "
    SELECT oc.order_id, oc.date_time, oc.no_of_products, oc.price_total,
           p.product_id, p.product_name, p.image1, eo.quantity
    FROM order_cust oc
    LEFT JOIN each_order eo ON oc.order_id = eo.order_id
    LEFT JOIN product p ON eo.product_id = p.product_id
    WHERE oc.cust_id = $user_id AND oc.completed = 0
    ORDER BY oc.date_time DESC";

$result_pending_orders = $conn->query($sql_pending_orders);

if ($result_pending_orders->num_rows > 0) {
    $pending_orders = $result_pending_orders->fetch_all(MYSQLI_ASSOC);

    // Transform the result into a structured array
    $pending_order_history = [];
    foreach ($pending_orders as $row) {
        $order_id = $row['order_id'];
        if (!isset($pending_order_history[$order_id])) {
            $pending_order_history[$order_id] = [
                'order_id' => $order_id,
                'date_time' => $row['date_time'],
                'no_of_products' => $row['no_of_products'],
                'price_total' => $row['price_total'],
                'products' => [],
            ];
        }

        // Add product details to the order
        if (!empty($row['product_id'])) {
            $pending_order_history[$order_id]['products'][] = [
                'product_id' => $row['product_id'],
                'product_name' => $row['product_name'],
                'image1' => $row['image1'],
                'quantity' => $row['quantity'],
            ];
        }
    }
} else {
    $pending_order_history = [];
}

$conn->close();
?>
<!-- The rest of your HTML code remains unchanged -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
    <link rel="stylesheet" href="styles.css" />
    <link rel="stylesheet" href="profile.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <style>
    #toggleButton {
        display: none;
        font-size: 20px;
        border: 1px solid #000;
        margin-top: 3px;
        background-color: red;
        color: white;
        border-radius: 2px;
        margin-right: 5%; /* Adjust this value as needed */
    }

    #icon {
        font-size: 40px;
        cursor: pointer;
        margin-right: 5%; /* Adjust this value as needed */
    }
    .horizontal-products {
    display: flex;
    overflow-x: auto;
    gap: 10px; /* Adjust as needed */
}

.product {
    flex: 0 0 auto;
    text-align: center;
}

</style>
</head>
<body>
<div class="head">
        <div class="header">
            <div class="navbar grid">
                <div class="logo"><img src="imgs/logo.png" alt="" class="logoimg"></div>
                <div class="search">
                    <a href="searchpage.php">
                        <form class="searchbar" action="">
                            <input type="text" placeholder="Search It Buy It..." name="search">
                            <button type="submit" class="background"><i class="fa fa-search"></i></button>
                        </form>
                    </a>
                </div>
                <div class="loginbtn">
                    <?php
                        echo '<a href="profile.php" class="button right"><i class="fa fa-user-circle"></i></a>';
                    ?>
                </div>
                <div class="cartbtn"><a href="cartpage.php" class="button left"><i class="fa fa-shopping-cart"></i></a></div>
            </div>
        </div>
    </div>

    <div id="nav" class="background">
        <ul>
            <li><div><a href="index.php" class="active">Home</a></div></li>
            <li><div><a href="searchpage.php" class="">Clothing</a></div></li>
            <li><div><a href="searchpage.php" class="">Tech</a></div></li>
            <li><div><a href="searchpage.php" class="">Accessories</a></div></li>
            <li><div><a href="contact.php" class="">Contact Us</a></div></li>
        </ul>
    </div>
    <br><br>
    <!-- Icon that triggers the button -->
    <div id="icon" onclick="toggleButton()">🔴</div>
    <!-- Button that appears on clicking the icon -->
    <a href ="logout.php"><button id="toggleButton" onclick="handleButtonClick()"> LOG-OUT</button></a>

    <script>
        function toggleButton() {
            var button = document.getElementById('toggleButton');
            button.style.display = (button.style.display === 'none' || button.style.display === '') ? 'block' : 'none';
        }

        function handleButtonClick() {
            // Add your button click functionality here
            alert('LOGOUT!');
        }
    </script>
    <div class="profile-section">
        <div class="left">
            <div class="profile-picture">
                <?php
                if (!empty($user_data['profile_picture'])) {
                    $profilePicture = base64_encode($user_data['profile_picture']);
                    echo '<img src="data:image/jpeg;base64,' . $profilePicture . '" alt="Profile Picture">';
                } else {
                    echo '<img src="https://rb.gy/e1u21r" alt="Profile Picture">';
                }
                ?>
                <p>Profile Picture</p>
            </div>
            </div>
            <div class="right">
            <div class="innerleft">
                <div class="container">
                    <h1>My Account</h1>
                    <div class="editable-field">
                        <label>Customer ID:</label>
                        <span class="non-editable"><?php echo $user_data['cust_id']; ?></span>
                    </div>
                    <div class="editable-field">
                        <label>Password:</label>
                        <span class="editable-value" id="password"><?php echo str_repeat('*', strlen($user_data['PASSWORD'])); ?></span> &nbsp;
                        <button class="show-hide-button" id="show-hide-password">Show</button>
                    </div>
                    <div class="editable-field" id="first-name-field">
                        <label>First Name:</label>
                        <span class="editable-value" id="first-name"><?= $user_data['first_name']; ?></span> &nbsp;
                    </div>
                
    <!-- ... (Repeat the structure for other editable fields) ... -->
    <!-- Repeat the structure for other editable fields -->
                <div class="editable-field" id="last-name-field">
                  <label>Last Name:</label>
                  <span class="editable-value" id="last-name"><?= $user_data['last_name']; ?></span> &nbsp;
                </div>
                <div class="editable-field" id="email-field">
                   <label>Email:</label>
                   <span class="editable-value" id="email"><?= $user_data['email']; ?></span> &nbsp;
                </div>
                <div class="editable-field" id="number-field">
                   <label>Number:</label>
                   <span class="editable-value" id="number"><?= $user_data['NUMBER']; ?></span> &nbsp;
                 </div>

<div class="editable-field" id="address-field">
    <label>Address:</label>
    <span class="editable-value" id="address"><?= $user_data['address']; ?></span> &nbsp;
</div>
    <a href="update_profile.php"><button style="color: blue; background-color: lightblue;">EDIT</button></a>
    </div>
    </div>
    <div class="innerright">
    <div class="handy">
        <div class="order">
            <h1>Order History</h1>
            <?php foreach ($order_history as $order): ?>
                <div class="related-item">
                    <h2 class="related-item-title">Order ID: <?= $order['order_id'] ?></h2>
                    <p class="date-of-purchase">Date of Purchase: <?= $order['date_time'] ?></p>
                    <p class="total-paid">Total Paid: $<?= $order['price_total'] ?></p>
                    <p class="total-items-bought">Items Bought: <?= $order['no_of_products'] ?></p>

                                 <!-- Display products for each order -->
<h3>Products:</h3>
                    <div class="horizontal-products">
                        <?php foreach ($order['products'] as $product): ?>
                            <div class="product">
                                <a href="product.php?product_id=<?= $product['product_id'] ?>">
                                    <img src="<?= 'data:image/jpeg;base64,' . base64_encode($product['image1']) ?>" alt="<?= $product['product_name'] ?>">
                                </a>
                                <p class="product-name">
                                    <a href="product.php?product_id=<?= $product['product_id'] ?>">
                                        <?= $product['product_name'] ?>
                                    </a>
                                </p>
                                <p class="quantity">Quantity: <?= $product['quantity'] ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- You can add more details or buttons as needed -->
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
            </div>
        </div>
    </div>
    <div class="main">
    <h1>&nbsp;&nbsp;&nbsp;🛒🌠 WISHLIST:</h1>
    <!-- Wishlist Scrollable Container -->
    <div class="related-item-container">
        <?php foreach ($wishlist_items as $item): ?>
            <div class="related-item">
                <?php
                // Convert blob to image
                $imageData = base64_encode($item['image1']);
                $imageSrc = 'data:image/jpeg;base64,' . $imageData;
                ?>
                <center>
                    <!-- Make the product name a link -->
                    <a href="product.php?product_id=<?= $item['product_id'] ?>">
                        <img src="<?= $imageSrc ?>" alt="<?= $item['product_name'] ?>">
                    </a>
                </center>
                <h2 class="related-item-title">
                    <!-- Make the product name a link -->
                    <a href="product.php?product_id=<?= $item['product_id'] ?>">
                        <?= $item['product_name'] ?>
                    </a>
                </h2>
                <p class="related-item-description"><?= $item['product_description'] ?></p>
                <div class="product-price">$<?= $item['price'] ?></div>
                <!-- You can add more details or buttons as needed -->
            </div>
        <?php endforeach; ?>
    </div>
    </div>
<br><br>
<div class="main">
    <div class="related-item-container">
        <h1>Pending Orders</h1>
        <?php foreach ($pending_order_history as $order): ?>
            <div class="related-item">
                <h2 class="related-item-title">Order ID: <?= $order['order_id'] ?></h2>
                <p class="date-of-purchase">Date of Purchase: <?= $order['date_time'] ?></p>
                <p class="total-paid">Total Paid: $<?= $order['price_total'] ?></p>
                <p class="total-items-bought">Items Bought: <?= $order['no_of_products'] ?></p>
                <h3>Products:</h3>
                    <div class="horizontal-products">
                        <?php foreach ($order['products'] as $product): ?>
                            <div class="product">
                                <a href="product.php?product_id=<?= $product['product_id'] ?>">
                                    <img src="<?= 'data:image/jpeg;base64,' . base64_encode($product['image1']) ?>" alt="<?= $product['product_name'] ?>">
                                </a>
                                <p class="product-name">
                                    <a href="product.php?product_id=<?= $product['product_id'] ?>">
                                        <?= $product['product_name'] ?>
                                    </a>
                                </p>
                                <p class="quantity">Quantity: <?= $product['quantity'] ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>

                <!-- You can add more details or buttons as needed -->
            </div>
        <?php endforeach; ?>
    </div>
</div>

    <div class="footer">
        <h4>This Site Is Created As A DBMS Project</h4>
    </div>

    <script>
        const showHidePasswordButton = document.getElementById('show-hide-password');
        const passwordField = document.getElementById('password');
        const originalPassword = '<?php echo $user_data['PASSWORD']; ?>';

        showHidePasswordButton.addEventListener('click', () => {
            if (passwordField.textContent === repeat('*', originalPassword.length)) {
                passwordField.textContent = originalPassword;
                showHidePasswordButton.textContent = 'Hide';
            } else {
                passwordField.textContent = repeat('*', originalPassword.length);
                showHidePasswordButton.textContent = 'Show';
            }
        });

        function repeat(char, times) {
            return new Array(times + 1).join(char);
        }
    </script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        const editButtons = document.querySelectorAll('.edit-button');
const doneButtons = document.querySelectorAll('.done-button');
const editableFields = document.querySelectorAll('.editable-field');
    </script>
    </script>
</body>
</html>


