<?php
include("connection.php");

class Staff {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getStaffList() {
        $query = "SELECT * FROM ad_staff";
        $result = $this->conn->query($query);

        if ($result->num_rows > 0) {
            $staffList = [];
            while ($row = $result->fetch_assoc()) {
                $staffList[] = $row;
            }
            return $staffList;
        } else {
            return [];
        }
    }

    public function addStaff($name, $email, $department) {
        $name = $this->conn->real_escape_string($name);
        $email = $this->conn->real_escape_string($email);
        $department = $this->conn->real_escape_string($department);

        $query = "INSERT INTO ad_staff(name, email, department) VALUES ('$name', '$email', '$department')";
        $result = $this->conn->query($query);

        return $result;
    }

    public function getStaffDetails($staffId) {
        $staffId = $this->conn->real_escape_string($staffId);
        $query = "SELECT * FROM ad_staff WHERE id = $staffId";
        $result = $this->conn->query($query);

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }

    public function deleteStaff($staffId) {
        $staffId = $this->conn->real_escape_string($staffId);
        $query = "DELETE FROM ad_staff WHERE id = $staffId";
        $result = $this->conn->query($query);

        return $result;
    }

    public function editStaff($staffId, $newValues) {
        // Implement the logic for updating staff details
    }
}

$staff = new Staff($conn);

// Check if the form is submitted for adding or deleting
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_staff'])) {
    // Form submitted for adding staff
    $name = $_POST['name'];
    $email = $_POST['email'];
    $department = $_POST['department'];

    // Call the addStaff method
    $result = $staff->addStaff($name, $email, $department);

    if ($result) {
        // Data added successfully, redirect or show a success message
        header("Location: your_staff_page.php?success=1");
        exit();
    } else {
        // Log the error for reference
        error_log("Error adding staff: " . $conn->error);
        // Display a user-friendly message
        $error_message = "Error adding staff. Please try again later.";
    }
} elseif (isset($_POST['delete_id'])) {
    // Form submitted for deleting staff
    $deleteId = $_POST['delete_id'];

    $deleteResult = $staff->deleteStaff($deleteId);

    if ($deleteResult) {
        // Use JavaScript to show the delete success toast
        // Data added successfully, redirect or show a success message
        header("Location: your_staff_page.php?success=1");
        exit();
    } else {
        // Log the error for reference
        error_log("Error deleting staff: " . $conn->error);
        // Display a user-friendly message
        $error_message = "Error deleting staff. Please try again later.";
    }
}

$staffList = $staff->getStaffList();
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
        <header>Staff List</header>

        <!-- Add New Staff Button -->
        <button class="btn btn-primary add-task-btn" data-toggle="modal" data-target="#addStaffModal">Add New Staff</button>

        <!-- Search Bar -->
        <input type="text" placeholder="Search..." class="form-control search-bar">


        <table class="table table-bordered">
        <thead>

        <tr>
        <th scope="col">#</th>
        <th scope="col">Name</th>
        <th scope="col">Email</th>
        <th scope="col">Department</th>
        <th scope="col">Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($staffList)) : ?>
        <?php foreach ($staffList as $index => $staff) : ?>
        <tr>
        <th scope="row"><?= $index + 1 ?></th>
        <td><?= $staff['name'] ?></td>
        <td><?= $staff['email'] ?></td>
        <td><?= $staff['department'] ?></td>
        <td>
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Actions
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#viewStaffModal<?= $index ?>">View Staff</a>
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#editStaffModal<?= $index ?>">Edit</a>
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#deleteStaffModal<?= $index ?>">Delete</a>
                </div>
            </div>
        </td>
        </tr>

        <!-- Edit Staff Modal -->
        <div class="modal fade" id="editStaffModal<?= $index ?>" tabindex="-1" role="dialog" aria-labelledby="editStaffModalLabel<?= $index ?>" aria-hidden="true">
        <!-- Modal Content Goes Here -->
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editStaffModalLabel<?= $index ?>">Edit Staff</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Your edit staff modal content goes here -->
                    Edit details for Staff #<?= $index + 1 ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <!-- Add Save Changes button or form for editing -->
                </div>
            </div>
        </div>
        </div>

        <!-- Delete Staff Modal -->
        <div class="modal fade" id="deleteStaffModal<?= $index ?>" tabindex="-1" role="dialog" aria-labelledby="deleteStaffModalLabel<?= $index ?>" aria-hidden="true">
        <!-- Modal Content Goes Here -->
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteStaffModalLabel<?= $index ?>">Delete Staff</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete Staff #<?= $index + 1 ?>?
                </div>
                <div class="modal-footer">
                    <form method="post">
                        <input type="hidden" name="delete_id" value="<?= $staff['id'] ?>">
                        <button type="submit" class="btn btn-danger">Delete</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
        </div>

        <!-- Add New Staff Modal -->
        <div class="modal fade" id="addStaffModal" tabindex="-1" role="dialog" aria-labelledby="addStaffModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addStaffModalLabel">Add New Staff</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Your staff form content goes here -->
                    <form id="addStaffForm" action="ad_staff.php" method="post" enctype="multipart/form-data">
                        <div class="form-row" >                          
                                <label for="first_name">First Name:</label>
                                <input type="text" class="form-control" name="first_name" required>                   
                        </div>
                            
                        <div class="form-row">                            
                                <label for="middle_name">Middle Name:</label>
                                <input type="text" class="form-control" name="middle_name">                            
                        </div>
                            
                        <div class="form-row">
                                <label for="last_name">Last Name:</label>
                                <input type="text" class="form-control" name="last_name" required>
                        </div>

                        <div class="form-row">
                                <label for="department">Department:</label>
                                <input type="text" class="form-control" name="department" required>
                        </div>

                        <div class="form-row">
                                <label for="evaluator">Evaluator:</label>
                                <input type="text" class="form-control" name="evaluator" required>
                            <div class="form-group col-md-6">
                                <label for="avatar">Avatar:</label>
                                <input type="file" class="form-control-file" name="avatar">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>

                        <div class="form-row">
                            
                                <label for="password">Password:</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                                <label for="confirm_password">Confirm Password:</label>
                                <input type="password" class="form-control" name="confirm_password" required>
                        </div>
                        <div class="form-group" >
                            <button type="submit" class="btn btn-primary">Save</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        </div> 
                    </form>
                </div>           
            </div>
        </div>
        </div>


        <!-- View Staff Modal -->
        <div class="modal fade" id="viewStaffModal<?= $index ?>" tabindex="-1" role="dialog" aria-labelledby="viewStaffModalLabel<?= $index ?>" aria-hidden="true">
        <!-- Modal Content Goes Here -->
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewStaffModalLabel<?= $index ?>">View Staff</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Display staff details here -->
                    Staff details for Staff #<?= $index + 1 ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <!-- Add Edit and Delete buttons here -->
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editStaffModal<?= $index ?>">Edit</button>
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteStaffModal<?= $index ?>">Delete</button>
                </div>
            </div>
    </div>
    </div>
    <?php endforeach; ?>
    </tbody>
    </table>
    <?php else : ?>
    <tr>
    <th colspan="5">No staff found</th>
    </tr>
    <?php endif; ?>
    </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    
    <script>
        // Reset the form when the modal is closed
        $('#addStaffModal').on('hidden.bs.modal', function () {
            $('#addStaffForm')[0].reset();
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
