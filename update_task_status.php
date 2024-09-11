<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("HTTP/1.1 401 Unauthorized");
    exit();
}

$user_id = $_SESSION['user_id'];
$task_id = $_POST['task_id'] ?? '';
$status = $_POST['status'] ?? '';

if (!empty($task_id) && !empty($status)) {
    // Prepare SQL query to update task status
    $stmt = $conn->prepare("UPDATE tasks SET status = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("sii", $status, $task_id, $user_id);

    if ($stmt->execute()) {
        echo "Status updated successfully";
    } else {
        echo "Error updating status";
    }
    
    $stmt->close();
} else {
    header("HTTP/1.1 400 Bad Request");
}
$conn->close();
?>
