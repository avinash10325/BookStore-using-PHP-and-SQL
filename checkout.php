<?php
require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'config.php';

$loggedIn = null;

if(!isset($_COOKIE["username"])) {
    header("Location: login.php");
    exit;
}

$loggedIn = true;
$error = false;
$error_message = null;

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

$bookID = $_GET["book-id"];
$Book->id = $bookID;
$singleBuyBook = $Book->getBookDetails();

$User->id = $userID;
$userRole = $User->getUserRole();

$getStockCount = $Book->getBookStock();

if(isset($_GET["checkout-single"])) {
    $getUserID = $_GET["user-id"];
    $getBookID = $_GET["book-id"];
    $getAddressID = $_GET["address-id"];

    if($getAddressID == 0) {
        $error = true;
        $error_message = "Please add a shipping address first, then continue.";
    }
    else {
        $Purchase->bookPurchasedBy = $getUserID;
        $Purchase->bookID = $getBookID;
        $Purchase->addressID = $getAddressID;
        $Purchase->bookPurchasedOn = Date("Y-m-d H:i:s");

        if(!$Purchase->addToPurchase()) {
            $error = true;
            $error_message = "Failed to purchase.";
        }
        else {
            $Book->updateBookStock($getStockCount - 1);

            header('Location: purchased-books.php');
            exit;
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
    <title>Checkout | My Book Store</title>
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
                    <li class="menu"><a href="dashboard.php">Dashboard</a></li>
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
            <h2 class="list-title">Buy Book</h2>
            <div class="list-container">
                <ul class="book-lists">
                    <?php
                        while($row = $singleBuyBook->fetch(PDO::FETCH_ASSOC)) {
                            extract($row);

                            $Book->id = $id;
                            $bookGenre = $Book->getBookGenre();
                            $bookRating = $Book->getBookRatings();
                    ?>
                    <li class="list">
                        <div class="left">
                            <img class="thumbnail" src="uploads/thumbnails/<?php echo $bookThumbnail; ?>" alt=""/>
                        </div>
                        <div class="right">
                            <div class="book-title"><?php echo $bookName; ?></div>
                            <div class="book-author">By <?php echo $bookAuthor; ?> <div class="book-genre">Genre: <?php echo $bookGenre; ?></div> <div class="book-published">Published: <?php echo Date("F j, Y", strtotime($bookPublishedOn)); ?></div></div>
                        </div>
                    </li>
                    <?php
                        }
                    ?>
                </ul>
                <!-- <div class="more-call">
                    <a href="#more">Browse More New Books</a>
                </div> -->
            </div>
        </div>
        <div class="list-section">
            <h2 class="list-title">Shipping Address</h2>
            <div class="list-container">
                <ul class="book-lists">
                    <?php
                        if(!$addressExist) {
                    ?>
                    <li class="list no-list">You have not saved any address yet.</li>
                    <?php
                        }
                        else {
                            $addressID = 1;
                    ?>
                    <li class="list"><?php echo $address; ?></li>
                    <?php
                        }
                    ?>
                </ul>
                <!-- <div class="more-call">
                    <a href="#more">Browse More New Books</a>
                </div> -->
            </div>
        </div>
        <div class="list-section">
            <div class="checkout-buttons">
                <a class="button" href="index.php">Cancel</a>
                <a href="?checkout-single&book-id=<?php echo $bookID; ?>&user-id=<?php echo $userID; ?>&address-id=<?php echo $addressID; ?>" class="button confirm-checkout">Confirm Checkout</a>
            </div>
        </div>
    </div>
    
    <script src="assets/js/index.js?<?php echo mt_rand(999,999999); ?>"></script>
</body>
</html>