<?php
class Genre {
    private $db_table = "booksgenre";
    private $database_connection;

    public $id;
    public $genreName;
    public $genreDescription;
    public $genreAddedAt;
    public $genreAddedBy;

    public function __construct($database_connection) {
        $this->database_connection = $database_connection;
    }

    public function addGenre() {
        try {
            // Query
            $query = "INSERT INTO $this->db_table (genreName, genreDescription, genreAddedAt, genreAddedBy) values (:genreName, :genreDescription, :genreAddedAt, :genreAddedBy)";

            $statement = $this->database_connection->prepare($query);


            $statement->bindParam(':genreName', $this->genreName, PDO::PARAM_STR);
            $statement->bindParam(':genreDescription', $this->genreDescription, PDO::PARAM_STR);
            $statement->bindParam(':genreAddedAt', $this->genreAddedAt, PDO::PARAM_STR);
            $statement->bindParam(':genreAddedBy', $this->genreAddedBy, PDO::PARAM_STR);

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

    public function isGenreIDExist() {
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

    public function getGenreLists() {
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

    public function deleteGenre() {
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

    public function genreRowCount() {
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

    public function genreRowCountByID() {
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

    public function totalGenre() {
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

    public function getGenreName() {
        try {
            $query = "SELECT genreName FROM $this->db_table WHERE id = :id";

            $statement = $this->database_connection->prepare($query);

            $statement->bindParam(':id', $this->id, PDO::PARAM_INT);

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

    public function getGenreDesc() {
        try {
            $query = "SELECT genreDescription FROM $this->db_table WHERE id = :id";

            $statement = $this->database_connection->prepare($query);

            $statement->bindParam(':id', $this->id, PDO::PARAM_INT);

            $statement->execute();

            $result = $statement->fetch(PDO::FETCH_ASSOC);


            if(is_array($result)) {
                if(count($result) > 0) {
                    return $result["genreDescription"];
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

    public function updateGenre() {
        try {
            // Query
            $query = "UPDATE $this->db_table SET genreName = :genreName, genreDescription = :genreDescription WHERE id = :id";

            // Query to statement
            // Prepare
            $statement = $this->database_connection->prepare($query);

            // Bind
            // Bind parameters
            $statement->bindParam(':genreName', $this->genreName, PDO::PARAM_STR);
            $statement->bindParam(':genreDescription', $this->genreDescription, PDO::PARAM_STR);
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
}
?>