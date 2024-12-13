<?php
require_once 'core/dbConfig.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $confirm_password = $_POST['confirm_password'];
  $email = $_POST['email'];
  $role = $_POST['role']; 

  if ($password !== $confirm_password) {
    $error = "Passwords do not match!";
  } else {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, password, role, email) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username, $hashed_password, $role, $email]);

    if ($stmt) {
      header("Location: login.php");
      exit();
    } else {
      $error = "An error occurred while registering.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
  <div class="flex items-center justify-center min-h-screen px-4">
    <div class="border border-gray-300 rounded-lg p-6 max-w-md shadow-[0_2px_22px_-4px_rgba(93,96,127,0.2)] w-full">
      <form action="registration.php" method="POST" class="space-y-4">
        <div class="mb-8">
          <h3 class="text-gray-800 text-3xl font-extrabold text-center">Register</h3>
        </div>
        <?php if (isset($error) && $error != "") {
          echo "<p class='text-red-600'>$error</p>";
        } ?>
        <div>
          <label for="username" class="text-gray-800 text-sm mb-2 block">Username</label>
          <input type="text" name="username" id="username" required
            class="w-full text-sm text-gray-800 border border-gray-300 px-4 py-3 rounded-lg outline-blue-600"
            placeholder="Enter username">
        </div>
        <div>
          <label for="email" class="text-gray-800 text-sm mb-2 block">Email</label>
          <input type="email" name="email" id="email" required
            class="w-full text-sm text-gray-800 border border-gray-300 px-4 py-3 rounded-lg outline-blue-600"
            placeholder="Enter email">
        </div>
        <div>
          <label for="role" class="text-gray-800 text-sm mb-2 block">Role</label>
          <select name="role" id="role" required
            class="w-full text-sm text-gray-800 border border-gray-300 px-4 py-3 rounded-lg outline-blue-600">
            <option value="applicant">Applicant</option>
            <option value="hr">HR</option>
          </select>
        </div>
        <div>
          <label for="password" class="text-gray-800 text-sm mb-2 block">Password</label>
          <input type="password" name="password" id="password" required
            class="w-full text-sm text-gray-800 border border-gray-300 px-4 py-3 rounded-lg outline-blue-600"
            placeholder="Enter password">
        </div>
        <div>
          <label for="confirm_password" class="text-gray-800 text-sm mb-2 block">Confirm Password</label>
          <input type="password" name="confirm_password" id="confirm_password" required
            class="w-full text-sm text-gray-800 border border-gray-300 px-4 py-3 rounded-lg outline-blue-600"
            placeholder="Confirm password">
        </div>

        <div class="!mt-8">
          <button type="submit"
            class="w-full shadow-xl py-3 px-4 text-sm tracking-wide rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
            Register
          </button>
        </div>
        <p class="text-sm !mt-8 text-center text-gray-800">
          Already have an account?
          <a href="login.php" class="text-blue-600 font-semibold hover:underline ml-1">Login here</a>
        </p>
      </form>
    </div>
  </div>
</body>

</html>