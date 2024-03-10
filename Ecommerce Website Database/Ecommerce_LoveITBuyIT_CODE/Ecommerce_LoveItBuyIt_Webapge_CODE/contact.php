<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css" />
    <link rel="stylesheet" href="product.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Love It Buy It</title>
    <style>
      body {
      font-family: Arial, sans-serif;
      text-align: center;
     }

      header {
       background-color: orange;
       color: #fff;
       padding: 20px;
     }

     .contact-info {
       margin: 20px;
     }

     .social-media {
       margin: 20px; 
     }

     .social-media h2 {
      margin-bottom: 10px;
     }

     .social-media img {
       margin: 5px;
       height: 50px ;
       width: 50px;
     }

    </style>
</head>

<body>
<?php include 'header.php';?>
    <header>
        <h1>Contact Us</h1>
    </header>
    <div class="contact-info">
        <p>Email: contact@example.com</p>
        <p>Phone: +1 (123) 456-7890</p>
    </div>
    <!-- ... (previous HTML code) -->
    <div class="social-media">
        <h2>Connect with us on social media:</h2>
        <div id="social-media-icons"></div>
     </div>
     <div class="footer" style="position: fixed; bottom: 0;">
        <h4>This Site Is Created As A DBMS Project</h4>
    </div>
    <script>

     const socialMediaIcons = [
    { name: "Facebook", url: "https://www.facebook.com/", image: "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAOEAAADhCAMAAAAJbSJIAAAAe1BMVEUYd/L///8Ab/EhevIAbvEAbPEAcfIAafEAa/Eje/IQdfKowvnG1/sAc/Lr8f290frX4/y/0vp8p/aCq/ayyvlqnfX4+v7O3fs5g/OjwPhjmfV3pPbc5vxPjvScu/ji6/2Ps/eHrvdelvQ+hvPw9P5KjPSux/lwoPWevPirAiLtAAAFyElEQVR4nO3d63KyOhQG4JCShKZG8YCCWG3V2t7/FW6wtWoVvygu10p23l8dZ6Q8E8gBk8CixnQ7xeCV0c9yOO8tmhns/MfjUcYTLo3GPn2LaCO5ENPR+ArhZKm4E7iDaK6mE0thPxeO6X5iRN63EKau+upokaf/EpbKXV8drcqLwtRI7FNsHanTZmGhsE/vLlFFk7AU2Od2p4jyvHDKsc/sbuHTc8Kp+7fgPnJ5Kiz9KcE6vPwrLHy5B3cRxbEw9aMWPYxKj4QG+3wAog+FpU+1zC6y3As9vEbrfF+nW2Hudl+0KXq1E/Z9q0d3EZ0foadFWBVi/i2c+FqEVSFOtsKlr0VYjfqzWjj2syL9jhpXwpFfHdLj8FElzHzsz+xSXaYs4v7ehlXiiHUT7JMAjViwjs+3YXUj9ljhY6d7HzlnA58rmqqqGbJXryuaqjLFPgPo6Bz7DMDj9yUaEhISEhISQi1a+9n70FryWCQq4UZLoVQiqsScS+PDkEcbnujp22iy2M/XWncXs0ln9PJWLp8rriPzzs7HCFn2utGlrNPe/HMlnHwIoeN4cHYS2rm8uPcUwqiPM/PPGuPcA12tlrMrfO4J49XJ3DqvhEZsrvRFUc8lIV+trwY6JVTv1/ucEqrRLUB3hFpZt4DH6bsivBXojPBmoCvC5PpWwi0hH9wMdEO4ndjitVBdHia5L5TzFkAnhA3Lx/wRil4rIf0+jX5uBXRAGLcrQgeE7e5CB4Tyy3ehumHQ65TQLP9tcFvIbxv2OiRUDUusvRHqj7ZA6sJbuqTrdfcga+JP9eOrhvaz4jMXKtn+xvYb2sBrbsPxnAnnNjpgzL5P+qaIF9b5mE9LX/fJSZ99RTNLnLs6f8LtxhVddxcmCbuq9NnVEqyEVo+g5rTb9ItJbBqLsctLPpRNEX45XIRM2Aifsc+yRbS0AC5cvki1zTOakaNt/TZWwqHLQvZkIVw6PUnPRuj2UnIboctVaRD6ILQZAPsvfMI+yVbxX2hzHwYh7QRhENJPEAYh/QRhENJPEP4/hP6PD+kLDW+OzfNSdvItWeX3b2xevU/RS2M2NhOiRpvT721+PiuKAn8LnbhjoWgR/IsYWoi/rSOwkMBP/MDCSYwNhBZu8CtTYOG790ICv70BCwn0WoGF+I0FsHCB31gACzv4jQWwsCAwmwhWSGF7VVjhB4FpDLBCArchrHBNYUYYqDAl0FjACknsvgMqfMPvdwMLpwSqUlgh/kMaBiwk0O+GFVLod8MKaWynACn8olCVggpLAv1uWOGKQmMBKiRR0UAKibwhBlBI4Hl3HUAhgefdda5brX1VCDzv3oZv92+O6x9s4zg+WGZefWT1K/fBN6SUxhgpq8Mo5cLSZ6vthY4WkOpt0E74+vgxU+FSgjAI6ScIg5B+gjAI6ScI6xCYbdEi/gvDPO8gpJ8gDEL6CcIgpJ8gDEL6CcIgpJ8gDEL6CcIgpJ8gDEL6CcIgpJ8gDEL6CcIgpB984RN7BZ3MiS7USwa7WBpdaN5ZATrVGF0oC9YBXTyFLuQdBrtqA12YdFnEIasabKGWEYsyyKoGW2g+KyHognBsIR9VwjHkWltsoRpXQtDLFFlosqgWTgBX+SEL69e+1ZtTAr7oBXduos6jb2EfrhBxhaL/IwQsRFThtgi/hSlYdYoqVOmvMCqhut+YQjmI9sII6jLFFOroUDgDuk4RhWp2JIxeYIYYeMJkEx0LoyHIynA0YTzcHX6/HXUG0QPHEvLs9/AHG25nAKWIJIz3wENhNLz/vYgjTIYHhz/aNH1z9xoVRag2h4c/3hZ+xu7c9CMIpZ4dHf7vxvcDddfG/+FCrQZ/Dn+ytf9sJe5ofLBQizz9e/gzLy/o5OJuo/6HCo3I+6eHP/t6hkmmuLlLST5sjK8NV9nZXVYaXkAx7mVScNma+QChNkbyRH6OGl5yfuEVG4ve13DZ8t/DC3WevRf9Cy+p/w9dXVYUCaRvFwAAAABJRU5ErkJggg==" },
    { name: "Twitter", url: "https://twitter.com/", image: "https://img.freepik.com/free-vector/new-2023-twitter-logo-x-icon-design_1017-45418.jpg?size=338&ext=jpg&ga=GA1.1.1880011253.1699142400&semt=ais" },
    { name: "LinkedIn", url: "https://www.linkedin.com/", image: "https://upload.wikimedia.org/wikipedia/commons/thumb/c/ca/LinkedIn_logo_initials.png/480px-LinkedIn_logo_initials.png" },
    { name: "Instagram", url: "https://www.instagram.com/", image: "https://upload.wikimedia.org/wikipedia/commons/thumb/e/e7/Instagram_logo_2016.svg/2048px-Instagram_logo_2016.svg.png" },
    // Add more social media icons and URLs as needed
     ];

    const socialMediaContainer = document.getElementById("social-media-icons");

      socialMediaIcons.forEach((icon) => {
      const link = document.createElement("a");
      link.href = icon.url;
      const img = document.createElement("img");
      img.src = icon.image;
      img.alt = icon.name;
      link.appendChild(img);
      socialMediaContainer.appendChild(link);
     });


    </script>
</body>
</html>