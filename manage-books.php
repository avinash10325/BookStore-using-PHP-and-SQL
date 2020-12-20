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

$getGenre = null;
$getGenreDesc = null;

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

$newBooks = $Book->getNewBooksList();

$genreLists = $Genre->getGenreLists();

$getBookID = null;

$getBookName = null;
$getBookAuhtor = null;
$getBookGenreID = null;
$getBookPublishedOn = null;
$getBookStockCount = null;
$getBookPrice = null;
$getBookDescription = null;

if(isset($_GET["add-new-book"])) {

    if(isset($_POST["add-book"])) {
        $getBookName = $_POST["bookName"];
        $getBookAuhtor = $_POST["bookAuthor"];
        $getBookGenreID = $_POST["bookGenreID"];
        $getBookPublishedOn = $_POST["bookPublishedOn"];
        $getBookStockCount = $_POST["bookStockCount"];
        $getBookPrice = $_POST["bookPrice"];
        $getBookDescription = $_POST["bookDescription"];

        if(empty($getBookName) || empty($getBookAuhtor) || empty($getBookGenreID) || empty($getBookPublishedOn) || empty($getBookStockCount) || empty($getBookPrice) || empty($getBookDescription)) {
            $error = true;
            $error_message = "You must fill up all the fields to add new book.";
        }
        else {
            $Book->bookName = $getBookName;
            $Book->bookAuthor = $getBookAuhtor;
            $Book->booksGenreID = $getBookGenreID;
            $Book->bookPublishedOn = $getBookPublishedOn;
            $Book->bookStockCount = $getBookStockCount;
            $Book->bookPrice = $getBookPrice;
            $Book->bookDescription = $getBookDescription;
            $Book->bookAddedAt = Date("Y-m-d H:i:s");
            $Book->bookAddedBy = $userID;

            if(!$Book->addBook()) {
                $error = true;
                $error_message = "Failed to add new book.";
            }
            else {
                header("Location: ?addsuccess");
                exit;
            }
        }
    }

}

if(isset($_GET["addsuccess"])) {
    $updateSuccess = true;
    $updateMessage = "New book added successfully.";
}

if(isset($_GET["edit-book"])) {
    $getBookID = $_GET["book-id"];

    $detail = $Book->getBookDetailAtOnce($getBookID);
    $Book->id = $detail["id"];
    $bookGenre_ = $Book->getBookGenre();

    if(isset($_POST["edit-book"])) {
        $getBookName = $_POST["bookName"];
        $getBookAuhtor = $_POST["bookAuthor"];
        $getBookGenreID = $_POST["bookGenreID"];
        $getBookPublishedOn = $_POST["bookPublishedOn"];
        $getBookStockCount = $_POST["bookStockCount"];
        $getBookPrice = $_POST["bookPrice"];
        $getBookDescription = $_POST["bookDescription"];

        if(empty($getBookName) || empty($getBookAuhtor) || empty($getBookGenreID) || empty($getBookPublishedOn) || empty($getBookStockCount) || empty($getBookPrice) || empty($getBookDescription)) {
            $error = true;
            $error_message = "You must fill up all the fields to add new book.";
        }
        else {
            $Book->id = $getBookID;
            $Book->bookName = $getBookName;
            $Book->bookAuthor = $getBookAuhtor;
            $Book->booksGenreID = $getBookGenreID;
            $Book->bookPublishedOn = $getBookPublishedOn;
            $Book->bookStockCount = $getBookStockCount;
            $Book->bookPrice = $getBookPrice;
            $Book->bookDescription = $getBookDescription;

            if(!$Book->updateBook()) {
                $error = true;
                $error_message = "Failed to edit new book.";
            }
            else {
                header("Location: ?editsuccess");
                exit;
            }
        }
    }

}

if(isset($_GET["editsuccess"])) {
    $updateSuccess = true;
    $updateMessage = "Book edited successfully.";
}

if(isset($_GET["delete-book"])) {
    $getBookID = $_GET["book-id"];

    $Book->id = $getBookID;
    if(!$Book->deleteBook()) {
        $error = true;
        $error_message = "Failed to delete.";
    }
    else {
        header("Location: ?deletesuccess");
        exit;
    }
}

