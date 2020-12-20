<?php
// Config file.

// Set timezone..
date_default_timezone_set('Asia/Kolkata');

define('DS', DIRECTORY_SEPARATOR);

// Required files.
require dirname(__FILE__) . DS . 'inc' . DS . 'class-database.php';
require dirname(__FILE__) . DS . 'inc' . DS . 'class-user.php';
require dirname(__FILE__) . DS . 'inc' . DS . 'class-book.php';
require dirname(__FILE__) . DS . 'inc' . DS . 'class-genre.php';
require dirname(__FILE__) . DS . 'inc' . DS . 'class-address.php';
require dirname(__FILE__) . DS . 'inc' . DS . 'class-purchase.php';
require dirname(__FILE__) . DS . 'inc' . DS . 'class-rating.php';
?>