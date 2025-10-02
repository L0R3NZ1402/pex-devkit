<?php
//MARK
header("Content-Type: application/json");
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : "";
//MARK
?>

<?php
// Database config
$host = "localhost";
$db = "lumaframework";
$user = "root";
$pass = "";
$charset = "utf8mb4";

// DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    // Create PDO instance
    $pdo = new PDO($dsn, $user, $pass);

    // Set error mode to Exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Example query (fetch all users)
    switch ($keyword) {
        case 'GetItems':
            $query = 'SELECT * FROM items';
            $data = Get($query, $pdo);
            $response = [
                "status" => "Success",
                "data" => $data
            ];
            echo json_encode($response);
            break;

        case 'InsertItems':
            $params = json_decode(file_get_contents("php://input"), true);
            $query = "INSERT INTO items (item, qty) VALUES (:item, :qty)";
            $result = Set($query, $params, $pdo);

            echo json_encode($result);
            break;

        default:
            $response = [
                "status" => 'Failed',
                "message" => 'No Keyword Found'
            ];
            echo json_encode($response);
            break;
    }

} catch (PDOException $e) {
    $response = [
        "status" => "Connection failed",
        "message" => $e->getMessage()
    ];
    echo json_encode($response);
}

?>

















<?php
//MARK
function Get($query, $pdo)
{
    $stmt = $pdo->query("SELECT * FROM items");
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $row;
}

function Set($query, $params = [], $pdo)
{
    try {
        $stmt = $pdo->prepare($query);

        // If params are passed, bind them, otherwise just execute
        $success = !empty($params) ? $stmt->execute($params) : $stmt->execute();

        return [
            "status" => $success ? "Success" : "Failed",
            "rowsAffected" => $stmt->rowCount()
        ];
    } catch (PDOException $e) {
        return [
            "status" => "Error",
            "message" => $e->getMessage()
        ];
    }
}
//MARK
?>