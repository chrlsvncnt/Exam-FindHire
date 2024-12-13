<?php
include_once '../core/dbConfig.php';

if ($_SESSION['role'] != 'applicant') {
  header('Location: index.php');
  exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['apply'])) {
  $job_post_id = $_POST['job_post_id'];
  $applicant_id = $_SESSION['user_id'];
  $description = $_POST['description'];

  if (isset($_FILES['resume']) && $_FILES['resume']['error'] == 0) {
    $resume = $_FILES['resume'];
    $allowedExtensions = ['pdf'];
    $fileExtension = pathinfo($resume['name'], PATHINFO_EXTENSION);

    if (in_array(strtolower($fileExtension), $allowedExtensions)) {
      $uploadDirectory = '../uploads/resumes/';
      $fileName = uniqid('resume_') . '.' . $fileExtension;
      $filePath = $uploadDirectory . $fileName;

      if (move_uploaded_file($resume['tmp_name'], $filePath)) {
        $applySql = "INSERT INTO applications (job_post_id, applicant_id, description, resume) VALUES (?, ?, ?, ?)";
        $applyStmt = $pdo->prepare($applySql);
        $applyStmt->execute([$job_post_id, $applicant_id, $description, $filePath]);

        header("Location: applicant_dashboard.php");
        exit();
      } else {
        $error = 'Error uploading the file. Please try again.';
      }
    } else {
      $error = 'Only PDF files are allowed for resume upload.';
    }
  } else {
    $error = 'Please upload a resume in PDF format.';
  }
}

if (isset($_GET['job_post_id'])) {
  $job_post_id = $_GET['job_post_id'];
  $jobPostSql = "SELECT * FROM job_posts WHERE job_post_id = ?";
  $jobPostStmt = $pdo->prepare($jobPostSql);
  $jobPostStmt->execute([$job_post_id]);
  $jobPost = $jobPostStmt->fetch(PDO::FETCH_ASSOC);

  if (!$jobPost) {
    header('Location: applicant_dashboard.php');
    exit();
  }
} else {
  header('Location: applicant_dashboard.php');
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Apply for Job</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.2/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css"> 
</head>

<body class="max-w-7xl m-auto">

  <?php include_once 'navbar.php'; ?>

  <div class="container mx-auto p-8">
    <h1 class="text-3xl font-bold text-center mb-6">Apply for Job: <?php echo htmlspecialchars($jobPost['title']); ?>
    </h1>

    <?php if (isset($error)): ?>
      <div class="bg-red-100 text-red-700 p-4 rounded-md mb-4">
        <?php echo $error; ?>
      </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="space-y-6">
      <input type="hidden" name="job_post_id" value="<?php echo $job_post_id; ?>">

      <div>
        <label for="description" class="block text-sm font-medium text-gray-700">Why are you the best fit for this
          role?</label>
        <textarea id="description" name="description" rows="4" class="mt-1 p-2 w-full border border-gray-300 rounded-md"
          required></textarea>
      </div>

      <div>
        <label for="resume" class="block text-sm font-medium text-gray-700">Upload Your Resume (PDF only)</label>
        <input type="file" name="resume" id="resume" accept="application/pdf"
          class="mt-1 p-2 w-full border border-gray-300 rounded-md" required>
      </div>

      <div class="flex justify-end">
        <button type="submit" name="apply"
          class="py-2 px-6 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
          Submit Application
        </button>
      </div>
    </form>

  </div>

</body>

</html>