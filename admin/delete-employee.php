<?php
require_once "../connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];
    
    // Proses penghapusan
    $sql = "DELETE FROM employee WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "error";
}
?>