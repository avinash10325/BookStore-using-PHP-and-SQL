<?php
require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'config.php';

$loggedIn = null;

if(!isset($_COOKIE["username"])) {
    header("Location: index.php");
    exit;
}

$loggedIn = true;
$error = false;
$error_message = null;
$success = null;
$success_message = null;

$singleBuyBook = null;

$Database = new Database();
$User = new User($Database->connect());
$Book = new Book($Database->connect());
$Address = new Address($Database->connect());
$Purchase = new Purchase($Database->connect());

$User->username = $_COOKIE["username"];
$userID = $User->getUserID();

$addressID = 0;
$Address->userID = $userID;
$address = $Address->getAddress();
$addressExist = $Address->isAddressExist();


$namechangerefresh = false;

$updateSuccess = false;
$updateMessage = null;

// User details
$username_ = null;
$fullName_ = null;
$userRole_ = null;
$address_ = null;
$User->id = $userID;
$getUserDetails = $User->getUserDetails();

$userRole = $User->getUserRole();

if($userRole !== "admin") {
    header("Location: index.php");
    exit;
}

$allUsers = $User->allUsers();

if(isset($_GET["delete-user"])) {
    $getUserID = $_GET["user-id"];

    $User->id = $getUserID;

    if(!$User->deleteUser()) {
        $error = true;
        $error_message = "Failed to delete.";
    }
    else {
        header("Location: manage-users.php?updatesuccess");
        exit;
    }
}

if(isset($_GET["updatesuccess"])) {
    $updateSuccess = true;
    $updateMessage = "User deleted successfully.";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="assets/css/reset.css?<?php echo mt_rand(999,99999); ?>"/>
    <link rel="stylesheet" href="assets/css/style.css?<?php echo mt_rand(999,99999); ?>"/>
    <title>Manage Users | My Book Store</title>
</head>
<body>
    <?php include('icons.php'); ?>
    <?php
        if($success) {
    ?>
    <div id="float-message-success" class="float-message">
        <div class="message-float-success">
            <div><?php echo $success_message; ?></div>
            <div id="float-message-success-close"><svg class="icon-x"><use xlink:href="#icon-x"></use></svg></div>
        </div>
    </div>
    <?php
        }
        else {
    ?>
    <div id="float-message-success" class="hidden">
        <div>
            <div id="float-message-success-close"></div>
        </div>
    </div>
    <?php
        }
    ?>
    <?php
        if($error) {
    ?>
    <div id="float-message-error" class="float-message">
        <div class="message-float-error">
            <div><?php echo $error_message; ?></div>
            <div id="float-message-error-close"><svg class="icon-x"><use xlink:href="#icon-x"></use></svg></div>
        </div>
    </div>
    <?php
        }
        else {
    ?>
    <div id="float-message-error" class="hidden">
        <div>
            <div id="float-message-error-close""></div>
        </div>
    </div>
    <?php
        }
    ?>
    <header id="header" class="header">
        <div class="inner-wrapper">
            <div class="side"><img src="assets/images/MyBookStore.png" alt="Logo" class="logo" /></div>
            <div class="side">
                <ul class="header-menu">
                    <li class="menu"><a href="index.php">Home</a></li>
                    <?php
                    if($loggedIn && $userRole == "admin") {
                    ?>
                    <li class="menu active"><a href="dashboard.php">Dashboard</a></li>
                    <?php
                    }
                    ?>
                    <?php
                        if($loggedIn) {
                    ?>
                    <li class="menu"><a href="myaccount.php">My Account</a></li>
                    <li class="menu"><a href="purchased-books.php">Purchased Books</a></li>
                    <li class="menu"><a class="color-tomato" href="logout.php">Logout</a></li>
                    <?php
                        }
                        else {
                    ?>
                    <li class="menu"><a href="login.php">Login</a></li>
                    <li class="menu"><a class="color-tomato" href="register.php">Create an Account</a></li>
                    <?php
                        }
                    ?>
                </ul>
            </div>
        </div>
    </header>
    <div id="scroll-body" class="body">
        <div class="list-section">
            <h2 class="list-title">Manage Users</h2>
        </div>
        <?php
            if($updateSuccess) {
        ?>
        <div class="message">
            <?php echo $updateMessage; ?>
        </div>
        <?php
            }
        ?>
        <div class="list-section">
            <h2 class="list-title">All Users</h2>
            <div class="list-container">
                <div class="book-lists" style="font-size: 18px;">
                <?php
                if(!($User->totalUsers() - 1) > 0) {
                ?>
                <div class="list no-list">No users.</div>
                <?php
                }
                else {
                ?>
                <div class="list" style="color: #555555;"><div>Name, Username</div><div>Actions</div></div>
                <?php
                while($row = $allUsers->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                ?>
                <div class="list"><div><?php echo $fullName; ?> <span style="margin-left: 14px;">@<?php echo $username; ?></span></div>
                <div><a class="color-tomato" href="?delete-user&user-id=<?php echo $id; ?>">Delete</a></div></div>
                <?php
                }
                ?>
                <?php
                }
                ?>
                </div>
                <!-- <div class="more-call">
                    <a href="#more">Browse More New Books</a>
                </div> -->
            </div>
        </div>
    </div>
    
    <script src="assets/js/index.js?<?php echo mt_rand(999,999999); ?>"></script>
</body>
</html>