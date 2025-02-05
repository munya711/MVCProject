<?php
require_once 'classes/event.php';
require_once 'classes/database.php';
$session = new session;
$session->start();
if(isset($_SESSION['user'])) {
     $user = $session->get("user"); // Retrieve the user's name from session
} else {
    // If the session variable isn't set, redirect the user to the login page
    $_SESSION['message'] = "Please Login.";
    header('Location: login.php');
    exit();
}
$db = new Database();
$events = new event($db);
//Retrieving Events for the particular user[id] which they created
$results = $events->read($user['id']);


//handeling requests to delete specif event from events 
 if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitBtn'])) {
    $eventId = trim(filter_var($_POST['eventId'], FILTER_SANITIZE_STRING));
    
    //makng sure u can't delete an event that does not exist
    if (!empty($eventId)) {
        $events->delete($eventId);
    } else {
        echo "Event ID is missing or invalid." . $eventId;
    }
    return;
    //handeling requests to edit event 
        }elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitEdit'])) {
            $Eid = trim(filter_var($_POST['Eid'], FILTER_SANITIZE_STRING));
            $eventName = trim(filter_var($_POST['eventName'], FILTER_SANITIZE_STRING));
            $description = trim(filter_var($_POST['description'],  FILTER_SANITIZE_STRING));
            $DateandTime = trim(filter_var($_POST['DateandTime'], FILTER_SANITIZE_STRING));
            $Location = trim(filter_var($_POST['Location'], FILTER_SANITIZE_STRING));
            if (!empty($Eid)){
                $events->edit($Eid, $eventName, $description, $DateandTime, $Location);
            } else {
                echo "Event ID is missing or invalid." . $eventId;
            }        
        //handeling requests to create a new event
    }elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eventName = trim(filter_var($_POST['eventName'], FILTER_SANITIZE_STRING));
    $description = trim(filter_var($_POST['description'],  FILTER_SANITIZE_STRING));
    $DateandTime = trim(filter_var($_POST['DateandTime'], FILTER_SANITIZE_STRING));
    $Location = trim(filter_var($_POST['Location'], FILTER_SANITIZE_STRING));
    $Uid = $user['id'];

    $sql = "INSERT INTO eventstable (id, eventName, description, DateandTime, Location) 
                VALUES ('$Uid','$eventName', '$description', '$DateandTime', '$Location')";
    
      if ($events->create($Uid, $eventName, $description, $DateandTime, $Location)) {
        header('Location: events.php');
        exit;
    } else { 
        ?>
           <script type="text/javascript">
                alert("<?php echo $_SESSION['registerError']; ?>");//event error
           </script>
               <?php 
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Event Management</title>
    <link rel="stylesheet" href="classes/home.css"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <header>
        <div class="container">
            <h1>EventManagement</h1>
         <nav>
            <a href="index.php">Home</a>
            <a href="events.php">Events and Management</a>
         </nav>
            <a class="logout" href="logout.php">Logout</a>
        </div>
    </header>
     <div class="wrapper">
    <div class="myEvents">
        <div class="container">
            <h1> MY EVENTS</h1>
            <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>UserID</th>
                    <th>Event Name</th>
                    <th>Description</th>
                    <th>Date and Time</th>
                    <th>Location</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
               <!-- Checking if there are any events present -->
                <?php if ($results->rowCount() > 0): ?>
                    <?php while ($row = $results->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?php echo $row['eventId']; ?></td>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['eventName']); ?></td>
                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                            <td><?php echo htmlspecialchars($row['DateAndTime']); ?></td>
                            <td><?php echo htmlspecialchars($row['Location']); ?></td>
                            <td>
                                <button class="specilaBtn" data-eid="<?php echo $row['eventId']; ?>"
                                        data-eventname="<?php echo htmlspecialchars($row['eventName']); ?>"
                                        data-description="<?php echo htmlspecialchars($row['description']); ?>"
                                        data-dateandtime="<?php echo htmlspecialchars($row['DateAndTime']); ?>"
                                        data-location="<?php echo htmlspecialchars($row['Location']); ?>">
                                    Edit
                                </button>
                                <!-- retrieving event id to delete it when button is pressed-->
                                  <form method="POST" action="">
                                       <input type="hidden" name="eventId" value="<?php echo htmlspecialchars($row['eventId']); ?>">
                                       <button class="specilaBtn-delete" type="submit" name="submitBtn" >Delete</button>
                                  </form>
                            </td>
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
    <!-- Modal -->
    <div id="editModal" class="modal">
      <div class="modal-content">
        <span class="close">&times;</span>
        <form action="events.php" method="POST">
          <input type="hidden" name="Eid" id="modalEid">
          <label for="eventName">Event Name:</label>
          <input type="text" id="modalEventName" name="eventName" required><br>
          <label for="description">Description:</label>
          <input type="text" id="modalDescription" name="description" required><br>
          <label for="DateandTime">Date and Time:</label>
          <input type="text" id="modalDateandTime" name="DateandTime" required><br>
          <label for="Location">Location:</label>
          <input type="text" id="modalLocation" name="Location" required><br>
          <button type="submit" name="submitEdit">Update Event</button>
        </form>
      </div>
    </div>
    <div class='aya'>
         <h1>Create an event</h1>
    </div>
    
       <div class="events_container">
   
            <form class="form" method="POST" action="events.php">
                 
                <label for="name">Event Name:</label>
                <input type="text" id="eventName" name="eventName" required><br>

                <label for="surname">Description:</label>
                <input type="text" id="description" name="description" required><br>

                <label for="email">Date and Time</label>
                <input type="datetime-local" id="DateandTime" name="DateandTime" required><br>

                <label for="address">Location:</label>
                <input type="text" id="Location" name="Location" required><br><br>

                <button type="submit">Add Event</button>
            </form>
          
        </div>

    
    <script>
        // Get the modal
    var modal = document.getElementById('editModal');

    // Get the button that opens the modal
    var btns = document.querySelectorAll('.specilaBtn');

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks the button, open the modal
    btns.forEach(btn => {
      btn.onclick = function() {
        let row = btn.closest("tr");
        modal.style.display = "block";
        document.getElementById('modalEid').value = row.cells[0].innerText;
        document.getElementById('modalEventName').value = row.cells[2].innerText;
        document.getElementById('modalDescription').value = row.cells[3].innerText;
        document.getElementById('modalDateandTime').value = row.cells[4].innerText;
        document.getElementById('modalLocation').value = row.cells[5].innerText;
      };
    });

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
      modal.style.display = "none";
    };

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
      if (event.target == modal) {
        modal.style.display = "none";
      }
    };


    </script>
     </div>
        <footer>
        <p>Event Management</p>
        <a href="index.php">Home</a>
        <a href="events.php">Events</a>
    </footer>
    </body>
</html>
