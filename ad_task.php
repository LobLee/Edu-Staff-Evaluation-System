<?php
include("connection.php");

class Task {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }
    

    public function getTasks() {
        $query = "SELECT * FROM eva_task";
        $result = $this->conn->query($query);

        if ($result->num_rows > 0) {
            $tasks = [];
            while ($row = $result->fetch_assoc()) {
                $tasks[] = $row;
            }
            return $tasks;
        } else {
            return [];
        }
    }

    public function addTask($type, $due_date, $assigned_to, $status) {
        $type = $this->conn->real_escape_string($type);
        $due_date = $this->conn->real_escape_string($due_date);
        $assigned_to = $this->conn->real_escape_string($assigned_to);
        $status = $this->conn->real_escape_string($status);

        $query = "INSERT INTO eva_task(task, due_date, assigned_to,status) VALUES ('$type', '$due_date', '$assigned_to', '$status')";
        $result = $this->conn->query($query);

        return $result;
    }

    // Inside the Task class
    public function getTaskDetails($taskId) {
    $taskId = $this->conn->real_escape_string($taskId);
    $query = "SELECT * FROM eva_task WHERE id = $taskId";
    $result = $this->conn->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }

}
public function deleteTask($taskId) {
    $taskId = $this->conn->real_escape_string($taskId);
    $query = "DELETE FROM eva_task WHERE id = $taskId";
    $result = $this->conn->query($query);

    return $result;
}

public function editTask($taskId, $newValues) {
    // Implement the logic for updating task details
}
}

$task = new Task($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_id'])) {
        // Form submitted for deleting task
        $deleteId = $_POST['delete_id'];

        $deleteResult = $task->deleteTask($deleteId);

        if ($deleteResult) {
            echo '<script>
                $(document).ready(function(){
                    $("#deleteSuccessToast").toast("show");
                });
            </script>';
        } else {
            echo "Error deleting task: " . $conn->error;
        }
    } elseif (isset($_POST['edit_id'])) {
        // Form submitted for editing task
        $editId = $_POST['edit_id'];
        $newValues = $_POST['new_values']; // You need to adjust this based on your form fields

        $editResult = $task->editTask($editId, $newValues);

        if ($editResult) {
            // Use JavaScript to show the edit success toast
            echo '<script>
                $(document).ready(function(){
                    $("#editSuccessToast").toast("show");
                });
            </script>';
        } else {
            echo "Error editing task: " . $conn->error;
        }
    }
}
$tasks = $task->getTasks();
?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <?php include("Include/ad_sidebar.php");?>

    <title>Edu Staff Evaluation System</title>
    <link rel="stylesheet" href="Assets/css/eva_task.css">
</head>
<body>
    <div class="Container">
    <div class="header">
        <header>Task List</header>
        
            <!-- Add New Task Button -->
            <button class="btn btn-primary add-task-btn" data-toggle="modal" data-target="#addTaskModal">Add New Task</button>

            <!-- Search Bar -->
            <input type="text" placeholder="Search..." class="form-control search-bar">
        
<table class="table table-bordered">
<thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Task</th>
        <th scope="col">Due Date</th>
        <th scope="col">Assigned To</th>
        <th scope="col">Status</th>
        <th scope="col">Actions</th>
    </tr>
</thead>
<tbody>
<?php if (!empty($tasks)) : ?>
<?php foreach ($tasks as $index => $task) : ?>
        <tr>
            <th scope="row"><?= $index + 1 ?></th>
            <td><?= $task['task'] ?></td>
            <td><?= $task['due_date'] ?></td>
            <td><?= $task['assigned_to'] ?></td>
            <td><?= $task['status'] ?></td>
            <td>
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Actions
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="" data-toggle="modal" data-target="#viewTaskModal<?= $index ?>">View Task</a>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#editTaskModal<?= $index ?>">Edit</a>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#deleteTaskModal<?= $index ?>">Delete</a>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#viewProgressModal<?= $index ?>">View Progress</a>
            </div>
        </div>
    </td>

    <!-- Edit Task Modal -->
    <div class="modal fade" id="editTaskModal<?= $index ?>" tabindex="-1" role="dialog" aria-labelledby="editTaskModalLabel<?= $index ?>" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTaskModalLabel<?= $index ?>">Edit Task</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Your edit task modal content goes here -->
                    Edit details for Task #<?= $index + 1 ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <!-- Add Save Changes button or form for editing -->
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Task Modal -->
    <div class="modal fade" id="deleteTaskModal<?= $index ?>" tabindex="-1" role="dialog" aria-labelledby="deleteTaskModalLabel<?= $index ?>" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteTaskModalLabel<?= $index ?>">Delete Task</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete Task #<?= $index + 1 ?>?
                </div>
                <div class="modal-footer">
                    <form method="post" action="ad_task.php">
                        <input type="hidden" name="delete_id" value="<?= $task['id'] ?>">
                        <button type="submit" class="btn btn-danger">Delete</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


        <!-- Add New Task Modal -->
            <div class="modal fade" id="addTaskModal" tabindex="-1" role="dialog" aria-labelledby="addTaskModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addTaskModalLabel">Add New Task</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Your task form content goes here -->
                            <form id="addTaskForm" action="ad_task.php" method="post">
                                <div class="form-group">
                                    <label for="task">Task:</label>
                                    <input type="text" class="form-control" name="task" required>
                                </div>
                                <div class="form-group">
                                    <label for="due_date">Due Date:</label>
                                    <input type="date" class="form-control" name="due_date" required>
                                </div>
                                <div class="form-group">
                                    <label for="assigned_to">Assigned To:</label>
                                    <input type="text" class="form-control" name="assigned_to" required>
                                </div>

                                <div class="form-group">
                                    <label for="status">Status:</label>
                                    <select class="form-control" name="status" required>
                                        <option value="Pending">Pending</option>
                                        <option value="Complete">Complete</option>
                                    </select>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Add Task</button>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>


                                        <!-- View Task Modal -->
            <div class="modal fade" id="viewTaskModal<?= $index ?>" tabindex="-1" role="dialog" aria-labelledby="viewTaskModalLabel<?= $index ?>" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="viewTaskModalLabel<?= $index ?>">View Task</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" id="modal-body-content">
                            <!-- Display task details here -->
                            Task details for Task #<?= $index + 1 ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <!-- Add Edit and Delete buttons here -->
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editTaskModal<?= $index ?>">Edit</button>
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteTaskModal<?= $index ?>">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
<!-- View Progress Modal -->
<div class="modal fade" id="viewProgressModal<?= $index ?>" tabindex="-1" role="dialog" aria-labelledby="viewProgressModalLabel<?= $index ?>" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="viewProgressModalLabel<?= $index ?>">View Progress</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Add content to display progress details here -->
                                        Progress details for Task #<?= $index + 1 ?>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
          
    <?php endforeach; ?>
</tbody>
</table>
<?php else : ?>
<tr>
<td colspan="5">No tasks found..</td>
</tr>       
<?php endif; ?>
</div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script>
// Reset the form when the modal is closed
            $('#addTaskModal').on('hidden.bs.modal', function () {
                $('#addTaskForm')[0].reset();
            });
        </script>
<script>
    $(document).ready(function () {
        $('#deleteSuccessToast, #editSuccessToast').toast({
            autohide: true,
            delay: 2000
        }).toast('show');
    });
</script>
</body>
</html>
<?php
//close the database
    mysqli_close($conn);
?>