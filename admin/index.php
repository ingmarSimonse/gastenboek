<?php
session_start();
$output = "";
$error  = "";
if (!isset($_SESSION["password"])) {
    $_SESSION["password"] = password_hash("admin", PASSWORD_DEFAULT);
    $_SESSION["loggedIn"] = false;
}
// change password
if (isset($_POST["submitPass"])) {
    if (password_verify($_POST["oldPass"], $_SESSION["password"])) {
        $_SESSION["password"] = password_hash($_POST["newPass"], PASSWORD_DEFAULT);
        $_SESSION["loggedIn"] = false;
    } else {
        $_POST["changePass"] = true;
        $error = "wrong password";
    }
}
// log in
if (isset($_POST["submit"])) {
    if (password_verify($_POST["password"], $_SESSION["password"])) {
        $_SESSION["loggedIn"] = true;
    }
}
// log out
if (isset($_POST["logOut"])) {
    $_SESSION["loggedIn"] = false;
}
// moderate berichten
if (isset($_POST["moderate"])) {
    header("Location: ../berichten/");
    die();
}
// change password load
if (isset($_POST["changePass"])) {
    $output = "<form action='' method='post'>oude wachtwoord: <input type='password' name='oldPass'> nieuwe wachtwoord: <input type='password' name='newPass'><input type='submit' name='submitPass' value='verander'></form>";
}
// not logged in
elseif (!$_SESSION["loggedIn"]) {
    $output = "<form action='' method='post'><input type='password' name='password'> <input type='submit' name='submit' value='inloggen'></form>";
}
// logged in
else {
    $output = "<form action='' method='post'><input type='submit' name='logOut' value='uitloggen'> 
                <input type='submit' name='moderate' value='moderatie'> 
                <input type='submit' name='changePass' value='verander Wachtwoord'></form>";
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html;" charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://kit.fontawesome.com/fab5bc8fbc.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css?family=Dancing+Script" rel="stylesheet">
    <title>Admin</title>
</head>
<body>
<main>
    <?=$error?>
    <?=$output?>
</main>
</body>
</html>
