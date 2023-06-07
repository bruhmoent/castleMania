<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CastleMania - Sign Up</title>
    <link rel="stylesheet" href="style.scss">
</head>
<body>

    <div class="container">
    <div class="close">
        <a href="index.php" class="noline"><p> ← Return </p></a>
    </div>
    <section class="login">
    <h1> Sign up! </h1>
    </br><hr></br>
    <form action="signup.php" method="post" enctype="multipart/form-data">
        <label>Username: </br><input type="text" name="username" required></label></br></br>
        <label>Password: </br><input type="password" name="password" required></label></br></br>
        <label>Email: </br><input type="email" name="email"></label></br></br>
        <label>Profile Picture: </br><input type="file" class="pfp" name="profile_picture"></label></br></br>
        <input type="submit" class="submit" value="Sign up!"/>
    </form>
    </section>

    <div class="welcomeCard">
        <h1> Welcome to CastleMania! </h1>
        </br>
        <p> Join the passion of the <b class="attention">medieval</b> ages today! </p>
        </br>
        <ul class="opaque">
            <p><b class="attention">Unlimited</b> sharing to showcase your love for castles,</p>
            <p>Easy access to other <b class="attention">profiles</b>, </p>
            <p> <b class="attention">Friendly</b> community and passionate moderators!</p>
        </ul>
    </div>
</div>
    <?php
    require_once 'database.php';
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $username = filter_input(INPUT_POST, 'username');
        $password = filter_input(INPUT_POST, 'password');
        $email = filter_input(INPUT_POST, 'email');
        $profilePicture = $_FILES['profile_picture']['tmp_name'];

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        if(isset($username) && isset($password)){
        if (!$db) {
            die("Database connection error.");
        }

        $checkUserQuery = $db->prepare('SELECT COUNT(*) FROM users WHERE login = :login');
        $checkUserQuery->bindParam(':login', $username);
        $checkUserQuery->execute();
        $userExists = ($checkUserQuery->fetchColumn() > 0);

        if ($userExists) {
            echo '<p class="error">User already exists.</p>';
        } else {
            $addUserQuery = $db->prepare('INSERT INTO users (login, password, email, profilePicture) VALUES (:login, :password, :email, :profilePicture)');
            $addUserQuery->bindParam(':login', $username);
            $addUserQuery->bindParam(':password', $hashedPassword);
            $addUserQuery->bindParam(':email', $email);
            $pictureData = file_get_contents($profilePicture);
            $addUserQuery->bindParam(':profilePicture', $pictureData, PDO::PARAM_LOB);
            $addUserQuery->execute();

            if ($addUserQuery->execute()) {
                echo '<p class="success">User added successfully.</p>';
                header('Location: index.php');
            } else {
                echo 'Error adding user.';
            }
        }
    }
}

    $db = null;
    ?>
</body>
</html>