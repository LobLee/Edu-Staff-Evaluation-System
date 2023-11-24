
<?php include("connection.php");
// Fetch user profile information from the database
// Replace this with your actual data retrieval logic
$userProfileInfo = getUserProfileInfo(); // Define a function to fetch user profile information

function getUserProfileInfo() {
    // Implement your logic to fetch user profile from the database
    // ...

    // Return the user profile information
    return [
        'id' => 1,
        'username' => "YourUsername",
        'fullName' => "Your Full Name",
        'email' => "your.email@example.com",
        'profilePicture' => "profile.jpg",
        'settings' => [
            'accessLevel' => 'User Access',
            'department' => 'Sample Department',
            'phone_number' => '+1234567890',
            'address' => '123 Main Street, City',
            'joinDate' => '2022-01-01',
        ],
        'additionalInfo' => [
            'dateOfBirth' => '1990-01-01',
            'gender' => 'Male',
            'maritalStatus' => 'Single',
        ],
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>User Profile</title>
    <link rel="stylesheet" href="Assets/css/profile.css">
    <?php include("Include/ad_sidebar.php");?>

</head>
<body>
    <div class = "prof_container">
    <h1>Profile</h1>

    <!-- Display user profile information -->
    <div>
        <img src="uploads/<?php echo $userProfileInfo['profilePicture']; ?>"  width="250">
    </div>
    <h2>Profile Information</h2>
    <p>Username: <?php echo $userProfileInfo['username']; ?></p>
    <p>Full Name: <?php echo $userProfileInfo['fullName']; ?></p>
    <p>Email: <?php echo $userProfileInfo['email']; ?></p>
    <p>Date of Birth: <?php echo $userProfileInfo['additionalInfo']['dateOfBirth']; ?></p>
    <p>Gender: <?php echo $userProfileInfo['additionalInfo']['gender']; ?></p>
    <p>Marital Status: <?php echo $userProfileInfo['additionalInfo']['maritalStatus']; ?></p>

    <!-- Button to open modal -->
    <button id="openModal">Edit Profile</button>

    <!-- The Modal -->
    <div id="profileModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeModal">&times;</span>
            
            <!-- Form for editing settings -->
            <form method="post" action="" id="settingsForm">
                <h2>Edit Profile</h2>
                <!-- Editable fields -->
                <label for="username">Username:</label>
                <input type="text" name="username" value="<?php echo $userProfileInfo['username']; ?>" required>
                <br><br>
                <label for="fullName">Full Name:</label>
                <input type="text" name="fullName" value="<?php echo $userProfileInfo['fullName']; ?>" required>
                <br><br>
                <label for="email">Email:</label>
                <input type="email" name="email" value="<?php echo $userProfileInfo['email']; ?>" required>
                <br><br>
                <label for="dateOfBirth">Date of Birth:</label>
                <input type="date" name="dateOfBirth" value="<?php echo $userProfileInfo['additionalInfo']['dateOfBirth']; ?>" required>
                <br><br>
                <label for="gender">Gender:</label>
                <select name="gender" required>
                    <option value="Male" <?php echo ($userProfileInfo['additionalInfo']['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
                    <option value="Female" <?php echo ($userProfileInfo['additionalInfo']['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
                </select>
                <br><br>
                <label for="maritalStatus">Marital Status:</label>
                <select name="maritalStatus" required>
                    <option value="Single" <?php echo ($userProfileInfo['additionalInfo']['maritalStatus'] === 'Single') ? 'selected' : ''; ?>>Single</option>
                    <option value="Married" <?php echo ($userProfileInfo['additionalInfo']['maritalStatus'] === 'Married') ? 'selected' : ''; ?>>Married</option>
                    <!-- Add other options as needed -->
                </select>
                <br><br>
                <!-- Add other fields as needed -->

                <!-- Profile Picture Upload -->
                <label for="profile_picture">Profile Picture:</label>
                <input type="file" name="profile_picture" accept="image/*">
                <br><br>
                <button type="submit">Save Changes</button>
                <br><br>
            </form>
        </div>
    </div>

    <script>
        // JavaScript to handle modal functionality
        var openModalButton = document.getElementById('openModal');
        var closeModalButton = document.getElementById('closeModal');
        var modal = document.getElementById('profileModal');

        openModalButton.addEventListener('click', function () {
            modal.style.display = 'block';
        });

        closeModalButton.addEventListener('click', function () {
            modal.style.display = 'none';
        });

        // Close the modal if the user clicks outside of it
        window.addEventListener('click', function (event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    </script>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>
</html>
<?php
//close the database
mysqli_close($conn);
?>
