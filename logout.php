<?php
// Clear cookies
setcookie('username', '', [
    "expires" => -1,
    "path" => '/'
]);

header('Refresh: 3; URL=index.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="assets/css/reset.css?<?php echo mt_rand(999,99999); ?>"/>
    <link rel="stylesheet" href="assets/css/style.css?<?php echo mt_rand(999,99999); ?>"/>
    <title>My Book Store</title>
</head>
<body>
    <?php include('icons.php'); ?>
    <header id="header" class="header">
        <div class="inner-wrapper">
            <div class="side"><img src="assets/images/MyBookStore.png" alt="Logo" class="logo" /></div>
        </div>
    </header>
    <div id="scroll-body" class="body">
        <div class="message">
            Logging out, please wait...
        </div>
    </div>
</body>
</html>