<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_id = $_POST['task_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $status = $_POST['status'];
    $due_date = $_POST['due_date'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("UPDATE tasks SET title = ?, description = ?, category = ?, status = ?, due_date = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ssssssi", $title, $description, $category, $status, $due_date, $task_id, $user_id);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>
