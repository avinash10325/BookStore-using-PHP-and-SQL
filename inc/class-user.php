<?php
class User {
    private $db_table = "users";
    private $database_connection;

    public $id;
    public $fullName;
    public $username;
    public $password;
    public $userRole;
    public $accountCreatedAt;

    public function __construct($database_connection) {
        $this->database_connection = $database_connection;
    }

    /*
     * Creates an account, 
     * a brand new account!
     */
    public function create_account() {
        try {
            // Query
            $query = "INSERT INTO $this->db_table (fullName, username, password, userRole, accountCreatedAt) values (:fullName, :username, :password, :userRole, :accountCreatedAt)";

            $statement = $this->database_connection->prepare($query);


            $statement->bindParam(':fullName', $this->fullName, PDO::PARAM_STR);
            $statement->bindParam(':username', $this->username, PDO::PARAM_STR);
            $statement->bindParam(':password', $this->password, PDO::PARAM_STR);
            $statement->bindParam(':userRole', $this->userRole, PDO::PARAM_STR);
            $statement->bindParam(':accountCreatedAt', $this->accountCreatedAt, PDO::PARAM_STR);

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

    public function allUsers() {
        try {

            $query = "SELECT * FROM $this->db_table WHERE userRole = 'user' ORDER BY id DESC";

            $statement = $this->database_connection->prepare($query);
            $statement->execute();

            return $statement;
        }
        catch(PDOException $error) {
            die('Error: ' . $error->getMessage());
        }
    }

    public function getUserDetails() {
        try {

            $query = "SELECT * FROM $this->db_table WHERE id = :id";

            $statement = $this->database_connection->prepare($query);
            
            $statement->bindParam(':id', $this->id, PDO::PARAM_STR);

            $statement->execute();

            return $statement;
        }
        catch(PDOException $error) {
            die('Error: ' . $error->getMessage());
        }
    }

    public function isAccountExist() {
        try {

            $query = "SELECT COUNT(id) FROM $this->db_table WHERE username = :username AND password = :password";

            $statement = $this->database_connection->prepare($query);

            $statement->bindParam(':username', $this->username, PDO::PARAM_STR);
            $statement->bindParam(':password', $this->password, PDO::PARAM_STR);

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

    public function deleteUser() {
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

    public function isUsernameExist() {
        try {

            $query = "SELECT COUNT(id) FROM $this->db_table WHERE username = :username";

            $statement = $this->database_connection->prepare($query);

            $statement->bindParam(':username', $this->username, PDO::PARAM_STR);

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

    public function getUserRole() {
        try {
            // Query
            $query = "SELECT userRole FROM $this->db_table WHERE id = :id";

            // Query to statement
            // Prepare
            $statement = $this->database_connection->prepare($query);

            // Bind
            // Bind parameters
            $statement->bindParam(':id', $this->id, PDO::PARAM_INT);

            // Execute
            // $statement->execute();
            // Execute but we return true or false;
            // Return true if queue created/added
            $statement->execute();

            // Result
            $result = $statement->fetch(PDO::FETCH_ASSOC);


            if(is_array($result)) {
                if(count($result) > 0) {
                    return $result["userRole"];
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

    public function getNameByID($id) {
        try {
            // Query
            $query = "SELECT fullName FROM $this->db_table WHERE id = :id";

            // Query to statement
            // Prepare
            $statement = $this->database_connection->prepare($query);

            // Bind
            // Bind parameters
            $statement->bindParam(':id', $id, PDO::PARAM_INT);

            // Execute
            // $statement->execute();
            // Execute but we return true or false;
            // Return true if queue created/added
            $statement->execute();

            // Result
            $result = $statement->fetch(PDO::FETCH_ASSOC);


            if(is_array($result)) {
                if(count($result) > 0) {
                    return $result["fullName"];
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

    public function totalUsers() {
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

    public function getUserPassword() {
        try {
            // Query
            $query = "SELECT password FROM $this->db_table WHERE id = :id";

            // Query to statement
            // Prepare
            $statement = $this->database_connection->prepare($query);

            // Bind
            // Bind parameters
            $statement->bindParam(':id', $this->id, PDO::PARAM_INT);

            // Execute
            // $statement->execute();
            // Execute but we return true or false;
            // Return true if queue created/added
            $statement->execute();

            // Result
            $result = $statement->fetch(PDO::FETCH_ASSOC);


            if(is_array($result)) {
                if(count($result) > 0) {
                    return $result["password"];
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

    public function getUserID() {
        try {
            // Query
            $query = "SELECT id FROM $this->db_table WHERE username = :username";

            // Query to statement
            // Prepare
            $statement = $this->database_connection->prepare($query);

            // Bind
            // Bind parameters
            $statement->bindParam(':username', $this->username, PDO::PARAM_STR);

            // Execute
            // $statement->execute();
            // Execute but we return true or false;
            // Return true if queue created/added
            $statement->execute();

            // Result
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
            // Die, die, die, just die.
            // Got an error, just die :D
            // Output with array
            die('Error: ' . $error->getMessage());
        }
    }

    public function updateFullName() {
        try {
            // Query
            $query = "UPDATE $this->db_table SET fullName = :fullName  WHERE id = :id";

            // Query to statement
            // Prepare
            $statement = $this->database_connection->prepare($query);

            // Bind
            // Bind parameters
            $statement->bindParam(':fullName', $this->fullName, PDO::PARAM_STR);
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

    public function updateUsername() {
        try {
            // Query
            $query = "UPDATE $this->db_table SET username = :username  WHERE id = :id";

            // Query to statement
            // Prepare
            $statement = $this->database_connection->prepare($query);

            // Bind
            // Bind parameters
            $statement->bindParam(':username', $this->username, PDO::PARAM_STR);
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

    public function updatePassword() {
        try {
            // Query
            $query = "UPDATE $this->db_table SET password = :password  WHERE id = :id";

            // Query to statement
            // Prepare
            $statement = $this->database_connection->prepare($query);

            // Bind
            // Bind parameters
            $statement->bindParam(':password', $this->password, PDO::PARAM_STR);
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