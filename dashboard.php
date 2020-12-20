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
$Genre = new Genre($Database->connect());

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

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="assets/css/reset.css?<?php echo mt_rand(999,99999); ?>"/>
    <link rel="stylesheet" href="assets/css/style.css?<?php echo mt_rand(999,99999); ?>"/>
    <title>Dashboard | My Book Store</title>
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
        <?php
            if($updateSuccess) {
        ?>
        <div class="message">
            <?php echo $updateMessage; ?>
        </div>
        <?php
            }
        ?>
        <div style="display: -webkit-flex; display: flex; -webkit-justify-content: space-between; -moz-justify-content: space-between; justify-content: space-between; -webkit-align-items: center; -moz-align-items: center; align-items: center;">
            <div class="list-section">
                <h2 class="list-title">Total Users</h2>
                <div class="list-container">
                    <div class="book-lists" style="font-size: 18px;">
                        <?php echo $User->totalUsers() - 1; ?>
                    </div>
                    <!-- <div class="more-call">
                        <a href="#more">Browse More New Books</a>
                    </div> -->
                </div>
            </div>
            <div class="list-section">
                <h2 class="list-title">Total Books</h2>
                <div class="list-container">
                    <div class="book-lists" style="font-size: 18px;">
                        <?php echo $Book->totalBooks(); ?>
                    </div>
                    <!-- <div class="more-call">
                        <a href="#more">Browse More New Books</a>
                    </div> -->
                </div>
            </div>
            <div class="list-section">
                <h2 class="list-title">Total Book's Genre</h2>
                <div class="list-container">
                    <div class="book-lists" style="font-size: 18px;">
                        <?php echo $Genre->totalGenre(); ?>
                    </div>
                    <!-- <div class="more-call">
                        <a href="#more">Browse More New Books</a>
                    </div> -->
                </div>
            </div>
            <div class="list-section">
                <h2 class="list-title">Total Books Selled</h2>
                <div class="list-container">
                    <div class="book-lists" style="font-size: 18px;">
                        <?php echo $Purchase->totalPurchase(); ?>
                    </div>
                    <!-- <div class="more-call">
                        <a href="#more">Browse More New Books</a>
                    </div> -->
                </div>
            </div>
            <div class="list-section">
                <h2 class="list-title">Total Book's Stocks</h2>
                <div class="list-container">
                    <div class="book-lists" style="font-size: 18px;">
                        <?php echo $Book->totalBookStocks(); ?>
                    </div>
                    <!-- <div class="more-call">
                        <a href="#more">Browse More New Books</a>
                    </div> -->
                </div>
            </div>
        </div>
        <div class="list-section">
            <h2 class="list-title">Manage</h2>
            <div class="list-container">
                <div class="book-lists" style="font-size: 18px;">
                    <div class="list"><a href="manage-users.php">Users</a></div>
                    <div class="list"><a href="manage-genre.php">Book's Categories/Genres</a></div>
                    <div class="list"><a href="manage-books.php">Books</a></div>
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