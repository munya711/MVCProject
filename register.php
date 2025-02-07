<?php

include 'classes/database.php'; 
include 'classes/user.php'; 
include 'mailer.php';

$db = new Database();
$user = new User($db);
$errors = array();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim(filter_var($_POST['username'], FILTER_SANITIZE_STRING));
    $email = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
    $password = trim(password_hash($_POST['password'], PASSWORD_DEFAULT));

    $sql = "INSERT INTO users (username, email, password) 
                VALUES ('$username', '$email', '$password')";
    
      if ($user->register($username, $email, $password)) {
        sendRegistrationEmail($email);
        header('Location: login.php');
        
        exit;
    } else {
      
        ?>
           <script type="text/javascript">
                alert("<?php echo $_SESSION['registerError']; ?>");
           </script>
     <?php 
        
    }
    
}

?>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="classes/logger.css">  
        <title>Register</title>
    </head>
    <body>
      
        
        <form class="form-horizontal" method="post" action="register.php">
            <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Username</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="username" name="username" placeholder="Username" required >
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
    <div class="col-sm-10">
     
      <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">Password</label>
    <div class="col-sm-10">
      
      <input type="password" class="form-control" id="password" placeholder="Password" name="password" required>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <input type="submit" class="btn btn-primary" value="Register">
        <p>Have an account?<a href = "login.php"> login</a> instead.</p>
    </div>
  </div>
          
</form>
<script src="js/app.js"></script>
    
    </body>
</html>
    
