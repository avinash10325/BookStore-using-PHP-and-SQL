<?php
class Rating {
    private $db_table = "booksrating";
    private $database_connection;

    public $id;
    public $bookID;
    public $rating;
    public $comment;
    public $ratedBy;

    public function __construct($database_connection) {
        $this->database_connection = $database_connection;
    }

    public function addRating() {
        try {
            // Query
            $query = "INSERT INTO $this->db_table (bookID, rating, comment, ratedBy) values (:bookID, :rating, :comment, :ratedBy)";

            $statement = $this->database_connection->prepare($query);


            $statement->bindParam(':bookID', $this->bookID, PDO::PARAM_INT);
            $statement->bindParam(':rating', $this->rating, PDO::PARAM_INT);
            $statement->bindParam(':comment', $this->comment, PDO::PARAM_STR);
            $statement->bindParam(':ratedBy', $this->ratedBy, PDO::PARAM_INT);

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

    public function getRatings() {
        try {

            $query = "SELECT * FROM $this->db_table WHERE ratedBy = :ratedBy ORDER BY id DESC";

            $statement = $this->database_connection->prepare($query);

            $statement->bindParam(':ratedBy', $this->ratedBy, PDO::PARAM_INT);

            $statement->execute();

            return $statement;
        }
        catch(PDOException $error) {
            die('Error: ' . $error->getMessage());
        }
    }

    public function getRatingsAll() {
        try {

            $query = "SELECT * FROM $this->db_table WHERE bookID = :bookID ORDER BY id DESC";

            $statement = $this->database_connection->prepare($query);

            $statement->bindParam(':bookID', $this->bookID, PDO::PARAM_INT);

            $statement->execute();

            return $statement;
        }
        catch(PDOException $error) {
            die('Error: ' . $error->getMessage());
        }
    }

    public function getBookDetails() {
        try {

            $query = "SELECT * FROM $this->db_table WHERE ratedBy = :ratedBy";

            $statement = $this->database_connection->prepare($query);
            
            $statement->bindParam(':ratedBy', $this->ratedBy, PDO::PARAM_INT);

            $statement->execute();

            return $statement;
        }
        catch(PDOException $error) {
            die('Error: ' . $error->getMessage());
        }
    }

    public function ratingRowCount() {
        try {

            $query = "SELECT COUNT(id) FROM $this->db_table WHERE ratedBy = :ratedBy";

            $statement = $this->database_connection->prepare($query);

            $statement->bindParam(':ratedBy', $this->ratedBy, PDO::PARAM_INT);

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

    public function ratingRowCountAll() {
        try {

            $query = "SELECT COUNT(id) FROM $this->db_table WHERE bookID = :bookID";

            $statement = $this->database_connection->prepare($query);

            $statement->bindParam(':bookID', $this->bookID, PDO::PARAM_INT);

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

    public function getRatingsCount($bookID) {
        try {

            $query = "SELECT COUNT(id) FROM $this->db_table WHERE bookID = :bookID";

            $statement = $this->database_connection->prepare($query);

            $statement->bindParam(':bookID', $bookID, PDO::PARAM_INT);

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

    public function deleteRating() {
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