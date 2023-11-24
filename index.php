<?php
// Dummy authentication logic for demonstration purposes
function authenticateUser($username, $password, $role) {
// Replace this with your actual authentication logic
if ($role === 'admin' && $username === 'admin' && $password === 'admin123') {
return true;
} elseif ($role === 'evaluator' && $username === 'evaluator' && $password === '1234') {
return true;
} else {
return false;
}
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$username = $_POST['username'];
$password = $_POST['password'];
$role = $_POST['role'];

// Authenticate the user
if (authenticateUser($username, $password, $role)) {
// Redirect to the respective homepage based on the selected role
if ($role === 'admin') {
header('Location: admin.php');
exit();
} elseif ($role === 'evaluator') {
header('Location: eva_homepage.php');
exit();
}
} else {
$loginError = 'Invalid username, password, or role.';
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Edu Staff Evaluation System</title>

<link rel="stylesheet" href="Assets/css/style.css">
</head>
<body>
<header>
<h1>Login</h1>
</header>
<form method="post">
<label for="username">Username:</label>
<input type="text" id="username" name="username" required>

<label for="password">Password:</label>
<input type="password" id="password" name="password" required>

<label for="role"> Role:</label>
<select id="role" name="role" required>
<option value="admin">Admin</option>
<option value="evaluator">Evaluator</option>
</select>

<?php if (isset($loginError)): ?>
<p style="color: red;"><?php echo $loginError; ?></p>
<?php endif; ?>

<button type="submit">Login</button>
</form>
</body>
</html>