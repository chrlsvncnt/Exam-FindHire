<?php
include_once '../core/dbConfig.php';


if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$receiverId = $_SESSION['user_id'];

if (!isset($_SESSION['email'])) {
  $sql = "SELECT email FROM users WHERE user_id = :user_id";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':user_id', $receiverId, PDO::PARAM_INT);
  $stmt->execute();

  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user) {
    $_SESSION['email'] = $user['email']; 
    $receiverEmail = $user['email']; 
  } else {
    echo "<p class='text-red-500'>User not found. Please log in again.</p>";
    exit();
  }
} else {
  $receiverEmail = $_SESSION['email']; 
}

$query = "SELECT messages.message_id, messages.message, messages.sent_at, users.email AS sender_email 
          FROM messages 
          JOIN users ON messages.sender_id = users.user_id 
          WHERE messages.receiver_id = :receiverId 
          ORDER BY messages.sent_at DESC";

$stmt = $pdo->prepare($query);
$stmt->bindParam(':receiverId', $receiverId, PDO::PARAM_INT);
$stmt->execute();

$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inbox</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="styles.css"> 
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal h-screen">

  <div class="w-full max-w-3xl p-6 bg-white mx-auto rounded-lg shadow-lg mt-10">
    <div class="flex items-center space-x-4 mb-6">
      <a href="<?php echo ($_SESSION['role'] === 'applicant') ? 'applicant_dashboard.php' : 'hr_dashboard.php'; ?>"
        class="text-blue-600 hover:underline text-2xl">←</a>
      <h1 class="text-3xl font-bold text-gray-700">Inbox</h1>
    </div>

    <?php if (count($messages) > 0): ?>
      <div class="space-y-4">
        <?php foreach ($messages as $message): ?>
          <div class="p-4 border border-gray-300 rounded-lg">
            <p class="text-gray-600 text-sm">From:
              <strong><?php echo htmlspecialchars($message['sender_email']); ?></strong>
            </p>
            <p class="text-gray-700 mt-2"><?php echo nl2br(htmlspecialchars($message['message'])); ?></p>
            <p class="text-gray-500 text-xs mt-2"><?php echo date("F j, Y, g:i a", strtotime($message['sent_at'])); ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p class="text-gray-500">You have no messages in your inbox.</p>
    <?php endif; ?>
  </div>

</body>

</html>