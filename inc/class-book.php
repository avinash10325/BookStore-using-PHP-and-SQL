<?php
class Book {
    private $db_table = "books";
    private $db_table_rating = "booksrating";
    private $db_table_genre = "booksgenre";
    private $database_connection;

    public $id;
    public $bookGenreID;
    public $bookName;
    public $bookAuthor;
    public $bookPublishedOn;
    public $bookPrice;
    public $bookDescription;
    public $bookStockCount;
    public $bookAddedAt;
    public $bookAddedBy;

    public function __construct($database_connection) {
        $this->database_connection = $database_connection;
    }

    public function addBook() {
        try {
            // Query
            $query = "INSERT INTO $this->db_table (booksGenreID, bookName, bookAuthor, bookPublishedOn, bookPrice, bookDescription, bookStockCount, bookAddedAt, bookAddedBy) values (:booksGenreID, :bookName, :bookAuthor, :bookPublishedOn, :bookPrice, :bookDescription, :bookStockCount, :bookAddedAt, :bookAddedBy)";

            $statement = $this->database_connection->prepare($query);


            $statement->bindParam(':booksGenreID', $this->booksGenreID, PDO::PARAM_INT);
            $statement->bindParam(':bookName', $this->bookName, PDO::PARAM_STR);
            $statement->bindParam(':bookAuthor', $this->bookAuthor, PDO::PARAM_STR);
            $statement->bindParam(':bookPublishedOn', $this->bookPublishedOn, PDO::PARAM_STR);
            $statement->bindParam(':bookPrice', $this->bookPrice, PDO::PARAM_INT);
            $statement->bindParam(':bookDescription', $this->bookDescription, PDO::PARAM_STR);
            $statement->bindParam(':bookStockCount', $this->bookStockCount, PDO::PARAM_INT);
            $statement->bindParam(':bookAddedAt', $this->bookAddedAt, PDO::PARAM_STR);
            $statement->bindParam(':bookAddedBy', $this->bookAddedBy, PDO::PARAM_STR);

            if($statement->execute()) {
                return true;
            }

            else {
                return false;
            }

        }
        catch(PDOException $error) {
            die('Error: ' . $error->getMessage());
        }
    }

    public function getNewBooksList() {
        try {

            $query = "SELECT * FROM $this->db_table ORDER BY id DESC";

            $statement = $this->database_connection->prepare($query);
            $statement->execute();

            return $statement;
        }
        catch(PDOException $error) {
            die('Error: ' . $error->getMessage());
        }
    }

    public function getBookDetails() {
        try {

            $query = "SELECT * FROM $this->db_table WHERE id = :id";

            $statement = $this->database_connection->prepare($query);
            
            $statement->bindParam(':id', $this->id, PDO::PARAM_INT);

            $statement->execute();

            return $statement;
        }
        catch(PDOException $error) {
            die('Error: ' . $error->getMessage());
        }
    }

    

    public function bookRowCount() {
        try {

            $query = "SELECT COUNT(id) FROM $this->db_table";

            $statement = $this->database_connection->prepare($query);

            $statement->execute();

            $result = $statement->fetchColumn();

            if($result > 0) {
                return true;
            }
            else {
                return false;
            }

        }
        catch(PDOException $error) {
            die('Error: ' . $error->getMessage());
        }
    }

    public function updateBook() {
        try {
            // Query
            $query = "UPDATE $this->db_table SET booksGenreID = :booksGenreID, bookName = :bookName, bookAuthor = :bookAuthor, bookPublishedOn = :bookPublishedOn, bookPrice = :bookPrice, bookDescription = :bookDescription, bookStockCount = :bookStockCount WHERE id = :id";

            // Query to statement
            // Prepare
            $statement = $this->database_connection->prepare($query);

            // Bind
            // Bind parameters
            $statement->bindParam(':booksGenreID', $this->booksGenreID, PDO::PARAM_INT);
            $statement->bindParam(':bookName', $this->bookName, PDO::PARAM_STR);
            $statement->bindParam(':bookAuthor', $this->bookAuthor, PDO::PARAM_STR);
            $statement->bindParam(':bookPublishedOn', $this->bookPublishedOn, PDO::PARAM_STR);
            $statement->bindParam(':bookPrice', $this->bookPrice, PDO::PARAM_STR);
            $statement->bindParam(':bookDescription', $this->bookDescription, PDO::PARAM_STR);
            $statement->bindParam(':bookStockCount', $this->bookStockCount, PDO::PARAM_STR);
            $statement->bindParam(':id', $this->id, PDO::PARAM_INT);

            // Execute
            // $statement->execute();
            // Execute but we return true or false;
            // Return true if queue created/added
            $statement->execute();

            // Result
            if($statement) {
                return true;
            }
            else {
                return false;
            }

        }
        catch(PDOException $error) {
            // Die, die, die, just die.
            // Got an error, just die :D
            // Output with array
            die('Error: ' . $error->getMessage());
        }
    }

