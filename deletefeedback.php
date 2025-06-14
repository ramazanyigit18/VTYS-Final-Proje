<?php
require_once('connection.php');

if (isset($_GET['delete'])) {
    $fed_id = $_GET['delete'];

    $stmt = $con->prepare("CALL DeleteFeedback(?)");
    $stmt->bind_param("i", $fed_id);

    if ($stmt->execute()) {
        header("Location: admindash.php");
        exit();
    } else {
        echo "Silme işlemi başarısız: " . $stmt->error;
    }
}
?>
