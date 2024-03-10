<?php 
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
      }
      if (!isset($_SESSION['admin'])) {
        echo '<script type="text/JavaScript">  
                alert("Please Login First");
                window.location.href = "login.php";
              </script>';
        //header('Location: login.php');
        exit();
      }
?>