<?php
// Establish a connection to the database
include('../Data/dbconfig.php');
// Create connection
$connection = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
// Retrieve form data sent via POST
$position = $_POST['position'];
$stat1 = $_POST['stat1'];
$stat1Value = $_POST['stat1Value'];
$stat2 = $_POST['stat2'];
$stat2Value = $_POST['stat2Value'];
$stat3 = $_POST['stat3'];
$stat3Value = $_POST['stat3Value'];

// Build the SQL query
$query = "SELECT * FROM players WHERE Pos = '$position' ORDER BY 
    ABS($stat1 - $stat1Value) +
    ABS($stat2 - $stat2Value) +
    ABS($stat3 - $stat3Value) DESC";

// Execute the query
$result = $connection->query($query);

if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        // Output data as needed
        echo $row['Player'] . ': ' . $row['Pos'] . '<br>';
    }
} else {
    echo "0 results";
}

// Close the database connection
$connection->close();
?>
