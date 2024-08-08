<?php
// Include database configuration file
include('../Data/dbconfig.php');

// Check if tableName and playerName are set
if(isset($_POST['tableName'], $_POST['playerName'])){
    $tableName = $_POST['tableName'];
    $playerName = $_POST['playerName'];

    // Establish database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Define column names based on table name
    $columnNames = [];
    switch($tableName) {
        case 'forwardsstats':
            $columnNames = ['Goals', 'Assists', 'TouAtt3rd', 'CrsPA', 'SoT_Percentage'];
            break;
        case 'defendersstats':
            $columnNames = ['AerWon_Percentage', 'ToTkl_Percentage', 'Carries', 'Blocks', 'TklWon'];
            break;
        case 'goalkeepersstats':
            $columnNames = ['PasLonCmp_Percentage', 'PasAtt', 'PasMedCmp_Percentage', 'Carries', 'AerWon_Percentage'];
            break;
        case 'fullbacksstats':
            $columnNames = ['PPA', 'CrsPA', 'Clr', 'PasTotCmp_Percentage', 'SCA'];
            break;
        case 'playmakersstats':
            $columnNames = ['PPA', 'CrsPA', 'SCA', 'PasAss', 'GCA'];
            break;
        case 'deepmakersstats':
            $columnNames = ['PasMedCmp_Percentage', 'PasLonCmp_Percentage', 'CarPrgDist', 'PasTotCmp_Percentage', 'PasAss'];
            break;
        case 'attackingfieldersstats':
            $columnNames = ['Assists', 'GCA' , 'Goals', 'SCA', 'PasAss'];
            break;
        case 'wingbacksstats':
            $columnNames = ['PPA', 'CrsPa', 'Assists', 'PasBlocks', 'CarPrgDist'];
            break;
        case 'midfieldwingersstats':
            $columnNames = ['Goals', 'PPA', 'CrsPA', 'Assists', 'TouAtt3rd'];
            break;
        case 'midfieldersstats':
            $columnNames = ['Interception', 'Clr', 'CarPrgDist', 'PasTotCmp_Percentage', 'PasMedCmp_Percentage'];
            break;
        // Add cases for other positions as needed
        default:
            die("Invalid table name.");
    }

    // Database query to retrieve player's table
    $sql = "SELECT *, row_number FROM $tableName";
  
    $test = "SELECT row_number FROM $tableName WHERE player = '$playerName'";
    
    // Execute the query and fetch results
    $result = $conn->query($sql);
    $result2= $conn->query($test);
   


    // Check if any result is found
    if ($result->num_rows > 0) {

        $row2 = $result2->fetch_assoc();
        $rowNumberString = $row2['row_number'];
        // Output the row number string
        echo $rowNumberString;
        // Calculate the number of rows representing the top 20%
        $totalRows = $result->num_rows;
        $top20Percent = ceil($totalRows * 0.2); // Initialize $top20Percent
        
        if ($rowNumberString<= $top20Percent) {
            echo 'Player is already good at their role.';
        }
        else{
            echo '<table>';
            // Output table headers
            echo '<tr><th>Player</th><th>Squad</th><th>Pos</th><th>Age</th>';
            foreach ($columnNames as $columnName) {
                echo '<th>'.$columnName.'</th>';
            }
            echo '<th>'.ucfirst(substr($tableName, 0, -6)).'Value</th></tr>'; // Convert table name to singular and add ' Value'

            // Reset the result pointer to start from the beginning
            $result->data_seek(0);

            // Loop through each row, limiting to the first 5 rows
            $rowCount = 0;
            while($row = $result->fetch_assoc()) {
                if($rowCount >= 5) break; // Exit loop after 5 rows
                // Output table rows
                echo '<tr>';
                echo '<td>'.$row["Player"].'</td>';
                echo '<td>'.$row["Squad"].'</td>';
                echo '<td>'.$row["Pos"].'</td>';
                echo '<td>'.$row["Age"].'</td>';
                foreach ($columnNames as $columnName) {
                    echo '<td>'.$row[$columnName].'</td>';
                }
                echo '<td>'.$row[ucfirst(substr($tableName, 0, -6)).'Value'].'</td>'; // Convert table name to singular and add 'Value'
                echo '</tr>';
                $rowCount++;
            }
            echo '</table>';

            
        }
     }

    

    // Close connection
    $conn->close();
}

?>