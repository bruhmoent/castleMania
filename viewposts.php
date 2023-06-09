<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="mainPage.scss">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <title>CastleMania - Post View</title>
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
    </section>
    <section class="section-container"> 
    <?php
    require_once 'database.php';
    if (isset($_GET['postID']) && !empty($_GET['postID'])) {
        if (!$db) {
            die("Database connection error.");
        }
        $postID = $_GET['postID'];

        $query = "SELECT p.*, u.login AS username FROM posts p INNER JOIN users u ON p.userId = u.id WHERE p.id = :id";
        $statement = $db->prepare($query);
        $statement->bindValue(':id', $postID);
        $statement->execute();

        if ($statement->rowCount() >= 0) {
            $post = $statement->fetch(PDO::FETCH_ASSOC);
            $castle_image = $post['attachment'];
            $username = $post['username'];
            $description = $post['opinion'];
            $attitude = $post['attitude'];
            $starRating = $post['starRating'];
            echo '<section class="content">';

            echo '<div class="image-container" style="background-image: url(data:image/png;base64,' . base64_encode($castle_image) . ');">';
            echo '</div>';     

            echo '<div class="close">';
            echo '<a href="' . $_SERVER['HTTP_REFERER'] . '" class="noline"><p> ← Return </p></a>';
            echo '</div>'; 

            echo '<section class="view-container">';
            echo '<h1 class="attention">'. $post['title'] . '</h1><br>';
            if (!empty($castle_image)) {
                echo '<img src="data:image/png;base64,' . base64_encode($castle_image) . '" alt="Post" width="128" style="margin: 0 auto; border-radius: 6.5px;"><br>';
            } else {
                echo "No profile picture available.";
            }
            echo '<div class="rating">';
            echo '<input type="hidden" name="rating" id="rating-value" value="">';
            
            for ($i = 1; $i <= $starRating; $i++) {
                echo '<span class="star" data-value="' . $i . '">';
                echo '<i class="fas fa-star"></i></span>';
            }
            
            echo '</div><br>';
            echo '<p> Attitude: ' . $attitude . '</p>';

            $maxCharactersPerChunk = 30;
            $chunks = str_split($description, $maxCharactersPerChunk);
            echo '</br><label style="font-size:24px">Description: </label>';
            echo '<div class="description-container">';
            foreach ($chunks as $chunk) {
                echo '<p class="description">' . $chunk . '</p>';
            }
            echo '</div><br><br>';

            echo '<a class="index-inside" href="profile.php?username='.$username.'">'.'<p> Author: ' . $username . '</p></a>';  
            echo '</section>';

            echo '<div class="image-container" style="background-image: url(data:image/png;base64,' . base64_encode($castle_image) . ')">';
            echo '</div>';
            
            echo '</section>';
        } else {
            echo '<section class="content">';
            echo "Post not found";
            echo '</section>';
        }
    } else {
        echo '<section class="content">';
        echo '<section class="profileContainer">';
        echo "No post provided / User isn't logged in.";
        echo '</section>';
        echo '</section>';
    }
    ?>

    </section>
    <section class="footer"></section>
</body>
</html>