<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $due_date = $_POST['due_date'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO tasks (title, description, category, due_date, user_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $title, $description, $category, $due_date, $user_id);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        alert("Your Task Is Updated");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
