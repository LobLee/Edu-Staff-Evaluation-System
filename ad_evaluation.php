<?php
include("connection.php");


class Evaluation {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }
    

    public function getEvaluations() {
        $query = "SELECT * FROM evaluations";
        $result = $this->conn->query($query);

        if ($result->num_rows > 0) {
            $evaluations = [];
            while ($row = $result->fetch_assoc()) {
                $evaluations[] = $row;
            }
            return $evaluations;
        } else {
            return [];
        }
    }

    public function addEvaluation($task_name, $name, $evaluator,$perf_average) {
        $task_name = $this->conn->real_escape_string($task_name);
        $name = $this->conn->real_escape_string($name);
        $evaluator = $this->conn->real_escape_string($evaluator);
        $perf_average = $this->conn->real_escape_string($perf_average);

        $query = "INSERT INTO evaluations (task_name, name, evaluator, performance_average) VALUES ('$task_name', '$name','$evaluator' ,'$perf_average' )";
        $result = $this->conn->query($query);

        return $result;
    }

    public function getEvaluationDetails($evaluationId) {
        $evaluationId = $this->conn->real_escape_string($evaluationId);
        $query = "SELECT * FROM evaluations WHERE id = $evaluationId";
        $result = $this->conn->query($query);

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }

    public function deleteEvaluation($evaluationId) {
        $evaluationId = $this->conn->real_escape_string($evaluationId);
        $query = "DELETE FROM evaluations WHERE id = $evaluationId";
        $result = $this->conn->query($query);

        return $result;
    }
}
$evaluation = new Evaluation($conn);

// Check if the form is submitted for adding or deleting
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_evaluation'])) {
        // Form submitted for adding evaluation
        $task_name = $_POST['task_name'];
        $name = $_POST['name'];

        $result = $evaluation->addEvaluation($task_name, $name);

        if ($result) {
            echo "Added successfully";
        } else {
            echo "Error adding evaluation: " . $conn->error;
        }
    } elseif (isset($_POST['delete_id'])) {
        // Form submitted for deleting evaluation
        $deleteId = $_POST['delete_id'];

        $deleteResult = $evaluation->deleteEvaluation($deleteId);

        if ($deleteResult) {
             // Use JavaScript to show the delete success toast
           // Data added successfully, redirect or show a success message
           header("Location: ad_evaluation.php?success=1");
           exit();
       } else {
           // Log the error for reference
           error_log("Error adding evaluatiopn: " . $conn->error);
           // Display a user-friendly message
           $error_message = "Error adding evaluatiopn. Please try again later.";
       }
    }
}

$evaluations = $evaluation->getEvaluations();
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
    <link rel="stylesheet" href="Assets/css/ad_eval.css">
</head>
<body>
    <div class="Container">
    <div class="header">
        <header>Evaluation List</header> 
            <!-- Add New Evaluation Button -->
            <button class="btn btn-primary" data-toggle="modal" data-target="#addEvaluationModal">Add New Evaluation</button>  
            <!-- Search Bar -->
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Search..." aria-label="Search">
                
            </div> 
   
        <div class="toast" id="deleteSuccessToast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="3000" style="position: absolute; top: 0; right: 0; margin: 10px;">
                <div class="toast-header">
                    <strong class="mr-auto">Success!</strong>
                    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="toast-body">
                    Evaluation deleted successfully.
                </div>
            </div>
        </div>
        
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Task</th>
                        <th scope="col">Name</th>
                        <th scope="col">Evaluator</th>
                        <th scope="col">Performance Average</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($evaluations)) : ?>
                    <?php foreach ($evaluations as $index => $evaluation) : ?>
                        <tr>
                            <th scope="row"><?= $index + 1 ?></th>
                            <td><?= $evaluation['task_name'] ?></td>
                            <td><?= $evaluation['name'] ?></td>
                            <td><?= $evaluation['evaluator'] ?></td>
                            <td><?= $evaluation['performance_average'] ?></td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Actions
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="" data-toggle="modal" data-target="#viewEvaluationModal<?= $index ?>">View</a>
                                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#editEvaluationModal<?= $index ?>">Edit</a>
                                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#deleteEvaluationModal<?= $index ?>">Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>

                       

                        <!-- Edit -->
                        <div class="modal fade" id="editEvaluationModal<?= $index ?>" tabindex="-1" role="dialog" aria-labelledby="editEvaluationModalLabel<?= $index ?>" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editEvaluationModalLabel<?= $index ?>">Edit Evaluation</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Add content to edit evaluation details here -->
                                        Edit details for Evaluation #<?= $index + 1 ?>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                      <!-- Delete Modal -->
                        <div class="modal fade" id="deleteEvaluationModal<?= $index ?>" tabindex="-1" role="dialog" aria-labelledby="deleteEvaluationModalLabel<?= $index ?>" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteEvaluationModalLabel<?= $index ?>">Delete Evaluation</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to delete Evaluation #<?= $index + 1 ?>?
                                    </div>
                                    <div class="modal-footer">
                                        <form method="post" action="ad_evaluation.php">
                                            <input type="hidden" name="delete_id" value="<?= $evaluation['id'] ?>">
                                            <input type="hidden" name="task_name" value="<?= $evaluation['task_name'] ?>">
                                            <input type="hidden" name="name" value="<?= $evaluation['name'] ?>">
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>                       

                        <!-- View -->
                        <div class="modal fade" id="viewEvaluationModal<?= $index ?>" tabindex="-1" role="dialog" aria-labelledby="viewEvaluationModalLabel<?= $index ?>" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="viewEvaluationModalLabel<?= $index ?>">View Evaluation</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Add content to display evaluation details here -->
                                        View details for Evaluation #<?= $index + 1 ?>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="addEvaluationModal" tabindex="-1" role="dialog" aria-labelledby="addEvaluationModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addEvaluationModalLabel">Add New Evaluation</h5>
                                        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_evaluation'])) : ?>
                                    <?php if (isset($result) && $result) : ?>
                                        <!-- Show success toast -->
                                        <script>
                                            $(document).ready(function(){
                                                $('#successToast').toast('show');
                                            });
                                        </script>
                                    <?php elseif (isset($error_message)) : ?>
                                        <!-- Show error message -->
                                        <div class="alert alert-danger" role="alert">
                                            <?= $error_message ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>

                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Your evaluation form content goes here -->
                                        <form action="ad_evaluation.php" method="post">
                                            <label for="task_name">Task:</label>
                                            <input type="text" name="task_name" required>
                                            <br>

                                            <label for="name">Name:</label>
                                            <input type="text" name="name" required>
                                            <br>

                                            <label for="performance_average">Performance Average:</label>
                                            <input type="number" name="performance_average" required>
                                            <br>
                                            <button type="submit" class="btn btn-add" name="add_evaluation">Submit Evaluation</button>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
    </div>

                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <tr>
                <td colspan="4">No evaluations found.</td>
            </tr>
        <?php endif; ?>
        
   
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <script>
    $(document).ready(function(){
        $('#deleteSuccessToast').toast({
            autohide: true,
            delay: 2000
        }).toast('show');
    });
    $(document).ready(function(){
        $('#successToast').toast({
            autohide: true,
            delay: 2000
        }).toast('show');
    });
</script>
</body>
</html>

<?php
// Close the database
mysqli_close($conn);
?>
