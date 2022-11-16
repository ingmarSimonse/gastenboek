<?php
session_start();
if (!isset($_SESSION["page"])) {
    $_SESSION["page"] = 0;
}


// Create connection
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET["delete"])) {
    $delete = intval($_GET["delete"]);
    $conn->query("DELETE FROM `guestbook` WHERE `ID` = $delete");
}

$output = "";
$returnArr = array();
$result = $conn->query("SELECT * FROM `guestbook` ORDER BY `id` ASC");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_array()) {
        array_push($returnArr, $row);
    }
}

$pageTrue = $_SESSION["page"] < 1;
if (isset($_GET["left"])) {
    if (!$pageTrue) {
        $_SESSION["page"]--;
    }
}
if (isset($_GET["right"])) {
    $_SESSION["page"]++;
}
if (isset($_GET["beginning"])) {
    if (!$pageTrue) {
        $_SESSION["page"] = 0;
    }
}
if (isset($_GET["end"])) {
    $_SESSION["page"] = floor(count($returnArr) / 10);
}

$page = $_SESSION["page"] * 10;
$output .= sprintf("<p class='page_number'>%s</p><br><br>", sprintf("<b>%s</b>", $_SESSION["page"] + 1));
for ($i = $page; $i < ($page + 10) && $i < count($returnArr); $i++) {
    if (!$_SESSION["loggedIn"]) {
        $output .= sprintf("<div>%s</div>", sprintf("<p><b>%s</b></p>", $returnArr[$i]["name"]) .
            sprintf("<p>%s</p>", $returnArr[$i]["message"]) .
            sprintf("<p>%s</p><br><br>", $returnArr[$i]["date"]));
    } else {
        $output .= sprintf("<div>%s</div>", sprintf("<p class='head_form'><b>%s</b></p>
            <a href='./?delete=" . $returnArr[$i]["ID"] . "' class='delete'>verwijder</a>", $returnArr[$i]["name"]) .
            sprintf("<p>%s</p>", $returnArr[$i]["message"]) .
            sprintf("<p>%s</p><br><br>", $returnArr[$i]["date"]));
    }
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
    <link rel="stylesheet" href="../styles/styles.css">
    <link rel="stylesheet" href="../styles/responsive.css">
    <title>Gastenboek</title>
</head>
<body>
<main class="messages">
    <div id="pager1" class="pager">
        <a href="../">
            <div class="back">
                <i class="fas fa-long-arrow-alt-left"></i>
            </div>
        </a>
        <a href="./?left=true">
            <div class="arrow">
                <i class="fas fa-arrow-alt-circle-left"></i>
            </div>
        </a>
        <a href="./?beginning=true">
            <div class="fast_arrow">
                <i class="fas fa-backward"></i>
            </div>
        </a>
    </div>
    <div class="paper" id="paper1">
        <div class="pattern">
            <div class="content">
                <?=$output?>
            </div>
        </div>
    </div>
    <div id="pager2" class="pager">
        <div class="back"></div>
        <a href="./?right=true">
            <div class="arrow">
                <i class="fas fa-arrow-alt-circle-right"></i>
            </div>
        </a>
        <a href="./?end=true">
            <div class="fast_arrow">
                <i class="fas fa-forward"></i>
            </div>
        </a>
    </div>
</main>
</body>
</html>
