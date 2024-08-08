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

    // Construct the queries
    $player1 = "SELECT " . implode(', ', $columnNames) . " FROM $tableName WHERE player = '$playerName'";
    $player2 = "SELECT " . implode(', ', $columnNames) . " FROM $tableName LIMIT 1";

    // Execute the queries
    $result1 = $conn->query($player1);
    $result2 = $conn->query($player2);


    $data = array();
if ($result1->num_rows > 0) {
    $data['result1'] = $result1->fetch_assoc();
}
if ($result2->num_rows > 0) {
    $data['result2'] = $result2->fetch_assoc();
}
echo json_encode($data);

    // Close connection
    $conn->close();
} else {
    echo "Missing parameters: tableName and/or playerName.";
}
?>
