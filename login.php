<?php

include 'classes/database.php';
include 'classes/user.php'; 


$db = new database();
$user = new user($db);
$session = new session;

// check for any messages 
if ($session->exists("message")){
    $inform = $session->get('message');
    $session->remove('messsage')?>
 <script type="text/javascript">
                alert("<?php echo $inform ?>");
           </script>
    <?php
}
$errors = array(); // Array to store any errors during registration

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim(filter_var($_POST['username'], FILTER_SANITIZE_STRING));
    $password = trim(filter_var($_POST['password']));

      if ($user->login($username, $password)) {
         $data = $user->login($username, $password);/// get user data
         $session->start();//start session
         $session->set("user", $data);
        header('Location: index.php');//home page
        exit;
    } else {?>
         <script type="text/javascript">
                alert("<?php echo 'Incorrect user details please try agian'; ?>");
           </script>
           <?php
        
    }
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Sign in to Account </title>
         <link rel="stylesheet" type="text/css" href="classes/logger.css">
    </head>
    <body>
       
         <form action="" method="post">
              <h2 class="form-title">Sign in to Account</h2>
        <div class="form-group">
            <label for="email">User Name:</label>
            <input type="text" id="username" name="username" placeholder="Username" required >
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <input type="submit" value="Login">
         <p>Don't have an account?<a href = "register.php"> Create account</a> instead.</p>
    </form>
    </body>
</html>

