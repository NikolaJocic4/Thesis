<?php
// Include database configuration file
include('../Data/dbconfig.php');

// Check if squad is set
if(isset($_POST['squad'])){
    $squad = $_POST['squad'];
    
    // Establish database connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Database query to retrieve players from a particular team and their position
    $sql = "SELECT Player, Pos FROM Players WHERE Squad = '$squad' ORDER BY Min90s DESC LIMIT 11";
    
    // Execute the query and fetch results
    $result = $conn->query($sql);
    
    // Check if any result is found
    if ($result->num_rows > 0) {
        // Displaying players and their positions
        while($row = $result->fetch_assoc()) {
            // Assign player name and position to variables
            $playerName = $row["Player"];
            $playerPos = $row["Pos"];
            
            // Output player button with data-player attribute
            echo '<button class="player-btn" data-player="'.$playerName.' - '.$playerPos.'">'.$playerName.' - '.$playerPos.'</button>';
        }
    } else {
        echo "No players found for the selected squad.";
    }
    
    // Close connection
    $conn->close();
}
?>