if(isset($_GET["deletesuccess"])) {
    $updateSuccess = true;
    $updateMessage = "Book deleted successfully.";
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
        <?php
            if($updateSuccess) {
        ?>
        <div class="message">
            <?php echo $updateMessage; ?>
        </div>
        <?php
            }
        ?>
        <?php
        if(isset($_GET["add-new-book"])) {
        ?>
        <div class="list-section">
            <h2 class="list-title">Add New Book</h2>
            <form class="form" method="post">
                <div class="section">
                    <div class="inner">
                        <input name="bookName" type="text" class="text" placeholder="Book Name" autofocus>
                        <input name="bookAuthor" type="text" class="text" placeholder="Book Author">
                        <select name="bookGenreID" type="text" class="text">
                            <option selected value="">Select Genre</option>
                            <?php
                            while($row = $genreLists->fetch(PDO::FETCH_ASSOC)) {
                                extract($row);
                            ?>
                            <option value="<?php echo $id; ?>"><?php echo $genreName; ?></option>
                            <?php
                            }
                            ?>    
                        </select>
                    </div>
                </div>
                <div class="section">
                    <div class="inner">
                        <span style="margin-right: 14px; width: 35%;">Book Published On</span> <input name="bookPublishedOn" type="date" class="text">
                        <input name="bookStockCount" type="text" class="text" placeholder="Book's Stock Count">
                        <input name="bookPrice" type="text" class="text" placeholder="Book Price">
                    </div>
                </div>
                <div class="section">
                    <div class="inner">
                        <textarea name="bookDescription" type="text" class="text" placeholder="Book Description"></textarea>
                    </div>
                </div>
                <div class="section">
                    <div class="inner">
                        <a style="display: inline-block; width: 100%; text-align: center;" href="manage-books.php">Cancel</a>
                        <button name="add-book" class="submit-button">Add Book</button>
                    </div>
                       
                </div>
            </form>
            
        </div>
        <?php
        }
        else if(isset($_GET["edit-book"])) {
        ?>
        <div class="list-section">
            <h2 class="list-title">Current Book Details</h2>
            <div class="list-container">
                <div class="genre-lists">
                    <div class="list"><span style="color: #555;">Book Name:</span> <?php echo $detail["bookName"]; ?></div>
                    <div class="list"><span style="color: #555;">Book Author:</span> <?php echo $detail["bookAuthor"]; ?></div>
                    <div class="list"><span style="color: #555;">Book Genre:</span> <?php echo $bookGenre_; ?></div>
                    <div class="list"><span style="color: #555;">Book Publised On:</span> <?php echo $detail["bookPublishedOn"]; ?></div>
                    <div class="list"><span style="color: #555;">Book Stock Count:</span> <?php echo $detail["bookStockCount"]; ?></div>
                    <div class="list"><span style="color: #555;">Book Price:</span> ₹<?php echo $detail["bookPrice"]; ?></div>
                    <div class="list"><span style="color: #555;">Book Description:</span> <?php echo $detail["bookDescription"]; ?></div>
                </div>
            </div>
            
        </div>
        <div class="list-section">
            <h2 class="list-title">Edit Book</h2>
            <form class="form" method="post">
                <div class="section">
                    <div class="inner">
                        <input name="bookName" type="text" class="text" placeholder="Book Name" autofocus>
                        <input name="bookAuthor" type="text" class="text" placeholder="Book Author">
                        <select name="bookGenreID" type="text" class="text">
                            <option selected value="">Select Genre</option>
                            <?php
                            while($row = $genreLists->fetch(PDO::FETCH_ASSOC)) {
                                extract($row);
                            ?>
                            <option value="<?php echo $id; ?>"><?php echo $genreName; ?></option>
                            <?php
                            }
                            ?>    
                        </select>
                    </div>
                </div>
                <div class="section">
                    <div class="inner">
                        <span style="margin-right: 14px; width: 35%;">Book Published On</span> <input name="bookPublishedOn" type="date" class="text">
                        <input name="bookStockCount" type="text" class="text" placeholder="Book's Stock Count">
                        <input name="bookPrice" type="text" class="text" placeholder="Book Price">
                    </div>
                </div>
                <div class="section">
                    <div class="inner">
                        <textarea name="bookDescription" type="text" class="text" placeholder="Book Description"></textarea>
                    </div>
                </div>
                <div class="section">
                    <div class="inner">
                        <a style="display: inline-block; width: 100%; text-align: center;" href="manage-books.php">Cancel</a>
                        <button name="edit-book" class="submit-button">Edit Book</button>
                    </div>
                </div>
            </form>
            
        </div>
        <?php
        }
        else {
        ?>
        <div class="list-section">
            <h2 class="list-title">Manage Books</h2>
            <div class="list-container">
                <div>
                    <a href="?add-new-book">Add New Book</a>
                </div>
            </div>
            
        </div>
        <div class="list-section">
            <h2 class="list-title">Added Books</h2>
            <div class="list-container">
                <ul class="book-lists">
                    <?php
                        if(!$Book->bookRowCount()) {
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
                            <div class="book-rating">₹<?php echo $bookPrice; ?></div>
                            <div class="book-actions">
                            <a style="display: inline-block; margin-left: 14px;" class="buy" href="?edit-book&book-id=<?php echo $id; ?>">Edit</a>
                            <a style="display: inline-block; margin-left: 14px;" class="buy color-tomato" href="?delete-book&book-id=<?php echo $id; ?>">Delete</a>
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
        <?php
        }
        ?>
        
    </div>
    
    <script src="assets/js/index.js?<?php echo mt_rand(999,999999); ?>"></script>
</body>
</html>