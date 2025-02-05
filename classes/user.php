<?php
include 'session.php';

class user {
    private $db;
   

    public function __construct(database $db) {
        $this->db = $db;
    }

   public function register($username, $email, $password) {
    // Check if the username or email already exists
    $sql = "SELECT * FROM users WHERE username = :username OR email = :email";
    $bind = ['username'=> $username, 'email'=> $email];
    $result = $this->db->query($sql,$bind);

    if ($result->rowCount() > 0) {
        //already exists
        $error = new session();
        $error->start();
        $error->set("registerError", "Selected UserName or Email is already in use please choose another email or userName");
        return false;
    } else {
        // Proceed to insert the new user
        $sql = "INSERT INTO users (username, email, password) 
                VALUES (:username, :email, :password)";
        $bind=['username'=> $username, 'email'=> $email,'password'=>$password];

        if ($this->db->query($sql,$bind)) {
            return true;
        } else {
            return false;
        }
    }
}

     public function login($username, $password) {
        // Query to fetch user data based on username
        $sql = "SELECT * FROM users WHERE username = :username";
        $bind = ['username'=> $username];
        $result = $this->db->query($sql,$bind );

        if ($result->rowCount() == 1) {
            // User found, fetch user data
            $user = $result->fetch(PDO::FETCH_ASSOC);
            // Verify the password using password_verify function
            if (password_verify($password, $user['password'])) {
                // Password is correct, return user data
               
                return $user;
            } else {
                return false;
            }
        } else {
            // User not found
          
            return false;
        }
    }
}
?>