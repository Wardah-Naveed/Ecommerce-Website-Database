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
                    <a href="searchpage.php">
                        <form class="searchbar" action="">
                            <input type="text" placeholder="Search It Buy It..." name="search">
                            <button type="submit" class="background"><i class="fa fa-search"></i></button>
                        </form>
                    </a>
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
