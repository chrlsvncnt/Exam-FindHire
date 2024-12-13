<?php
include_once '../core/dbConfig.php';

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php'); 
  exit();
}

$applicant_id = $_SESSION['user_id']; 

$sql = "SELECT a.application_id, a.application_status, a.description, a.application_date, a.resume, jp.title AS job_title
        FROM applications a
        JOIN job_posts jp ON a.job_post_id = jp.job_post_id
        WHERE a.applicant_id = :applicant_id
        ORDER BY a.application_date DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute(['applicant_id' => $applicant_id]);

$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Applications</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.2/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css"> 
</head>

<body class="max-w-7xl m-auto">

  <?php include_once 'navbar.php'; ?>

  <div class="container mx-auto p-8">
    <h1 class="text-3xl font-bold text-center mb-6">My Applications</h1>

    <?php if (empty($applications)): ?>
      <p class="text-center text-gray-600">You have not applied for any jobs yet.</p>
    <?php else: ?>
      <table class="min-w-full bg-white shadow-lg rounded-lg mb-8 text-center">
        <thead class="bg-gray-200">
          <tr>
            <th class="py-2 px-4 border">Application ID</th>
            <th class="py-2 px-4 border">Job Title</th>
            <th class="py-2 px-4 border">Description</th>
            <th class="py-2 px-4 border">Status</th>
            <th class="py-2 px-4 border">Application Date</th>
            <th class="py-2 px-4 border">Resume</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($applications as $application): ?>
            <tr>
              <td class="py-2 px-4 border"><?php echo htmlspecialchars($application['application_id']); ?></td>
              <td class="py-2 px-4 border"><?php echo htmlspecialchars($application['job_title']); ?></td>
              <td class="py-2 px-4 border"><?php echo nl2br(htmlspecialchars($application['description'])); ?></td>
              <td class="py-2 px-4 border"><?php echo ucfirst(htmlspecialchars($application['application_status'])); ?></td>
              <td class="py-2 px-4 border"><?php echo date('F j, Y, g:i a', strtotime($application['application_date'])); ?>
              </td>
              <td class="py-2 px-4 border">
                <a href="<?php echo htmlspecialchars($application['resume']); ?>" class="text-blue-500 hover:underline"
                  download>Download Resume</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>

  </div>

</body>

</html>