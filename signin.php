<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CastleMania - Sign In</title>
    <link rel="stylesheet" href="style.scss">
</head>
<body>
    <div class="container">
    <div class="close">
        <a href="index.php" class="noline"><p> ← Return </p></a>
    </div>
    <section class="login">
    <h1> Sign in! </h1>
    </br><hr></br>
    <form action="signin.php" method="post" enctype="multipart/form-data">
        <label>Username: </br><input type="text" name="username" required></label></br></br>
        <label>Password: </br><input type="password" name="password" required></label></br></br>
        <a href="signup.php"><p class="hint"> Don't have an account? Sign up!</p></a></br>
        <input type="submit" class="submit" value="Sign in!"/>
    </form>
    </section>

    <div class="welcomeCard">
        <h1> Welcome back to CastleMania! </h1>
    </div>
</div>
<section class="message">
            <?php
            require_once 'database.php';
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $username = filter_input(INPUT_POST, 'username');
                $password = filter_input(INPUT_POST, 'password');
                
                if (!empty($username) && !empty($password)) {
                if (!$db) {
                    die("Database connection error.");
                }

                $getUserQuery = $db->prepare('SELECT password,id FROM users WHERE login = :login');
                $getUserQuery->bindParam(':login', $username);
                $getUserQuery->execute();
                $userData = $getUserQuery->fetch();

                if ($userData && password_verify($password, $userData['password'])) {
                    echo '<p class="success">User successfully logged in!</p>';
                    setcookie("username", $username, time() + 3600 * 365, "/");
                    setcookie("user_id", $userData['id'], time() + 3600 * 365, "/");
                    header('Location: index.php');
                } else {
                    echo '<p class="error">Incorrect details.</p>';
                }
            }

            $db = null;
        }
            ?>
</section>
</body>
</html>