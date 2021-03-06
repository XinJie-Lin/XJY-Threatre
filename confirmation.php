<?php
    session_start();
    $id=session_id();

$customerName=$_POST['customerName'];
$customerEmail=$_POST['customerEmail'];
$customerNumber=$_POST['customerNumber'];
$customerAddress=$_POST['customerAddress'];

$confirmSlot = $_SESSION['selectedSlot'];
$confirmSeats = $_SESSION['selectedSeats'];
$confirmSeatsArray = explode(",", $confirmSeats);

@ $db = new mysqli('localhost','f32ee','f32ee','f32ee');
if(mysqli_connect_errno()){
    echo 'Error: Could not connect to database.  Please try again later.';
    exit;
}

$query = "INSERT INTO transTable values
    (null, '$customerName', '$customerEmail', $customerNumber, '$customerAddress', $confirmSlot, '$confirmSeats')";
$result = $db -> query($query);

// insert query results
if ($result) {
    // Get the last index in the log to be assigned the order_id, push order into orders-details
    $queryLastIndex = "SELECT MAX( transID ) FROM `transTable`;";
    // echo $queryLastIndex;
    $lastIndex = $db->query($queryLastIndex);
    $row = $lastIndex->fetch_assoc();
    $lastIndex = $row['MAX( transID )'];
    // echo ( $lastIndex);
} 
else {
echo "An error has occurred.  The item was not added.";
}

$queryMovie = "SELECT movieName FROM movieTable INNER JOIN slotTable ON movieTable.movieID = slotTable.movieID WHERE slotID=$confirmSlot";
$resultMovie = $db -> query($queryMovie);
$rowNumMovie = $resultMovie->num_rows;
$array = array();
    for ($i=0; $i < $rowNumMovie; $i++){
        $rowMovie = $resultMovie -> fetch_assoc();
        array_push($array,$rowMovie);
    }
// print_r($rowMovie);
$confirmMovie = $rowMovie['movieName'];
// echo $confirmMovie;

$queryDate = "SELECT day, time FROM slotTable WHERE slotID=$confirmSlot";
$resultDate = $db -> query($queryDate);
$rowNumDate = $resultDate -> num_rows;
$arrayDate = array();
for ($i=0; $i < $rowNumDate; $i++){
    $rowDate = $resultDate -> fetch_assoc();
    array_push($arrayDate,$rowDate);
}
// print_r($arrayDate);
$confirmDay = $arrayDate[0]['day'];
$confirmTime = $arrayDate[0]['time'];
// echo $confirmDay;
// echo $confirmTime;



foreach ($confirmSeatsArray as $key => $value) {
    $queryUpdate = "UPDATE seatsTable SET availability='No' WHERE slotID=$confirmSlot AND seatNumber=\"$confirmSeatsArray[$key]\"";
    $resultUpdate = $db -> query($queryUpdate);
}
$to      = 'f32ee@localhost';
    $subject = 'Order Confirmed!';
    $message = "Thank you for choosing XJY Threatre. Please show this E-Ticket to our staff on the day of the movie.
    \nName: {$customerName} \nMovie Name: {$confirmMovie} \n Day: {$confirmDay} \n Time: {$confirmTime} \n Seat Number: {$confirmSeats}
    \nWe look forward to seeing you.";
    $headers = 'From: f32ee@localhost' . "\r\n" .
        'Reply-To: f32ee@localhost' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers,'-ff32ee@localhost');
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>XJY Theatre</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div id="wrapper">
        <header>
            <div id="title">
                <div id="leftheader">
                    <div id="logo">
                        <a href="index.html"><img id="home" src="assets/logo.png" alt="home" height="50px" ;>
                    </div>
                </div>

                <div id="rightheader">
                    <div id="nav">
                        <a href="movies.html">Showing Now / Coming Soon</a>
                        <a href="tickets.php">Buy Tickets</a>
                        <a href="locate.html">Locate / Contact Us</a>
                    </div>
                </div>
            </div>
        </header>

        <div id="body4">
            <h5>Confirmation</h5>
            <p>Thank you for booking with us! The E-tickets have been sent to your email address! We look forward to
                seeing you soon!</p>
        </div>
        <footer>
            <div id="copyright">
                <small><i>Copyright &copy; XJY Theatre</i></small><br>
                <small><i><a href="mailto:XinJie@Lin.com">XJYTheatre@XJY.com</a></i></small>
            </div>
            <div id="cc">
                <img src="assets/ccicons.png" height="20px" ;>
            </div>
        </footer>
    </div>
</body>

</html>