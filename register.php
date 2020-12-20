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
$fullName = null;
$username = null;
$password = null;
$conf_password = null;

$Database = new Database();
$User = new User($Database->connect());

if(isset($_POST["register"])) {
    $fullName = $_POST["full_name"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $conf_password = $_POST["conf_password"];

    if(empty($fullName) && empty($username) && empty($password) && empty($conf_password)) {
        $error = true;
        $error_message = "Please enter your full name, username and password.";
    }
    else if(empty($fullName)) {
        $error = true;
        $error_message = "Please enter your full name.";
    }
    else if(empty($username)) {
        $error = true;
        $error_message = "Please enter your username.";
    }
    else if(empty($password)) {
        $error = true;
        $error_message = "Please enter your password.";
    }
    else if(empty($conf_password)) {
        $error = true;
        $error_message = "Please re-enter your password (confirm password).";
    }
    else if($password !== $conf_password) {
        $error = true;
        $error_message = "Your entered password dosen't matched, please re-enter and try again.";
    }
    else {
        $User->fullName = $fullName;
        $User->username = $username;
        $User->password = $password;
        $User->userRole = 'user';
        $User->accountCreatedAt = Date("Y-m-d H:i:s");

        if($User->isUsernameExist()) {
            $error = true;
            $error_message = "Account already exist with username <span". ' style="font-weight: bold;"' . ">{$username}</span>.";
        }
        else {
            if($User->create_account()) {
                // Account created successfully...
                // Create cookie
                setcookie('username', $username, [
                    "expires" => time() + strtotime(Date("Y-m-d H:i:s", strtotime("+1 Year"))),
                    "path" => "/"
                ]);
    
                $success = true;
                $success_message = "Your account has been created successfully, please wait...";

                // Redirect
                header('Refresh: 3; URL=index.php');
            }
            else {
                $error = true;
                $error_message = "Failed to create an account.";
            }
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
    <title>Create an Account on My Book Store</title>
</head>
<body>
    <?php include('icons.php'); ?>
    <header id="header" class="header">
        <div class="inner-wrapper">
            <div class="side"><img src="assets/images/MyBookStore.png" alt="Logo" class="logo" /></div>
            <div class="side">
                <ul class="header-menu">
                    <li class="menu"><a href="login.php">Login</a></li>
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
                <h2>Create an Account</h2>
                <?php
                    if($error) {
                ?>
                <div class="error-message">
                    <?php echo $error_message; ?>
                </div>
                <?php
                    }
                ?>
                <input name="full_name" type="text" class="text" placeholder="Full Name" value="<?php echo $fullName; ?>" autofocus>
                <input name="username" type="text" class="text" placeholder="Username" value="<?php echo $username; ?>" autofocus>
                <input name="password" type="password" class="text" placeholder="Password" value="<?php echo $password; ?>">
                <input name="conf_password" type="password" class="text" placeholder="Confirm Password" value="<?php echo $conf_password; ?>">
                <button name="register" class="submit-button">Create</button>
            </form>
        </div>
        <?php
            }
        ?>
    </div>
</body>
</html>