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
    <section class="section"><p class="headerShadow"><a id="profileLink" href="#">Profile</a></p></section>

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
    <section class="section-container"> 
    <?php
    require_once 'database.php';
    if (isset($_GET['username']) && !empty($_GET['username'])) {
        $userCheck = "";
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
            $userCheck = $user['login'];
            echo '<section class="content">';

            echo '<style>';
            echo '.content {';
            echo '  position: relative;';
            echo '  z-index: 1;';
            echo '}';
            echo '.content::before {';
            echo '  content: "";';
            echo '  position: absolute;';
            echo '  top: 0;';
            echo '  left: 0;';
            echo '  right: 0;';
            echo '  bottom: 0;';
            echo '  z-index: -1;';
            echo '  background-repeat: no-repeat;';
            echo '  background-size: cover;';
            echo '  background-position: center;';
            echo '  background-blend-mode: lighten;';
            echo '  filter: brightness(0.7) blur(5px);';
            echo '  background-image: url(data:image/png;base64,' . base64_encode($pfp) . ');';
            echo '}';
            echo '</style>';

            echo '<section class="profileContainer">';
            echo '<h1 class="attention"> Profile </h1><hr>';
            if (!empty($pfp)) {
                echo '<img src="data:image/png;base64,' . base64_encode($pfp) . '" alt="Profile Picture"'.' width=64 height=64 style="margin: 0 auto; border-radius:25px; border: 1px groove white;">';
            } else {
                echo "No profile picture available.";
            }
            echo "> Username: " . $user['login'] . "<br>";
            echo "> Email: " . $user['email'] . "<br>";

            $postsQuery = "SELECT * FROM posts WHERE userId = :userId";
            $postsStatement = $db->prepare($postsQuery);
            $postsStatement->bindValue(':userId', $user['id'], PDO::PARAM_INT);
            $postsStatement->execute();

            echo '<div class="button-container">';
            if(isset($_COOKIE["username"]) && $_COOKIE["username"] === $userCheck){
            echo '<div class="plus-button">';
            echo '<a href="createposts.php">'.'<p>+</p>'.'</a>';
            echo '</div>';
            }
            echo '</div>';
            echo '</section>';
            echo '<div class="postContainer">';
            echo '<div class="postList">';
            echo '<h1>Posts:</h1></br>';

            while ($post = $postsStatement->fetch(PDO::FETCH_ASSOC)) {
                echo '<div class="post">';
                echo '<a class="index-inside" href="viewposts.php?username='.$username.'&postID='.$post['id'] .'">'.'<h3>' . $post['title'] . '</h3></a>';
                echo '</div>';
            }

            echo '</div>';
            echo '</div>';
            if(isset($_COOKIE["username"]) && $_COOKIE["username"] === $userCheck){
            echo '<section class="form-container">';
            echo '<form method="POST">';
            echo '<label for="delete_title" style="font-size:28px;">Title of Post to Delete:</label><br><br>';
            echo '<input type="text" style="font-size:28px;" name="delete_title" id="delete_title" required>';
            echo '<button class="delete-button" type="submit">🗑</button>';
            echo '</form>';
            echo '</section>';

            if (isset($_POST['delete_title']) && !empty($_POST['delete_title'])) {
                $title = $_POST['delete_title'];
            
                $deleteQuery = "DELETE FROM posts WHERE title = :title AND userId = :userId";
                $deleteStatement = $db->prepare($deleteQuery);
                $deleteStatement->bindValue(':title', $title, PDO::PARAM_STR);
                $deleteStatement->bindValue(':userId', $user['id'], PDO::PARAM_INT);
                $deleteStatement->execute();

                $rowsAffected = $deleteStatement->rowCount();
                if ($rowsAffected > 0) {
                    echo '<script>window.location.reload();</script>';
                }
            }
            echo '</section>';
            }
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