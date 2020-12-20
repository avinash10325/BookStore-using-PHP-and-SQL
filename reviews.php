<?php
require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'config.php';

$loggedIn = null;

if(isset($_COOKIE["username"])) {
    $loggedIn = true;
}

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
$Rating = new Rating($Database->connect());

$User->username = $_COOKIE["username"];
$userID = $User->getUserID();
$User->id = $userID;
$userRole = $User->getUserRole();
$getUserID = null;

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
$address_ = null;
$User->id = $userID;
$getUserDetails = $User->getUserDetails();

$userRole = $User->getUserRole();

$getBookID = null;

if(isset($_GET["book-id"])) {
    $getBookID = $_GET["book-id"];
}

$Book->id = $getBookID;
$bookDetails = $Book->getBookDetails();

$Rating->bookID = $getBookID;
$readRating = $Rating->getRatingsAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="assets/css/reset.css?<?php echo mt_rand(999,99999); ?>"/>
    <link rel="stylesheet" href="assets/css/style.css?<?php echo mt_rand(999,99999); ?>"/>
    <title>Reviews | My Book Store</title>
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
            <h2 class="list-title">Book Reviews of</h2>
            <div class="list-container">
                <ul class="book-lists">
                <?php
                    while($row = $bookDetails->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);

                        $Book->id = $id;
                        $bookGenre = $Book->getBookGenre();
                        $bookRating = $Book->getBookRatings();
                    ?>
                    <li id="<?php echo 'book' . $id; ?>" class="list">
                        <div class="left">
                            <img class="thumbnail" src="uploads/thumbnails/<?php echo $bookThumbnail; ?>" alt=""/>
                        </div>
                        <div class="right">
                            <div class="book-title"><?php echo $bookName; ?></div>
                            <div class="book-author">By <?php echo $bookAuthor; ?> <div class="book-genre">Genre: <?php echo $bookGenre; ?></div> <div class="book-published">Published: <?php echo Date("F j, Y", strtotime($bookPublishedOn)); ?></div></div>
                            <div class="book-rating"><svg class="icon-star"><use xlink:href="#icon-star"></use></svg> <?php if(empty($bookRating)) {
                                echo "No Ratings.";
                            } else {
                                echo $bookRating;
                            } ?></div>
                            <div class="book-actions">
                            ₹<?php echo $bookPrice; ?> <a style="display: inline-block; margin-left: 14px;" class="buy" href="checkout.php?buy-single&book-id=<?php echo $id; ?>">Buy</a>
                            </div>
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
            <h2 class="list-title">Reviews</h2>
            <div class="list-container">
                <ul class="book-lists">
                    <?php
                    if(!$Rating->ratingRowCountAll()) {
                    ?>
                    <li class="list no-lis">No rating & reviews for the book.</li>
                    <?php

                    }
                    else {
                        while($row2 = $readRating->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <li class="list">
                        <div><?php echo $row2["comment"]; ?></div>
                        <div style="-webkit-display: flex; display: flex; -webkit-align-items: center; -moz-align-items: center; align-items: center;"><svg class="icon-star"><use xlink:href="#icon-star"></use></svg> <span style="margin-left:4px;"><?php echo $row2["rating"]; ?></span> <span style="margin-left:14px;">By: <?php echo $User->getNameByID($row2["ratedBy"]); ?></span></div>
                    </li>
                    <?php
                        }
                    }
                    ?>
                </ul>
                <!-- <div class="more-call">
                    <a href="#more">Browse More New Books</a>
                </div> -->
            </div>
        </div>
    </div>
    
    <script src="assets/js/index.js?<?php echo mt_rand(999,999999); ?>"></script>
</body>
</html>