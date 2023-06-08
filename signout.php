<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logging out...</title>
</head>
<body>
<?php
if (isset($_COOKIE['username'])) {
    unset($_COOKIE['username']);
    setcookie('username', null, -1, '/');
}
if (isset($_COOKIE['user_id'])) {
    unset($_COOKIE['user_id']); 
    setcookie('user_id', null, -1, '/');
}
header('Location: index.php');
exit;
?>
</body>
</html>