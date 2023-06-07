<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="mainPage.scss">
    <title>CastleMania - Profile</title>
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
    <section class="section"><p class="headerShadow"><a href="profile.php">Profile</a></p></section>
    <?php
    if(isset($_COOKIE["username"])){
    echo '<section class="section"><p class="headerShadow"><a href="signout.php">Sign Out</a></p></section>';
    }
    else{
    echo '<section class="section"><p class="headerShadow"><a href="signin.php">Sign In</a></p></section>';
    echo '<section class="section"><p class="headerShadow"><a href="signup.php">Sign Up</a></p></section>';
    }
    ?>

    <?php
    require_once 'database.php';
    if (isset($_GET['username']) && !empty($_GET['username'])) {
        if (!$db) {
            die("Database connection error.");
        }
        $username = $_GET['username'];

        $query = "SELECT * FROM users WHERE login = :login";
        $statement = $db->prepare($query);
        $statement->bindValue(':login', $username, PDO::PARAM_STR);
        $statement->execute();

        if ($statement->rowCount() >= 0) {
            $user = $statement->fetch(PDO::FETCH_ASSOC);
            $pfp = $user['profilePicture'];
            echo '<section class="content">';
            echo '<section class="profileContainer">';
            echo '<h1 class="attention"> Profile </h1><hr>';
            if (!empty($pfp)) {
                echo '<img src="data:image/png;base64,' . base64_encode($pfp) . '" alt="Profile Picture"'.' width=64 style="margin: 0 auto;">';
            } else {
                echo "No profile picture available.";
            }
            echo "> Username: " . $user['login'] . "<br>";
            echo "> Email: " . $user['email'] . "<br>";
            echo '</section>';
            echo '</section>';
        } else {
            echo '<section class="content">';
            echo "User not found";
            echo '</section>';
        }
    } else {
        echo '<section class="content">';
        echo '<section class="profileContainer">';
        echo "No username provided / User isn't logged in.";
        echo '</section>';
        echo '</section>';
    }
    ?>

    </section>
    <section class="footer"></section>
</body>
</html>