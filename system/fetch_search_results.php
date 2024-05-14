<?php
include 'db_connection.php';

if(isset($_GET['from']) && isset($_GET['to']) && isset($_GET['date'])) {
    $from = htmlspecialchars($_GET['from']);
    $to = htmlspecialchars($_GET['to']);
    $date = htmlspecialchars($_GET['date']);

    // Debugging: Output received parameters
    echo "From: $from, To: $to, Date: $date<br>";

    try {
        $sql = "SELECT * FROM Route WHERE FromLocation = :from AND Destination = :to AND DepartureDate = :date";
        // Debugging: Output SQL query
        echo "SQL Query: $sql<br>";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['from' => $from, 'to' => $to, 'date' => $date]);
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Debugging: Output fetched results
        var_dump($results);

        header('Content-Type: application/json');
        echo json_encode($results);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Error: Missing parameters";
}
?>
