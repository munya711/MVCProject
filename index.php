<?php
require_once 'classes/database.php';
require_once 'classes/event.php';
$session = new session;
$session->start();
if($session->isLoggedIN('user')) {
    $user = $session->get("user"); // Retrieve the user's name from session
} else {
    $session->set("message", "Please Login");
    // If the session variable isn't set, redirect the user to the login page
    header('Location: login.php');
    exit();
}
$db = new Database();
$event = new event($db);
//Retrieving All Events created
$events = $event->getAllEvents();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management</title>
    <link rel="stylesheet" href="classes/home.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="wrapper">
        <header>
            <div class="container">
                <h1>Event Management site</h1>
                <nav>
                    <a href="index.php">Home</a>
                    <a href="events.php">Events and Management</a>
                </nav>
                <a class="logout" href="logout.php">Logout</a>
            </div>
        </header>
           <div class="intro-text">
            <div class="container">
                <h2>Welcome to our Event Management Site</h2>
                <p>Plan, manage, and attend events effortlessly. <a href="events.php">Check out our events</a></p>
            </div>
        </div>
        <div class="Upcoming_Events">
            <div class="container">
                <h1>EVENTS ABOUT TO START SOON</h1>
                <br><!-- comment -->
                <div class="container">
            <table class="table table-hover">
            <thead>
                <tr>
                   
                    <th>Event Name</th>
                    <th>Description</th>
                    <th>Date and Time</th>
                    <th>Location</th>
               
                </tr>
            </thead>
            <tbody>
               <!-- Checking if there are any events present -->
                <?php if ($events->rowCount() > 0): ?>
                    <?php while ($row = $events->fetch(PDO::FETCH_ASSOC)): ?>
                        <trx>
                            
                            <td><?php echo htmlspecialchars($row['eventName']); ?></td>
                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                            <td><?php echo htmlspecialchars($row['DateAndTime']); ?></td>
                            <td><?php echo htmlspecialchars($row['Location']); ?></td>
                            
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9">No records found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        </div>        
    </div>
            </div>
        </div>
    <footer>
        <p>Event Management</p>
        <a href="index.php">Home</a>
        <a href="events.php">Events</a>
    </footer>
</body>
</html>
