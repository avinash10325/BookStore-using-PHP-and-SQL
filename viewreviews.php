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
$Rating = new Rating($Database->connect());

$User->username = $_COOKIE["username"];
$userID = $User->getUserID();
$User->id = $userID;
$userRole = $User->getUserRole();
$getUserID = null;

if(isset($_GET["view-user-rating"]) && $userRole == "admin") {
    $getUserID = $_GET["user-id"];
}

$Rating->ratedBy = $userID;

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

if(isset($_GET["delete-rating"])) {
    $ratingID = $_GET["rating-id"];

    if(empty($ratingID)) {
        $error = true;
        $error_message = "No rating selected, to delete.";
    }
    else {
        $Rating->id = $ratingID;

        if($Rating->deleteRating()) {
            header('Location: ?updatesuccess');
        }
        else {
            $error = true;
            $error_message = "Failed to delete, rating.";
        }
    }
}

if(isset($_GET["updatesuccess"])) {
    $updateSuccess = true;
    $updateMessage = "Rating deleted successfully.";
}

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
            <?php
            if(!isset($_GET["view-user-rating"]) && $userRole !== "admin" || !isset($_GET["view-user-rating"]) && $userRole == "admin") {
            ?>
            <h2 class="list-title">My Rating & Reviews</h2>
            <div class="list-container">
                <ul class="book-lists">
                    <?php
                    if(!$Rating->ratingRowCount()) {
                    ?>
                    <li class="list no-list">
                        You haven't given any rating & review.
                    </li>
                    <?php
                    }
                    else {
                    $x = $Rating->getRatings();

                    while($row = $x->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);

                        $Book->id = $bookID;
                        $bookGenre = $Book->getBookGenre();
                        $bookRating = $Book->getBookRatings();

                        $xb = $Book->getBookDetails();
                        $bookThumbnail = null;
                        $bookName = null;
                        $bookAuthor = null;
                        $bookPublishedOn = null;
                        
                        while($row2 = $xb->fetch(PDO::FETCH_ASSOC)) {
                            $bookThumbnail = $row2["bookThumbnail"];
                            $bookName = $row2["bookName"];
                            $bookAuthor = $row2["bookAuthor"];
                            $bookPublishedOn = $row2["bookPublishedOn"];
                        }
                    ?>
                    <li id="<?php echo 'book' . $id; ?>" class="list">
                        <div class="left">
                            <img class="thumbnail" src="uploads/thumbnails/<?php echo $bookThumbnail; ?>" alt=""/>
                        </div>
                        <div class="right">
                            <div class="book-title"><?php echo $bookName; ?></div>
                            <div class="book-author">By <?php echo $bookAuthor; ?> <div class="book-genre">Genre: <?php echo $bookGenre; ?></div> <div class="book-published">Published: <?php echo Date("F j, Y", strtotime($bookPublishedOn)); ?></div></div>
                            <div class="book-comment">Comment:</div>
                            <div class="book-comment-text"><?php echo $comment; ?></div>
                            <div class="book-rating"><span style="color:#555555; margin-right: 14px;">Rated: </span> <svg class="icon-star"><use xlink:href="#icon-star"></use></svg> <?php echo $rating; ?></div>
                            <div class="book-actions">
                                <a class="rent" href="?delete-rating&rating-id=<?php echo $id; ?>">Delete</a>
                            </div>
                        </div>
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
            <?php
            }
            else if(isset($_GET["view-user-rating"]) && $userRole == "admin") {
            ?>
            <h2 class="list-title"><?php echo $_GET["name"]; ?>'s rating & reviews.</h2>
            <div class="list-container">
                <ul class="book-lists">
                    <?php
                    $Rating->ratedBy = $getUserID;

                    if(!$Rating->ratingRowCount()) {
                    ?>
                    <li class="list no-list">
                    <?php echo $_GET["name"]; ?> haven't given any rating & review.
                    </li>
                    <?php
                    }
                    else {
                    $x = $Rating->getRatings();

                    while($row = $x->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);

                        $Book->id = $bookID;
                        $bookGenre = $Book->getBookGenre();
                        $bookRating = $Book->getBookRatings();

                        $xb = $Book->getBookDetails();
                        $bookThumbnail = null;
                        $bookName = null;
                        $bookAuthor = null;
                        $bookPublishedOn = null;
                        
                        while($row2 = $xb->fetch(PDO::FETCH_ASSOC)) {
                            $bookThumbnail = $row2["bookThumbnail"];
                            $bookName = $row2["bookName"];
                            $bookAuthor = $row2["bookAuthor"];
                            $bookPublishedOn = $row2["bookPublishedOn"];
                        }
                    ?>
                    <li id="<?php echo 'book' . $id; ?>" class="list">
                        <div class="left">
                            <img class="thumbnail" src="uploads/thumbnails/<?php echo $bookThumbnail; ?>" alt=""/>
                        </div>
                        <div class="right">
                            <div class="book-title"><?php echo $bookName; ?></div>
                            <div class="book-author">By <?php echo $bookAuthor; ?> <div class="book-genre">Genre: <?php echo $bookGenre; ?></div> <div class="book-published">Published: <?php echo Date("F j, Y", strtotime($bookPublishedOn)); ?></div></div>
                            <div class="book-comment">Comment:</div>
                            <div class="book-comment-text"><?php echo $comment; ?></div>
                            <div class="book-rating"><span style="color:#555555; margin-right: 14px;">Rated: </span> <svg class="icon-star"><use xlink:href="#icon-star"></use></svg> <?php echo $rating; ?></div>
                            <div class="book-actions">
                                <a class="rent" href="&delete-rating&rating-id=<?php echo $id; ?>">Delete</a>
                            </div>
                        </div>
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
            <?php
            }
            else {
                header("Location: viewreviews.php");
            }
            ?>
            
        </div>
    </div>
    
    <script src="assets/js/index.js?<?php echo mt_rand(999,999999); ?>"></script>
</body>
</html>