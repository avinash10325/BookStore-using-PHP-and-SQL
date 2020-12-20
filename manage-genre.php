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

$genreList = $Genre->getGenreLists();

if(isset($_GET["delete-genre"])) {
    $getGenreID = $_GET["genre-id"];

    $Genre->id = $getGenreID;

    if(!$Genre->deleteGenre()) {
        $error = true;
        $error_message = "Failed to delete.";
    }
    else {
        header("Location: manage-genre.php?deletesuccess");
        exit;
    }
}

if(isset($_GET["deletesuccess"])) {
    $updateSuccess = true;
    $updateMessage = "Genre deleted successfully.";
}

if(isset($_POST["add-genre"])) {
    $getGenre = $_POST["genre"];
    $getGenreDesc = $_POST["desc"];

    if(empty($getGenre) || empty($getGenreDesc)) {
        $error = true;
        $error_message = "Please fill up all the fields to add genre.";
    }
    else {
        $Genre->genreName = $getGenre;
        $Genre->genreDescription = $getGenreDesc;
        $Genre->genreAddedAt = Date("Y-m-d H:i:s");
        $Genre->genreAddedBy = $userID;

        if(!$Genre->addGenre()) {
            $error = true;
            $error_message = "Failed to add genre.";
        }
        else {
            header("Location: manage-genre.php?addsuccess");
        }
    }
}

if(isset($_GET["addsuccess"])) {
    $updateSuccess = true;
    $updateMessage = "Genre added successfully.";
}

if(isset($_GET["edit-genre"])) {
    $getGenreID = $_GET["genre-id"];
    $getGenre = $_GET["genre-name"];

    if(isset($_POST["edit-genre"])) {
        $getGenre = $_POST["genre"];
        $getGenreDesc = $_POST["desc"];
    
        if(empty($getGenre) || empty($getGenreDesc)) {
            $error = true;
            $error_message = "Please fill up all the fields to add genre.";
        }
        else {
            $Genre->id = $getGenreID;
            $Genre->genreName = $getGenre;
            $Genre->genreDescription = $getGenreDesc;
    
            if(!$Genre->updateGenre()) {
                $error = true;
                $error_message = "Failed to update genre.";
            }
            else {
                header("Location: manage-genre.php?updatesuccess");
            }
        }
    }

}

if(isset($_GET["updatesuccess"])) {
    $updateSuccess = true;
    $updateMessage = "Genre edited successfully.";
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
        <div class="list-section">
            <?php
            if(!isset($_GET["edit-genre"])) {
            ?>
            <h2 class="list-title">Add New Genre</h2>
            <div class="list-container">
                <div class="book-lists">
                <form class="form single-line" method="post">
                <input name="genre" type="text" class="text" placeholder="Genre" value="<?php echo $getGenre; ?>" autofocus>
                <input name="desc" type="text" class="text" placeholder="Description for genre" value="<?php echo $getGenreDesc; ?>" autofocus>
                <button name="add-genre" class="submit-button">Add New Genre</button>
                </form>
                    </div>
                <!-- <div class="more-call">
                    <a href="#more">Browse More New Books</a>
                </div> -->
            </div>
            <?php
            }
            else {
            ?>
            <h2 class="list-title">Edit Genre</h2>
            <div class="list-container">
                <div class="book-lists">
                <form class="form single-line" method="post">
                <input name="genre" type="text" class="text" placeholder="<?php echo $getGenre; ?>" autofocus>
                <input name="desc" type="text" class="text" placeholder="Description for genre" value="<?php echo $getGenreDesc; ?>" autofocus>
                <button name="edit-genre" class="submit-button">Edit Genre</button>
                </form>
                    </div>
                <!-- <div class="more-call">
                    <a href="#more">Browse More New Books</a>
                </div> -->
            </div>
            <?php

            }
            ?>
            
        </div>
    
        <div class="list-section">
            <h2 class="list-title">Genres</h2>
            <div class="list-container">
                <div class="book-lists" style="font-size: 18px;">
                <?php
                if(!$Genre->genreRowCount()) {
                ?>
                <div class="list no-list">No users.</div>
                <?php
                }
                else {
                ?>
                <?php
                while($row = $genreList->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                ?>
                <div class="list"><a href="browse-books.php?genre-id=<?php echo $id; ?>"><?php echo $genreName; ?></a>
                <div><a style="display: inline-block; margin-right: 14px" href="?edit-genre&genre-id=<?php echo $id; ?>&genre-name=<?php echo $genreName; ?>">Edit</a> <a class="color-tomato" href="?delete-genre&genre-id=<?php echo $id; ?>">Delete</a></div></div>
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