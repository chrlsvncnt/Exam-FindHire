<?php
include_once '../core/dbConfig.php';

$statuses = ['pending', 'accepted', 'rejected'];

$applications = [];
foreach ($statuses as $status) {
  $sql = "SELECT a.application_id, a.applicant_id, a.job_post_id, a.description, a.application_status, a.application_date, a.resume, u.username, jp.title 
            FROM applications a 
            INNER JOIN users u ON a.applicant_id = u.user_id 
            INNER JOIN job_posts jp ON a.job_post_id = jp.job_post_id 
            WHERE a.application_status = :status
            ORDER BY a.application_date DESC";

  $stmt = $pdo->prepare($sql);
  $stmt->execute(['status' => $status]);

  if ($stmt->rowCount() > 0) {
    $applications[$status] = $stmt->fetchAll(PDO::FETCH_ASSOC);
  } else {
    $applications[$status] = [];
  }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
  $application_id = $_POST['application_id'];
  $action = $_POST['action']; 

  $updateSql = "UPDATE applications SET application_status = :status WHERE application_id = :application_id";
  $updateStmt = $pdo->prepare($updateSql);
  $updateStmt->execute(['status' => $action, 'application_id' => $application_id]);

  header('Location: applicants.php');
  exit(); 
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Applications</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.2/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css"> 
</head>

<body class="max-w-7xl m-auto">


  <?php include_once 'navbar.php'; ?>

  <div class="container mx-auto p-8">
    <h1 class="text-3xl font-bold text-center mb-6">Applications</h1>

    <h2 class="text-2xl font-semibold mb-4">Pending Applications</h2>
    <?php if (!empty($applications['pending'])): ?>
      <table class="min-w-full bg-white shadow-lg rounded-lg mb-8 text-center">
        <thead class="bg-gray-200">
          <tr>
            <th class="py-2 px-4 border">Application ID</th>
            <th class="py-2 px-4 border">Applicant</th>
            <th class="py-2 px-4 border">Job Title</th>
            <th class="py-2 px-4 border">Description</th>
            <th class="py-2 px-4 border">Status</th>
            <th class="py-2 px-4 border">Application Date</th>
            <th class="py-2 px-4 border">Resume</th>
            <th class="py-2 px-4 border">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($applications['pending'] as $application): ?>
            <tr>
              <td class="py-2 px-4 border"><?php echo htmlspecialchars($application['application_id']); ?></td>
              <td class="py-2 px-4 border"><?php echo htmlspecialchars($application['username']); ?></td>
              <td class="py-2 px-4 border"><?php echo htmlspecialchars($application['title']); ?></td>
              <td class="py-2 px-4 border"><?php echo nl2br(htmlspecialchars($application['description'])); ?></td>
              <td class="py-2 px-4 border"><?php echo ucfirst(htmlspecialchars($application['application_status'])); ?></td>
              <td class="py-2 px-4 border"><?php echo date('F j, Y, g:i a', strtotime($application['application_date'])); ?>
              </td>
              <td class="py-2 px-4 border">
                <a href="<?php echo htmlspecialchars($application['resume']); ?>" class="text-blue-500 hover:underline"
                  download>Download Resume</a>
              </td>
              <td class="py-2 px-4 border">
                <form method="POST" class="inline">
                  <input type="hidden" name="application_id" value="<?php echo $application['application_id']; ?>">

                  <?php if ($application['application_status'] == 'pending'): ?>
                    <button type="submit" name="action" value="accepted" class="text-green-500">Accept</button>
                    <span class="mx-2">|</span>
                    <button type="submit" name="action" value="rejected" class="text-red-500">Reject</button>
                  <?php elseif ($application['application_status'] == 'accepted'): ?>
                    <button type="submit" name="action" value="pending" class="text-yellow-500">Pending</button>
                    <span class="mx-2">|</span>
                    <button type="submit" name="action" value="rejected" class="text-red-500">Reject</button>
                  <?php elseif ($application['application_status'] == 'rejected'): ?>
                    <button type="submit" name="action" value="pending" class="text-yellow-500">Pending</button>
                    <span class="mx-2">|</span>
                    <button type="submit" name="action" value="accepted" class="text-green-500">Accept</button>
                  <?php endif; ?>
                </form>
              </td>


            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p class="text-center text-gray-600">No pending applications at the moment.</p>
    <?php endif; ?>

    <h2 class="text-2xl font-semibold mb-4">Accepted Applications</h2>
    <?php if (!empty($applications['accepted'])): ?>
      <table class="min-w-full bg-white shadow-lg rounded-lg mb-8 text-center">
        <thead class="bg-gray-200">
          <tr>
            <th class="py-2 px-4 border">Application ID</th>
            <th class="py-2 px-4 border">Applicant</th>
            <th class="py-2 px-4 border">Job Title</th>
            <th class="py-2 px-4 border">Description</th>
            <th class="py-2 px-4 border">Status</th>
            <th class="py-2 px-4 border">Application Date</th>
            <th class="py-2 px-4 border">Resume</th>
            <th class="py-2 px-4 border">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($applications['accepted'] as $application): ?>
            <tr>
              <td class="py-2 px-4 border"><?php echo htmlspecialchars($application['application_id']); ?></td>
              <td class="py-2 px-4 border"><?php echo htmlspecialchars($application['username']); ?></td>
              <td class="py-2 px-4 border"><?php echo htmlspecialchars($application['title']); ?></td>
              <td class="py-2 px-4 border"><?php echo nl2br(htmlspecialchars($application['description'])); ?></td>
              <td class="py-2 px-4 border"><?php echo ucfirst(htmlspecialchars($application['application_status'])); ?></td>
              <td class="py-2 px-4 border"><?php echo date('F j, Y, g:i a', strtotime($application['application_date'])); ?>
              </td>
              <td class="py-2 px-4 border">
                <a href="<?php echo htmlspecialchars($application['resume']); ?>" class="text-blue-500 hover:underline"
                  download>Download Resume</a>
              </td>
              <td class="py-2 px-4 border">
                <form method="POST" class="inline">
                  <input type="hidden" name="application_id" value="<?php echo $application['application_id']; ?>">

                  <?php if ($application['application_status'] == 'pending'): ?>
                    <button type="submit" name="action" value="accepted" class="text-green-500">Accept</button>
                    <span class="mx-2">|</span>
                    <button type="submit" name="action" value="rejected" class="text-red-500">Reject</button>
                  <?php elseif ($application['application_status'] == 'accepted'): ?>
                    <button type="submit" name="action" value="pending" class="text-yellow-500">Pending</button>
                    <span class="mx-2">|</span>
                    <button type="submit" name="action" value="rejected" class="text-red-500">Reject</button>
                  <?php elseif ($application['application_status'] == 'rejected'): ?>
                    <button type="submit" name="action" value="pending" class="text-yellow-500">Pending</button>
                    <span class="mx-2">|</span>
                    <button type="submit" name="action" value="accepted" class="text-green-500">Accept</button>
                  <?php endif; ?>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p class="text-center text-gray-600">No accepted applications at the moment.</p>
    <?php endif; ?>

    <h2 class="text-2xl font-semibold mb-4">Rejected Applications</h2>
    <?php if (!empty($applications['rejected'])): ?>
      <table class="min-w-full bg-white shadow-lg rounded-lg mb-8 text-center">
        <thead class="bg-gray-200">
          <tr>
            <th class="py-2 px-4 border">Application ID</th>
            <th class="py-2 px-4 border">Applicant</th>
            <th class="py-2 px-4 border">Job Title</th>
            <th class="py-2 px-4 border">Description</th>
            <th class="py-2 px-4 border">Status</th>
            <th class="py-2 px-4 border">Application Date</th>
            <th class="py-2 px-4 border">Resume</th>
            <th class="py-2 px-4 border">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($applications['rejected'] as $application): ?>
            <tr>
              <td class="py-2 px-4 border"><?php echo htmlspecialchars($application['application_id']); ?></td>
              <td class="py-2 px-4 border"><?php echo htmlspecialchars($application['username']); ?></td>
              <td class="py-2 px-4 border"><?php echo htmlspecialchars($application['title']); ?></td>
              <td class="py-2 px-4 border"><?php echo nl2br(htmlspecialchars($application['description'])); ?></td>
              <td class="py-2 px-4 border"><?php echo ucfirst(htmlspecialchars($application['application_status'])); ?></td>
              <td class="py-2 px-4 border"><?php echo date('F j, Y, g:i a', strtotime($application['application_date'])); ?>
              </td>
              <td class="py-2 px-4 border">
                <a href="<?php echo htmlspecialchars($application['resume']); ?>" class="text-blue-500 hover:underline"
                  download>Download Resume</a>
              </td>
              <td class="py-2 px-4 border">
                <form method="POST" class="inline">
                  <input type="hidden" name="application_id" value="<?php echo $application['application_id']; ?>">

                  <?php if ($application['application_status'] == 'pending'): ?>
                    <button type="submit" name="action" value="accepted" class="text-green-500">Accept</button>
                    <span class="mx-2">|</span>
                    <button type="submit" name="action" value="rejected" class="text-red-500">Reject</button>
                  <?php elseif ($application['application_status'] == 'accepted'): ?>
                    <button type="submit" name="action" value="pending" class="text-yellow-500">Pending</button>
                    <span class="mx-2">|</span>
                    <button type="submit" name="action" value="rejected" class="text-red-500">Reject</button>
                  <?php elseif ($application['application_status'] == 'rejected'): ?>
                    <button type="submit" name="action" value="pending" class="text-yellow-500">Pending</button>
                    <span class="mx-2">|</span>
                    <button type="submit" name="action" value="accepted" class="text-green-500">Accept</button>
                  <?php endif; ?>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p class="text-center text-gray-600">No rejected applications at the moment.</p>
    <?php endif; ?>
  </div>

</body>

</html>