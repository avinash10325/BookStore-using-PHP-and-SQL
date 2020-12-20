<?php
class Purchase {
    private $db_table = "bookspurchased";
    private $database_connection;

    public $id;
    public $bookID;
    public $bookPurchasedBy;
    public $addressID;
    public $bookPurchasedOn;

    public function __construct($database_connection) {
        $this->database_connection = $database_connection;
    }

    public function addToPurchase() {
        try {
            // Query
            $query = "INSERT INTO $this->db_table (bookID, bookPurchasedBy, addressID, bookPurchasedOn) values (:bookID, :bookPurchasedBy, :addressID, :bookPurchasedOn)";

            $statement = $this->database_connection->prepare($query);


            $statement->bindParam(':bookID', $this->bookID, PDO::PARAM_INT);
            $statement->bindParam(':bookPurchasedBy', $this->bookPurchasedBy, PDO::PARAM_INT);
            $statement->bindParam(':addressID', $this->addressID, PDO::PARAM_INT);
            $statement->bindParam(':bookPurchasedOn', $this->bookPurchasedOn, PDO::PARAM_STR);

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

    public function getPurchaseCount() {
        try {

            $query = "SELECT COUNT(id) FROM $this->db_table WHERE bookPurchasedBy = :bookPurchasedBy";

            $statement = $this->database_connection->prepare($query);

            $statement->bindParam(':bookPurchasedBy', $this->bookPurchasedBy, PDO::PARAM_INT);

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

    public function totalPurchase() {
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

    public function getPurchasedBooksList() {
        try {

            $query = "SELECT * FROM $this->db_table WHERE bookPurchasedBy = :bookPurchasedBy ORDER BY id DESC";

            $statement = $this->database_connection->prepare($query);

            $statement->bindParam(':bookPurchasedBy', $this->bookPurchasedBy, PDO::PARAM_INT);

            $statement->execute();

            return $statement;
        }
        catch(PDOException $error) {
            die('Error: ' . $error->getMessage());
        }
    }
}
?>