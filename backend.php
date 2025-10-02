<?php
//MARK
header("Content-Type: application/json");
$input = json_decode(file_get_contents("php://input"), true);
$keyword = $input['keyword'];
$params = $input['params'];
//MARK

// Database config
$host = "localhost";
$db = "";
$user = "";
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
        // case 'GetItems':
        //     $query = 'SELECT * FROM items';
        //     $result = Get($query, $params, $pdo);
        //     echo json_encode($result);
        //     break;

        // case 'InsertItems':
        //     $query = "INSERT INTO items (item, qty) VALUES (:item, :qty)";
        //     $result = Set($query, $params, $pdo);

        //     echo json_encode($result);
        //     break;



        default:
            $response = [
                "status" => 'Failed',
                "message" => 'No Keyword Found',
                "keyword" => $keyword
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


















//MARK
function Get($query, $params = [], $pdo)
{
    try {
        $stmt = $pdo->prepare($query);

        // If params are passed, bind them, otherwise just execute
        $success = !empty($params) ? $stmt->execute($params) : $stmt->execute();

        if ($success) {
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                "status" => "Success",
                "data" => $rows,
                "rowCount" => count($rows)
            ];
        } else {
            return [
                "status" => "Failed",
                "data" => [],
                "rowCount" => 0
            ];
        }
    } catch (PDOException $e) {
        return [
            "status" => "Error",
            "message" => $e->getMessage()
        ];
    }
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