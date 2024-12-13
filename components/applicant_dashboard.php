<?php
include_once '../core/dbConfig.php';

if ($_SESSION['role'] != 'applicant') {
  header('Location: index.php');
  exit();
}

$sql = "SELECT jp.job_post_id, jp.title, jp.description, jp.created_at, u.username, u.email
        FROM job_posts jp 
        INNER JOIN users u ON jp.created_by = u.user_id 
        ORDER BY jp.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute();

if ($stmt->rowCount() > 0) {
  $jobPosts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
  $jobPosts = [];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['apply'])) {
  $job_post_id = $_POST['job_post_id'];
  $applicant_id = $_SESSION['user_id'];

  $applySql = "INSERT INTO applications (job_post_id, applicant_id) VALUES (?, ?)";
  $applyStmt = $pdo->prepare($applySql);
  $applyStmt->execute([$job_post_id, $applicant_id]);

  header("Location: applicant_dashboard.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Applicant Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.2/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css"> 
</head>

<body class="max-w-7xl m-auto">

  <?php include_once 'navbar.php'; ?>

  <div class="container mx-auto p-8">
    <h1 class="text-3xl font-bold text-center mb-6">Job Posts</h1>

    <?php if (!empty($jobPosts)): ?>
      <div class="space-y-4">
        <?php foreach ($jobPosts as $post): ?>
          <div
            class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 ease-in-out relative">
            <div class="flex justify-between items-start">
              <div class="flex-grow">
                <h2 class="text-2xl font-semibold text-gray-800"><?php echo htmlspecialchars($post['title']); ?></h2>
                <p class="text-gray-600 mt-2"><?php echo nl2br(htmlspecialchars($post['description'])); ?></p>
                <p class="text-sm text-gray-500 mt-2">Posted by: <?php echo htmlspecialchars($post['username']); ?></p>
                <p class="text-sm text-gray-500">Posted on:
                  <?php echo date('F j, Y, g:i a', strtotime($post['created_at'])); ?>
                </p>
                <p class="text-sm text-gray-500">Contact: <?php echo htmlspecialchars($post['email']); ?></p>
              </div>
            </div>

            <div class="absolute bottom-4 right-4">
              <form method="POST">
                <input type="hidden" name="job_post_id" value="<?php echo $post['job_post_id']; ?>">
                <a href="apply_job.php?job_post_id=<?php echo $post['job_post_id']; ?>"
                  class="py-2 px-4 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                  Apply for Job
                </a>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p class="text-center text-gray-600">No job posts available at the moment.</p>
    <?php endif; ?>

  </div>

</body>

</html>