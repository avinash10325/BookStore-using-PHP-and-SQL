<?php
class Address {
    private $db_table = "addressbook";
    private $database_connection;

    public $id;
    public $userID;
    public $address;
    public $addedAt;

    public function __construct($database_connection) {
        $this->database_connection = $database_connection;
    }

    public function addAddress() {
        try {
            // Query
            $query = "INSERT INTO $this->db_table (userID, address, addedAt) values (:userID, :address, :addedAt)";

            $statement = $this->database_connection->prepare($query);


            $statement->bindParam(':userID', $this->userID, PDO::PARAM_INT);
            $statement->bindParam(':address', $this->address, PDO::PARAM_STR);
            $statement->bindParam(':addedAt', $this->addedAt, PDO::PARAM_STR);

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

    public function isAddressExist() {
        try {

            $query = "SELECT COUNT(id) FROM $this->db_table WHERE userID = :userID";

            $statement = $this->database_connection->prepare($query);

            $statement->bindParam(':userID', $this->userID, PDO::PARAM_INT);

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

    public function getAddress() {
        try {
            // Query
            $query = "SELECT address FROM $this->db_table WHERE userID = :userID";

            // Query to statement
            // Prepare
            $statement = $this->database_connection->prepare($query);

            // Bind
            // Bind parameters
            $statement->bindParam(':userID', $this->userID, PDO::PARAM_INT);

            // Execute
            // $statement->execute();
            // Execute but we return true or false;
            // Return true if queue created/added
            $statement->execute();

            // Result
            $result = $statement->fetch(PDO::FETCH_ASSOC);


            if(is_array($result)) {
                if(count($result) > 0) {
                    return $result["address"];
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
            // Die, die, die, just die.
            // Got an error, just die :D
            // Output with array
            die('Error: ' . $error->getMessage());
        }
    }

    public function updateAddress() {
        try {
            // Query
            $query = "UPDATE $this->db_table SET address = :address  WHERE userID = :userID";

            // Query to statement
            // Prepare
            $statement = $this->database_connection->prepare($query);

            // Bind
            // Bind parameters
            $statement->bindParam(':address', $this->address, PDO::PARAM_STR);
            $statement->bindParam(':userID', $this->userID, PDO::PARAM_INT);

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