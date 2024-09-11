<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];



if (isset($_SESSION['user_id'])) {
    // Get email from session
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT email FROM users WHERE id='$user_id'"; 
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $email = $row['email'];
        ?>
        <!-- Display the email in HTML -->
        <div class="profile_text">
            <p><?php echo "Logged-in user email is: " . $email; ?></p>
        </div>
        <?php
    } else {
        echo "No email found for the user.";
    }
} else {
    echo "No user is logged in.";
}



// Fetch selected filter values
$selectedCategory = $_GET['category'] ?? '';
$selectedStatus = $_GET['status'] ?? '';

// SQL query to fetch tasks with filters
$query = "SELECT * FROM tasks WHERE user_id = ?";
$params = [$user_id];

if (!empty($selectedCategory)) {
    $query .= " AND category = ?";
    $params[] = $selectedCategory;
}

if (!empty($selectedStatus)) {
    $query .= " AND status = ?";
    $params[] = $selectedStatus;
}

$stmt = $conn->prepare($query);
$types = str_repeat("s", count($params));
$stmt->bind_param($types, ...$params);
$stmt->execute();
$tasks = $stmt->get_result();

// count the number of tasks
$taskcount=$tasks->num_rows;


// fetch  pending tasks and complete tasks and in progress tasks
$query = "SELECT COUNT(*) as total_tasks, SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_tasks, SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_tasks,SUM(CASE WHEN status = 'in Progress' THEN 1 ELSE 0 END) as inprogress_tasks 
          FROM tasks 
          WHERE user_id = ?";
$params = [$user_id];
$stmt = $conn->prepare($query);
$types = str_repeat("s", count($params));
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$totalTasks = $row['total_tasks'];
$pendingTasks = $row['pending_tasks'];
$inprogressTasks = $row['inprogress_tasks'];
$completedTasks = $row['completed_tasks'];

?>



    

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Application</title>
    <!-- ===== remix icons cdn link ===== -->
    <link
    href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css"
    rel="stylesheet"/>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style.css">
    <script src="tasks.js"></script>

    <style>
        .task-status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 5px;
            color: #fff;
        }
        .status-pending {
            background-color: #ffcc00; /* Yellow for Pending */
        }
        .status-in-progress {
            background-color: #3399ff; /* Blue for In Progress */
        }
        .status-completed {
            background-color: #66cc66; /* Green for Completed */
        }
    </style>
</head>
<body>
<div class="profile_text"><p><?php ?></p></div>
    <!-- ===== main container section start ===== -->
     <section class="maxWidth">
        <h1>Todo Application App</h1>
        

        <div class="container">
            <div class="col_1">
            <h3>Your Tasks</h3>
                
    <!-- Task Addition Form -->
    <form action="add_task.php" method="POST"class="budget_form">
        <input type="text" class="input_field budget_input" name="title" placeholder="Task Title" required>
        <input type="text" class="input_field budget_input" name="description" placeholder="Task Description">
        <select name="category"class="input_field budget_input">
            <option value="Work">Work</option>
            <option value="Personal">Personal</option>
        </select>
        <input type="date" class="input_field budget_input" name="due_date" required>
        <button type="submit" >Add Task</button>
    </form>

    </div>

<div class="col_2">
            
    <h3>Filter</h3>

    <form method="GET">
        <label for="category">Category:</label>
        <select name="category" id="category" class="input_field">
            <option value="">All</option>
            <option value="Work" <?= $selectedCategory === 'Work' ? 'selected' : '' ?>>Work</option>
            <option value="Personal" <?= $selectedCategory === 'Personal' ? 'selected' : '' ?>>Personal</option>
        </select>

        <label for="status">Status:</label>
        <select name="status" id="status"class="input_field">
            <option value="">All</option>
            <option value="Pending" <?= $selectedStatus === 'Pending' ? 'selected' : '' ?>>Pending</option>
            <option value="In Progress" <?= $selectedStatus === 'In Progress' ? 'selected' : '' ?>>In Progress</option>
            <option value="Completed" <?= $selectedStatus === 'Completed' ? 'selected' : '' ?>>Completed</option>
        </select>

        <button type="submit">Filter</button>
    </form>   
     </div>

        </div>

        <div class="container">
            <div class="total_budget">
            <p>Total Tasks</p>
            
 
                <span > <?php  echo "" .$taskcount?></span>
            
            </div>


            <div class="total_expense">
            <p>Pending Tasks</p>
          
                <span class="total_expense_child"> <?php  echo "" .$pendingTasks?></span>
   
           
            </div>


            <div class="total_balance">
            <p>In Progress Tasks</p> 
        
                <span class="total_balance_child">  <?php  echo "".$inprogressTasks?></span>
           
          
            </div>


            <div class="total_balance">
           <p>Completed Tasks</p>
                <span class="total_balance_child">  <?php  echo "" .$completedTasks ?></span>
            
          
            </div>
        </div>

       



         <!-- Display Tasks -->
        <div class="container">
            <div class="list">
                <h3>Task List</h3>

                <div class="parent_list">
                    <div class="list_container">
                      
        <?php while($task = $tasks->fetch_assoc()): ?>
            <div class="product" data-id="<?= $task['id'] ?>">
                <h3 class="f_box"><?= $task['title'] ?></h3>

                <span class="task-status <?= 'status-' . strtolower(str_replace(' ', '-', $task['status'])) ?>">
                    <?= $task['status'] ?>
                </span>
                <p class="f_box"><?= $task['description'] ?></p>

              
                
                <select class="status-select  " data-id="<?= $task['id'] ?>">
                    <option value="Pending" <?= $task['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="In Progress" <?= $task['status'] === 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                    <option value="Completed" <?= $task['status'] === 'Completed' ? 'selected' : '' ?>>Completed</option>
                </select>

                <button class="edit-btn ">Edit</button>
                <button class="delete-btn  " onclick="deleteTask(<?= $task['id'] ?>)">Delete</button>
            </div>
        <?php endwhile; ?>
    </div>
                            



                        </div>
                    </div>
                </div>

            </div>
        </div>


     </section>











      <!-- Edit Task Modal -->
    <div id="edit-task-modal" style="display: none;">
        <div class="modal-content">
            <form id="edit-task-form">
                <input type="hidden" id="task-id" name="task_id">
                <label>Title</label>
                <input type="text" id="edit-title" name="title" required>
                <label>Description</label>
                <textarea id="edit-description" name="description"></textarea>
                <label>Category</label>
                <select id="edit-category" name="category">
                    <option value="Work">Work</option>
                    <option value="Personal">Personal</option>
                </select>
                <label>Status</label>
                <select id="edit-status" name="status">
                    <option value="Pending">Pending</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Completed">Completed</option>
                </select>
                <label>Due Date</label>
                <input type="date" id="edit-due-date" name="due_date">
                <button type="submit">Save</button>
                <button type="button" id="close-modal">Cancel</button>
            </form>
        </div>
    </div>
   <button class="logout_btn"><a href="logout.php">Logout</a></button>
    <!-- ===== main container section end ===== -->










    <!-- <script src="script.js"></script> -->
</body>
</html>