    public function isBookIDExist() {
        try {

            $query = "SELECT COUNT(id) FROM $this->db_table WHERE id = :id";

            $statement = $this->database_connection->prepare($query);

            $statement->bindParam(':id', $this->id, PDO::PARAM_INT);

            $statement->execute();

            $result = $statement->fetchColumn();

            if($result > 0) {
                return true;
            }
            else {
                return false;
            }

        }
        catch(PDOException $error) {
            die('Error: ' . $error->getMessage());
        }
    }

    public function totalBooks() {
        try {

            $query = "SELECT COUNT(id) FROM $this->db_table";

            $statement = $this->database_connection->prepare($query);

            $statement->execute();

            $result = $statement->fetchColumn();

            if($result > 0) {
                return $result;
            }
            else {
                return 0;
            }

        }
        catch(PDOException $error) {
            die('Error: ' . $error->getMessage());
        }
    }

    public function bookCountByGenre() {
        try {

            $query = "SELECT COUNT(id) FROM $this->db_table WHERE booksGenreID = :booksGenreID";

            $statement = $this->database_connection->prepare($query);

            $statement->bindParam(':booksGenreID', $this->booksGenreID, PDO::PARAM_INT);

            $statement->execute();

            $result = $statement->fetchColumn();

            if($result > 0) {
                return $result;
            }
            else {
                return 0;
            }

        }
        catch(PDOException $error) {
            die('Error: ' . $error->getMessage());
        }
    }

    public function totalBookStocks() {
        try {

            $query = "SELECT SUM(bookStockCount) FROM $this->db_table";

            $statement = $this->database_connection->prepare($query);

            $statement->execute();

            $result = $statement->fetchColumn();

            if($result > 0) {
                return $result;
            }
            else {
                return 0;
            }

        }
        catch(PDOException $error) {
            die('Error: ' . $error->getMessage());
        }
    }

    public function getBookNameByBookID() {
        try {
            $query = "SELECT bookName FROM $this->db_table WHERE id = :id";

            $statement = $this->database_connection->prepare($query);

            $statement->bindParam(':id', $this->id, PDO::PARAM_INT);

            $statement->execute();

            $result = $statement->fetch(PDO::FETCH_ASSOC);


            if(is_array($result)) {
                if(count($result) > 0) {
                    return $result["bookName"];
                }
                else {
                    return null;
                }
            }
            else {
                return null;
            }

        }
        catch(PDOException $error) {
            die('Error: ' . $error->getMessage());
        }
    }

    public function getBookDetailAtOnce($id) {
        try {
            $query = "SELECT id, bookName, bookAuthor, bookPublishedOn, bookPrice, bookDescription, bookStockCount, bookAddedAt, bookAddedBy FROM $this->db_table WHERE id = :id";

            $statement = $this->database_connection->prepare($query);

            $statement->bindParam(':id', $id, PDO::PARAM_INT);

            $statement->execute();

            $result = $statement->fetch(PDO::FETCH_ASSOC);


            if(is_array($result)) {
                if(count($result) > 0) {
                    return $result;
                }
                else {
                    return null;
                }
            }
            else {
                return null;
            }

        }
        catch(PDOException $error) {
            die('Error: ' . $error->getMessage());
        }
    }

    public function deleteBook() {
        try {

            $query = "DELETE FROM $this->db_table WHERE id = :id";

            $statement = $this->database_connection->prepare($query);

            $statement->bindParam(':id', $this->id, PDO::PARAM_INT);

            $statement->execute();

            if($statement) {
                return true;
            }
            else {
                return false;
            }

        }
        catch(PDOException $error) {
            die('Error: ' . $error->getMessage());
        }
    }

