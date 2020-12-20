<?php
require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'config.php';

$loggedIn = false;
$username = null;
$userID = null;
$userRole = null;

$Database = null;
$User = null;

$Database = new Database();
$Book = new Book($Database->connect());
$newBooks = $Book->getNewBooksList();
$Rating = new Rating($Database->connect());

$Genre = new Genre($Database->connect());
$newGenre = $Genre->getGenreLists();

// If user already logged in...
if(isset($_COOKIE["username"])) {
    $loggedIn = true;

    $User = new User($Database->connect());
    $User->username = $_COOKIE["username"];
}

$error = false;
$error_message = null;
$success = false;
$success_message = null;

$rowCount = 0;

if(isset($_GET["genre-id"])) {
    $getGenreID = $_GET["genre-id"];

    $Genre->id = $getGenreID;
    $Book->booksGenreID = $getGenreID;

    if(!$Genre->isGenreIDExist()) {
        header("Location: index.php");
        exit;
    }

    
    $rowCount = $Genre->genreRowCountByID();
    $bookGenre = $Genre->getGenreName();
    $bookDesc = $Genre->getGenreDesc();

    $bookCount = $Book->bookCountByGenre();
}
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
                    <li class="menu active"><a href="?">Home</a></li>
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
            <h2 class="list-title"><?php echo $bookGenre; ?> Genre Books</h2>
            <div style="margin-top: 8px;"><?php echo $bookDesc; ?></div>
            <div class="list-container">
                <ul class="book-lists">
                    <?php
                        if(!$rowCount || !$bookCount > 0) {
                    ?>
                    <li class="list no-list">No books added yet.</li>
                    <?php
                        }
                        else {
                            while($row = $newBooks->fetch(PDO::FETCH_ASSOC)) {
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
                                echo "No ratings yet";
                            } else {
                                echo $bookRating;
                                echo '<a href="reviews.php?book-id='.$id.'" style="display: inline-block; margin-left: 5px;">Reviews ('.$Rating->getRatingsCount($id).')</a>';
                            } ?></div>
                            <div class="book-actions">
                            ₹<?php echo $bookPrice; ?> <a style="display: inline-block; margin-left: 14px;" class="buy" href="checkout.php?buy-single&book-id=<?php echo $id; ?>">Buy</a>
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
        </div>
    </div>
    
    <script src="assets/js/index.js?<?php echo mt_rand(999,999999); ?>"></script>
</body>
</html>