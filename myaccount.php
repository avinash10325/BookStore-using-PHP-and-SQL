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

while($rowUD = $getUserDetails->fetch(PDO::FETCH_ASSOC)) {
    $username_ = $rowUD["username"];
    $fullName_ = $rowUD["fullName"];
    $userRole_ = $rowUD["userRole"];
}

if(isset($_POST["update-name"])) {
    $getName = $_POST["fullName"];

    if(empty($getName)) {
        $error = true;
        $error_message = "Please enter your name";
    }
    else {
        $User->fullName = $getName;

        if($User->updateFullName()) {
            header('Location: myaccount.php?updatesuccess&type=name');
            exit;
        }
        else {
            $error = true;
            $error_message = "Failed to change name.";
        }
    }
}

if(isset($_POST["update-address"])) {
    $getName = $_POST["address"];

    if(empty($getName)) {
        $error = true;
        $error_message = "Please enter your address.";
    }
    else {
        $Address->address = $getName;

        if($Address->updateAddress()) {
            header('Location: myaccount.php?updatesuccess&type=address');
            exit;
        }
        else {
            $error = true;
            $error_message = "Failed to change address.";
        }
    }
}

if(isset($_POST["add-address"])) {
    $getName = $_POST["address"];

    if(empty($getName)) {
        $error = true;
        $error_message = "Please enter your address.";
    }
    else {
        $Address->addedAt = Date("Y-m-d H:is");
        $Address->address = $getName;

        if($Address->addAddress()) {
            header('Location: myaccount.php?updatesuccess&type=addaddress');
            exit;
        }
        else {
            $error = true;
            $error_message = "Failed to add address.";
        }
    }
}

if(isset($_POST["update-password"])) {
    $pass = $_POST["current_password"];
    $newpass = $_POST["new_password"];
    $newpassconf = $_POST["new_conf_password"];

    if(empty($pass)) {
        $error = true;
        $error_message = "Please enter your current password.";
    }
    else if(empty($newpass)) {
        $error = true;
        $error_message = "Please enter your new password.";
    }
    else if(empty($newpassconf)) {
        $error = true;
        $error_message = "Please re-enter your new password.";
    }
    else if($User->getUserPassword() !== $pass) {
        $error = true;
        $error_message = "Invalid current password, please try again.";
    }
    else if($newpass !== $newpassconf) {
        $error = true;
        $error_message = "Your entered new password and re-entered password doesn't matched with each other.";
    }
    else {
        $User->password = $newpass;

        if($User->updatePassword()) {
            header('Location: myaccount.php?updatesuccess&type=password');
            exit;
        }
        else {
            $error = true;
            $error_message = "Failed to change password.";
        }
    }
}

if(isset($_POST["update-username"])) {
    $getName = $_POST["username"];
    $User->username = $getName;

    if(empty($getName)) {
        $error = true;
        $error_message = "Please enter your username";
    }
    else if($User->isUsernameExist()) {
        $error = true;
        $error_message = "Username is already taken, please choose a different one.";
    }
    else {
        $User->fullName = $getName;

        if($User->updateUsername()) {
            setcookie('username', $getName, [
                "expires" => time() + strtotime(Date("Y-m-d H:i:s", strtotime("+1 Year"))),
                "path" => "/"
            ]);

            header('Location: myaccount.php?updatesuccess&type=username');
            exit;
        }
        else {
            $error = true;
            $error_message = "Failed to change username.";
        }
    }
}

if(isset($_GET["updatesuccess"])) {
    $updateSuccess = true;
    $updateType = $_GET["type"];

    if($updateType == "name") {
        $updateMessage = "Name changed successfully.";
    }
    else if($updateType == "username") {
        $updateMessage = "Userame changed successfully.";
    }
    else if($updateType == "password") {
        $updateMessage = "Password changed successfully.";
    }
    else if($updateType == "address") {
        $updateMessage = "Address changed successfully.";
    }
    else if($updateType == "addaddress") {
        $updateMessage = "Address added successfully.";
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
    <title>My Account | My Book Store</title>
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
                    <li class="menu active"><a href="myaccount.php">My Account</a></li>
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
            <h2 class="list-title"><?php echo $fullName_; ?> <span style="color:#555555; text-transform:capitalize; font-size: 14px;">(<?php echo $userRole_; ?>)</span></h2>
            <div class="list-container">
                <div class="book-lists">
                    <div class="list" style="-webkit-justify-content: flex-start; -moz-justify-content: flex-start; justify-content: flex-start;"><span style="color:#555555; margin-right: 14px;">Userrname:</span> <?php echo $username_; ?></div>
                    <div class="list" style="-webkit-justify-content: flex-start; -moz-justify-content: flex-start; justify-content: flex-start;"><span style="color:#555555; margin-right: 14px;">Current Address:</span> <?php echo $address; ?></div>
                </div>
                <div class="more-call">
                    <a href="viewreviews.php">My Ratings & Reviews</a>
                </div>
            </div>
        </div>
        <div class="list-section">
            <h2 class="list-title">Update Full Name</h2>
            <div class="list-container">
                <div class="book-lists">
                <form class="form single-line" method="post">
                <input name="fullName" type="text" class="text" placeholder="Full Name" value="<?php echo $fullName_; ?>">
                <button name="update-name" class="submit-button">Update</button>
                </form>
                    </div>
                <!-- <div class="more-call">
                    <a href="#more">Browse More New Books</a>
                </div> -->
            </div>
        </div>
        <div class="list-section">
            <h2 class="list-title">Update Username</h2>
            <div class="list-container">
                <div class="book-lists">
                <form class="form single-line" method="post">
                <input name="username" type="text" class="text" placeholder="Username" value="<?php echo $username_; ?>">
                <button name="update-username" class="submit-button">Update</button>
                </form>
                    </div>
                <!-- <div class="more-call">
                    <a href="#more">Browse More New Books</a>
                </div> -->
            </div>
        </div>
        <div class="list-section">
            <?php
                if(!$addressExist) {
            ?>
            <h2 class="list-title">Add Address</h2>
            <?php
                }
                else {
            ?>
            <h2 class="list-title">Update Address</h2>
            <?php
                }
            ?>
            <div class="list-container">
                <?php
                    if(!$addressExist) {
                ?>
                <div class="book-lists">
                    <form class="form single-line" method="post">
                    <input name="address" type="text" class="text" placeholder="Address">
                    <button name="add-address" class="submit-button">Add New Address</button>
                    </form>
                </div>
                <?php
                    }
                    else {
                ?>
                <div class="book-lists">
                    <form class="form single-line" method="post">
                    <input name="address" type="text" class="text" placeholder="Address" value="<?php echo $address; ?>">
                    <button name="update-address" class="submit-button">Update</button>
                    </form>
                </div>
                <?php
                    }
                ?>
                
                <!-- <div class="more-call">
                    <a href="#more">Browse More New Books</a>
                </div> -->
            </div>
        </div>
        <div class="list-section">
            <h2 class="list-title">Change Password</h2>
            <div class="list-container">
                <div class="book-lists">
                <form class="form single-line" method="post">
                <input name="current_password" type="password" class="text" placeholder="Current Password">
                <input name="new_password" type="password" class="text" placeholder="New Password">
                <input name="new_conf_password" type="password" class="text" placeholder="Confirm New Password">
                <button name="update-password" class="submit-button">Change</button>
                </form>
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