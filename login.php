<?php
require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'config.php';

// If user already logged in...
if(isset($_COOKIE["username"])) {
    header('location: index.php');
}

// Placeholders
$error = false;
$error_message = null;
$success = false;
$success_message = null;
$username = null;
$password = null;

$Database = new Database();
$User = new User($Database->connect());

if(isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if(empty($username) && empty($password)) {
        $error = true;
        $error_message = "Please enter your username and password.";
    }
    else if(empty($username)) {
        $error = true;
        $error_message = "Please enter your username.";
    }
    else if(empty($password)) {
        $error = true;
        $error_message = "Please enter your password.";
    }
    else {
        $User->username = $username;
        $User->password = $password;

        if(!$User->isAccountExist()) {
            $error = true;
            $error_message = "Invalid username and password.";
        }
        else {
            // Logged in successfully...
            // Create cookie
            setcookie('username', $username, [
                "expires" => time() + strtotime(Date("Y-m-d H:i:s", strtotime("+1 Year"))),
                "path" => "/"
            ]);

            $success = true;
            $success_message = "You have logged in successfully, please wait...";

            // Redirect
            header('Refresh: 3; URL=index.php');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="assets/css/reset.css?<?php echo mt_rand(999,99999); ?>"/>
    <link rel="stylesheet" href="assets/css/style.css?<?php echo mt_rand(999,99999); ?>"/>
    <title>Login to My Book Store</title>
</head>
<body>
    <?php include('icons.php'); ?>
    <header id="header" class="header">
        <div class="inner-wrapper">
            <div class="side"><img src="assets/images/MyBookStore.png" alt="Logo" class="logo" /></div>
            <div class="side">
                <ul class="header-menu">
                    <li class="menu"><a class="color-tomato" href="register.php">Create an Account</a></li>
                </ul>
            </div>
        </div>
    </header>
    <div id="scroll-body" class="body">
        <?php
            if($success) {
        ?>
        <div class="message">
            <?php echo $success_message; ?>
        </div>
        <?php
            }
        ?>

        <?php
            if(!$success) {
        ?>
        <div class="form-container">
            <form method="post" class="form">
                <h2>Login</h2>
                <?php
                    if($error) {
                ?>
                <div class="error-message">
                    <?php echo $error_message; ?>
                </div>
                <?php
                    }
                ?>
                <input name="username" type="text" class="text" placeholder="Username" value="<?php echo $username; ?>" autofocus>
                <input name="password" type="password" class="text" placeholder="Password" value="<?php echo $password; ?>">
                <button name="login" class="submit-button">Login</button>
            </form>
        </div>
        <?php
            }
        ?>
    </div>
</body>
</html>