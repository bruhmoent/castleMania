<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="mainPage.scss">
    <title>CastleMania - Main</title>
</head>
<body>
    <section class="bar">
        <h1 class="headerShadow">CastleMania</h1>
        <?php
        if(isset($_COOKIE["username"])){
        echo '<p class="headerShadow"> Welcome, '.$_COOKIE["username"].'!</p>';
        }
        ?>
    </section>
    <section class="section-container">
    <section class="section"><p class="headerShadow"><a href="index.php">Main</a></p></section>
    <section class="section">
     <p class="headerShadow"><a id="profileLink" href="#">Profile</a></p>
    </section>

    <script>
    var username = document.cookie.replace(/(?:(?:^|.*;\s*)username\s*=\s*([^;]*).*$)|^.*$/, "$1");

    var profileLink = document.getElementById("profileLink");

    profileLink.href = "profile.php?username=" + encodeURIComponent(username);
    </script>
    
    <?php
    if(isset($_COOKIE["username"])){
    echo '<section class="section"><p class="headerShadow"><a href="signout.php">Sign Out</a></p></section>';
    }
    else{
    echo '<section class="section"><p class="headerShadow"><a href="signin.php">Sign In</a></p></section>';
    echo '<section class="section"><p class="headerShadow"><a href="signup.php">Sign Up</a></p></section>';
    }
    ?>
    </section>
    <section class="content"></section>
    <section class="footer"></section>
</body>
</html>