    public function getBookStock() {
        try {
            $query = "SELECT bookStockCount FROM $this->db_table WHERE id = :id";

            $statement = $this->database_connection->prepare($query);

            $statement->bindParam(':id', $this->id, PDO::PARAM_INT);

            $statement->execute();

            $result = $statement->fetch(PDO::FETCH_ASSOC);


            if(is_array($result)) {
                if(count($result) > 0) {
                    return $result["bookStockCount"];
                }
                else {
                    return null;
                }
            }
            else {
                return null;
            }

        }
        catch(PDOException $error) {
            die('Error: ' . $error->getMessage());
        }
    }

    public function updateBookStock($bookStockCount) {
        try {
            // Query
            $query = "UPDATE $this->db_table SET bookStockCount = :bookStockCount  WHERE id = :id";

            // Query to statement
            // Prepare
            $statement = $this->database_connection->prepare($query);

            // Bind
            // Bind parameters
            $statement->bindParam(':bookStockCount', $bookStockCount, PDO::PARAM_INT);
            $statement->bindParam(':id', $this->id, PDO::PARAM_INT);

            // Execute
            // $statement->execute();
            // Execute but we return true or false;
            // Return true if queue created/added
            $statement->execute();

            // Result
            if($statement) {
                return true;
            }
            else {
                return false;
            }

        }
        catch(PDOException $error) {
            // Die, die, die, just die.
            // Got an error, just die :D
            // Output with array
            die('Error: ' . $error->getMessage());
        }
    }

    public function getBookGenreID() {
        try {
            $query = "SELECT booksGenreID FROM $this->db_table WHERE id = :id";

            $statement = $this->database_connection->prepare($query);

            $statement->bindParam(':id', $this->id, PDO::PARAM_INT);

            $statement->execute();

            $result = $statement->fetch(PDO::FETCH_ASSOC);


            if(is_array($result)) {
                if(count($result) > 0) {
                    return $result["booksGenreID"];
                }
                else {
                    return null;
                }
            }
            else {
                return null;
            }

        }
        catch(PDOException $error) {
            die('Error: ' . $error->getMessage());
        }
    }

    public function getBookGenre() {
        try {
            $bookGenreID = $this->getBookGenreID();

            $query = "SELECT genreName FROM $this->db_table_genre WHERE id = :id";

            $statement = $this->database_connection->prepare($query);

            $statement->bindParam(':id', $bookGenreID, PDO::PARAM_INT);

            $statement->execute();

            $result = $statement->fetch(PDO::FETCH_ASSOC);


            if(is_array($result)) {
                if(count($result) > 0) {
                    return $result["genreName"];
                }
                else {
                    return null;
                }
            }
            else {
                return null;
            }

        }
        catch(PDOException $error) {
            die('Error: ' . $error->getMessage());
        }
    }

    public function getBookRatings() {
        try {
            $query = "SELECT SUM(rating)/(SELECT COUNT(rating) FROM $this->db_table_rating WHERE bookID = :id) as rating FROM $this->db_table_rating WHERE bookID = :id";

            $statement = $this->database_connection->prepare($query);

            $statement->bindParam(':id', $this->id, PDO::PARAM_INT);

            $statement->execute();

            $result = $statement->fetch(PDO::FETCH_ASSOC);


            if(is_array($result)) {
                if(count($result) > 0) {
                    return round($result["rating"], 1);
                }
                else {
                    return null;
                }
            }
            else {
                return null;
            }

        }
        catch(PDOException $error) {
            die('Error: ' . $error->getMessage());
        }
    }

    public function getUserID() {
        try {
            $query = "SELECT id FROM $this->db_table WHERE username = :username";

            $statement = $this->database_connection->prepare($query);

            $statement->bindParam(':username', $this->username, PDO::PARAM_STR);

            $statement->execute();

            $result = $statement->fetch(PDO::FETCH_ASSOC);


            if(is_array($result)) {
                if(count($result) > 0) {
                    return $result["id"];
                }
                else {
                    return null;
                }
            }
            else {
                return null;
            }

        }
        catch(PDOException $error) {
            die('Error: ' . $error->getMessage());
        }
    }
}
?>