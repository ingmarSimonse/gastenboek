<?php
session_start();
if (isset($_SESSION["destroy"])) {
    if ($_SESSION["destroy"]) {
        session_destroy();
    }
}
if (!isset($_SESSION["firstMessage"])) {
    $_SESSION["firstMessage"] = true;
    $_SESSION["destroy"] = false;
    $_SESSION["timestamp"] = time() + 3600;
}



// Create connection
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$alertOutput = "";
if (isset($_POST["submit"])) {
    if (abs($_SESSION["timestamp"] - time()) > 7200) {
        $_SESSION["firstMessage"] = true;
    }
    if ($_SESSION["firstMessage"]) {
        $name = strval($_POST["name"]);
        $message= strval($_POST["message"]);
        $name = $conn -> real_escape_string($name);
        $message = $conn -> real_escape_string($message);
        $name = htmlspecialchars($name);
        $message = htmlspecialchars($message);
        $date = date("d M | H:i | Y");
        $conn->query("INSERT INTO `guestbook` (`ID`, `name`, `message`, `date`) VALUES (NULL, '$name', '$message', '$date')");
        $_SESSION["firstMessage"] = false;
        $_SESSION["timestamp"] = time();
    } else {
        $alertOutput = "<span>Je kan maar een bericht versturen.</span>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html;" charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css?family=Dancing+Script" rel="stylesheet">
    <script src="https://kit.fontawesome.com/fab5bc8fbc.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../styles/styles.css">
    <link rel="stylesheet" href="../styles/responsive.css">
    <title>Gastenboek</title>
</head>
<body>
<main class="new_message">
    <div class="pager">
        <a href="../">
            <div class="back">
                <i class="fas fa-long-arrow-alt-left"></i>
            </div>
        </a>
    </div>
    <form action="" method="post" class="paper">
        <div class="pattern">
            <div class="content">
                Naam: <input type="text" name="name" maxlength="20" required><br>
                <textarea rows="5" cols="80" maxlength="9999" name="message" placeholder="Bericht....." required></textarea><br>
            </div>
        </div>
        <input type="submit" name="submit" value="Verstuur">
        <?=$alertOutput?>
    </form>
    <div class="pager"></div>
</main>
</body>
</html>
