<?php
// Connect to your database
include('../Data/dbconfig.php');

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve player name from AJAX request
$playerName = $_POST['playerName'];

// Prepare and execute SQL query to retrieve player data
$stmt = $conn->prepare("SELECT Goals, Assists, TouAtt3rd, CrsPA, SoT_Percentage FROM forwardsstats WHERE Player = ?");
$stmt->bind_param("s", $playerName);
$stmt->execute();
$result = $stmt->get_result();

// Fetch player data
if ($result->num_rows > 0) {
    $playerData = $result->fetch_assoc();
    // Convert player data to JSON format and send it as response
    echo json_encode($playerData);
} else {
    // If player data not found, return empty JSON object
    echo json_encode((object)array());
}

// Close connections
$stmt->close();
$conn->close();
?>
