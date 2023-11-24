<?php 
include("connection.php"); ?>

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
<header>User List</header>

<!-- Add New User Button -->
<button class="btn btn-primary add-task-btn" data-toggle="modal" data-target="#addStaffModal">Add New User</button>

<!-- Search Bar -->
<input type="text" placeholder="Search..." class="form-control search-bar">
<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($userList)) : ?>
                <?php foreach ($userList as $index => $user) : ?>
                    <tr>
                        <th scope="row"><?= $index + 1 ?></th>
                        <td><?= $user['first_name'] . ' ' . $user['last_name'] ?></td>
                        <td><?= $user['email'] ?></td>
                        <td>
                            <div class="btn-group" role="group" aria-label="User Actions">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#viewUserModal<?= $index ?>">View</button>
                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#editUserModal<?= $index ?>">Edit</button>
                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteUserModal<?= $index ?>">Delete</button>
                            </div>
                        </td>
                    </tr>

                    <!-- Edit User Modal -->
<div class="modal fade" id="editUserModal<?= $index ?>" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel<?= $index ?>" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel<?= $index ?>">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Your edit user modal content goes here -->
                <form id="editUserForm<?= $index ?>" action="process_edit_user.php" method="post">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="edit_first_name">First Name:</label>
                            <input type="text" class="form-control" name="edit_first_name" value="<?= $user['first_name'] ?>" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="edit_middle_name">Middle Name:</label>
                            <input type="text" class="form-control" name="edit_middle_name" value="<?= $user['middle_name'] ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="edit_last_name">Last Name:</label>
                            <input type="text" class="form-control" name="edit_last_name" value="<?= $user['last_name'] ?>" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="edit_avatar">Avatar:</label>
                            <input type="file" class="form-control-file" name="edit_avatar">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_email">Email:</label>
                        <input type="email" class="form-control" name="edit_email" value="<?= $user['email'] ?>" required>
                    </div>

                    <!-- Add other fields you want to edit -->

                    <div class="modal-footer">
                        <input type="hidden" name="edit_id" value="<?= $user['id'] ?>">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete User Modal -->
<div class="modal fade" id="deleteUserModal<?= $index ?>" tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel<?= $index ?>" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteUserModalLabel<?= $index ?>">Delete User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete User #<?= $index + 1 ?>?
            </div>
            <div class="modal-footer">
                <form action="process_delete_user.php" method="post">
                    <input type="hidden" name="delete_id" value="<?= $user['id'] ?>">
                    <button type="submit" class="btn btn-danger">Delete</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>

        <!-- Add New User Modal -->
        <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Your user form content goes here -->
                        <form id="addUserForm" action="ad_user_info.php" method="post" enctype="multipart/form-data">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="first_name">First Name:</label>
                                    <input type="text" class="form-control" name="first_name" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="middle_name">Middle Name:</label>
                                    <input type="text" class="form-control" name="middle_name">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="last_name">Last Name:</label>
                                    <input type="text" class="form-control" name="last_name" required>
                                </div>
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
                                <div class="form-group col-md-6">
                                    <label for="password">Password:</label>
                                    <input type="password" class="form-control" name="password" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="confirm_password">Confirm Password:</label>
                                    <input type="password" class="form-control" name="confirm_password" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- View User Modal -->
<div class="modal fade" id="viewUserModal<?= $index ?>" tabindex="-1" role="dialog" aria-labelledby="viewUserModalLabel<?= $index ?>" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewUserModalLabel<?= $index ?>">View User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Display user details here -->
                <p><strong>Name:</strong> <?= $user['first_name'] . ' ' . $user['last_name'] ?></p>
                <p><strong>Email:</strong> <?= $user['email'] ?></p>
                <!-- Add other fields you want to display -->

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <!-- Add Edit and Delete buttons here -->
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editUserModal<?= $index ?>">Edit</button>
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteUserModal<?= $index ?>">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>
   <!-- Add other modals (Edit and Delete) here -->
   <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <th colspan="4">No users found</th>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <!-- Your additional scripts go here -->

</body>
</html>

<?php
// Close the database
mysqli_close($conn);
?>
