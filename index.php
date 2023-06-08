<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="mainPage.scss">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
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
    <section class="content">
    <?php
    require_once 'database.php';
    if (!$db) {
        die("Database connection error.");
    }

    $query = "SELECT p.*, u.login AS username FROM posts p INNER JOIN users u ON p.userId = u.id ORDER BY RANDOM() LIMIT 3";
    $getPost = $db->prepare($query);
    $getPost->execute();

    if ($getPost->rowCount() >= 0) {
        $counter = 1;

        while ($post = $getPost->fetch(PDO::FETCH_ASSOC)) {
            $description = $post['opinion'];
            $castle_photo = $post['attachment'];
            $title = $post['title'];
            $username = $post['username'];
            $attitude = $post['attitude'];
            $starRating = $post['starRating'];

            echo '<section class="profileContainer post-' . $counter . '">';
            echo '<style>';
            echo '.post-' . $counter . ' {';
            echo '  position: relative;';
            echo '  z-index: 1;';
            echo '}';
            echo '.post-' . $counter . '::before {';
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
            echo '  filter: brightness(0.6) blur(5px);';
            echo '  background-image: url(data:image/png;base64,' . base64_encode($castle_photo) . ');';
            echo '}';
            echo '</style>';

            echo '<h1 class="attention">' . $title . '</h1><hr>';

            $maxCharactersPerChunk = 30;
            $chunks = str_split($description, $maxCharactersPerChunk);
            echo '</br><label style="font-size:24px">Description: </label>';
            echo '<div class="description-container">';
            foreach ($chunks as $chunk) {
                echo '<p class="description">' . $chunk . '</p>';
            }
            echo '</div>';
            echo '<div class="rating">';
            echo '<input type="hidden" name="rating" id="rating-value" value="">';
            
            for ($i = 1; $i <= $starRating; $i++) {
                echo '<span class="star" data-value="' . $i . '">';
                echo '<i class="fas fa-star';
                echo '"></i></span>';
            }
            
            echo '</div>';
            echo '<p> Attitude: ' . $attitude . '</p>';
            echo '<a class="index-lower" href="profile.php?username='.$username.'">'.'<p> Author: ' . $username . '</p></a>';
            echo '</section>';

            $counter++;
        }
    } else {
        echo '<section class="content">';
        echo "Post not found";
        echo '</section>';
    }
    ?>
    </section>
    <section class="footer"></section>
</body>
</html>