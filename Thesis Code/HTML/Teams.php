<?php
// Include database configuration file
include('../Data/dbconfig.php');

// Check if league is set
if(isset($_POST['league'])){
    $league = $_POST['league'];
    
    // Establish database connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Database query to retrieve squads based on league
    $sql = "SELECT DISTINCT Squad FROM Players WHERE Comp = '$league'";
    
    // Execute the query and fetch results
    $result = $conn->query($sql);
    
    // Check if any result is found
    if ($result->num_rows > 0) {
        // Fetching and displaying squads as buttons
        while($row = $result->fetch_assoc()) {
            echo '<button class="player">'.$row["Squad"].'</button>';
        }
    } else {
        echo "No squads found for the selected league.";
    }
    
    // Close connection
    $conn->close();
}
?>
