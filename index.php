<?php
require_once 'core/dbConfig.php';


if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit();
}

$user_role = $_SESSION['role'];

?>

<?php include_once 'login.php'; ?>