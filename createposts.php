<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CastleMania - Create Post</title>
    <link rel="stylesheet" href="style.scss">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

    <div class="container">
    <div class="close">
        <a href="index.php" class="noline"><p> ← Return </p></a>
    </div>
    <section class="login">
    <h1> Create a post! </h1>
    </br><hr></br>
    <form action="createposts.php" method="post" enctype="multipart/form-data">
        <label>Post Title: <br><input type="text" name="postname" required></label><br><br>
        <label>Description: <br><textarea name="description" required maxlength="90"></textarea></label><br><br>
        <label>Rating:</label><br>
        <div class="rating">
            <input type="hidden" name="rating" id="rating-value" value="">
            <span class="star" data-value="1"><i class="fas fa-star"></i></span>
            <span class="star" data-value="2"><i class="fas fa-star"></i></span>
            <span class="star" data-value="3"><i class="fas fa-star"></i></span>
            <span class="star" data-value="4"><i class="fas fa-star"></i></span>
            <span class="star" data-value="5"><i class="fas fa-star"></i></span>
        </div>
        <br><br>
        <label>Castle Photo: <br><input type="file" class="pfp" name="castle_photo"></label><br><br>
        <label>Select Spot on Map:</label><br>
        <div id="map" style="width: 100%; height: 400px;"></div><br>
        <label>Attitude: <br><input type="text" name="attitude" id="attitude" required></label><br><br>
        <input type="submit" class="submit" value="Post!"/>
    </form>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $('.star').on('click', function() {
            var value = $(this).data('value');
            $('#rating-value').val(value);
            $('.star').removeClass('active hover');
            $(this).addClass('active');
            $(this).prevAll('.star').addClass('active');
        });

        $('.star').on('mouseover', function() {
            var value = $(this).data('value');
            $('.star').removeClass('hover');
            $(this).prevAll('.star').addClass('hover');
            $(this).addClass('hover');
        });

        $('.star').on('mouseout', function() {
            $('.star').removeClass('hover');
        });
    });
    </script>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([0, 0], 2);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
        }).addTo(map);

        var marker;
        map.on('click', function(e) {
            if (marker) {
                map.removeLayer(marker);
            }
            marker = L.marker(e.latlng).addTo(map);
            document.getElementById('attitude').value = e.latlng.lat + ', ' + e.latlng.lng;
        });
    </script>
    </section>

    <div class="welcomeCard">
        <h1><i> Share your experience! </i></h1>
        </br>
        <p> Browse or post <b class="attention">beatiful</b> castles, </p>
        </br>
        <ul class="opaque">
            <p><b class="attention">Free</b> and easy sharing,</p>
            <p>Attach the castle's <b class="attention">attitude</b> to let others know where it's located.</p>
        </ul>
    </div>
</div>
    <?php
    require_once 'database.php';
    if(isset($_COOKIE["user_id"])){
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $postname = filter_input(INPUT_POST, 'postname');
            $description = filter_input(INPUT_POST, 'description');
            $castle_photo = $_FILES['castle_photo']['tmp_name'];
            $attitudeValue = $_POST["attitude"];
        
            if (isset($postname) && isset($description)) {
                if (!$db) {
                    die("Database connection error.");
                }
                $addPost = $db->prepare('INSERT INTO posts (userId, opinion, title, starRating, attachment, attitude) VALUES (:userId, :opinion, :title, :starRating, :attachment, :attitude)');
                $addPost->bindParam(':userId', $_COOKIE["user_id"]);
                $addPost->bindParam(':opinion', $description);
                $addPost->bindParam(':title', $postname);
                $addPost->bindParam(':starRating', $_POST['rating']);
                $pictureData = file_get_contents($castle_photo);
                $addPost->bindParam(':attachment', $pictureData, PDO::PARAM_LOB);
                $addPost->bindParam(':attitude', $attitudeValue);
                $addPost->execute();
        
                if ($addPost->rowCount() > 0) {
                    echo '<p class="success">Post created successfully!</p>';
                    header('Location: index.php');
                    exit();
                } else {
                    echo 'Error creating post.';
                }
            }
        }
}else{
    echo '<p class="error">Access denied. User not logged in.</p>';
}

    $db = null;
    ?>
</body>
</html>