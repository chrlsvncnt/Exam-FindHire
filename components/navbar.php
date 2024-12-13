<?php

$sql = "SELECT email FROM users WHERE user_id = " . $_SESSION['user_id'];
$stmt = $pdo->prepare($sql);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$email = $user['email'];

?>

<head>
  <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
</head>

<head>
  <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
</head>

<nav class="bg-white border-gray-200 dark:bg-gray-900">
  <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">

    <div class="flex items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse gap-3">

      <?php if (basename($_SERVER['PHP_SELF']) === 'hr_dashboard.php'): ?>
        <a href="add_job.php"
          class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
          Add Job
        </a>
      <?php endif; ?>

      <a href="message.php" class="flex text-sm rounded-full md:me-0" id="user-menu-button" aria-expanded="false"
        data-dropdown-placement="bottom">
        <img class="w-7 h-7 " src="https://cdn-icons-png.flaticon.com/512/149/149446.png" alt="message icon">
      </a>

      <button type="button"
        class="flex text-sm bg-gray-800 rounded-full md:me-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
        id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown"
        data-dropdown-placement="bottom">
        <span class="sr-only">Open user menu</span>
        <img class="w-8 h-8 rounded-full"
          src="https://images.pexels.com/photos/15135914/pexels-photo-15135914/free-photo-of-2-0-2-3.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1"
          alt="user photo">
      </button>
      <div
        class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600"
        id="user-dropdown">
        <div class="px-4 py-3">
          <span class="block text-sm text-gray-900 dark:text-white"><?php echo $_SESSION['username']; ?></span>
          <span
            class="block text-sm text-gray-500 truncate dark:text-gray-400"><?php echo htmlspecialchars($email); ?></span>
        </div>
        <ul class="py-2" aria-labelledby="user-menu-button">
          <li>
            <a href="../logout.php"
              class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Sign
              out</a>
          </li>
        </ul>
      </div>
      <button data-collapse-toggle="navbar-user" type="button"
        class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
        aria-controls="navbar-user" aria-expanded="false">
        <span class="sr-only">Open main menu</span>
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M1 1h15M1 7h15M1 13h15" />
        </svg>
      </button>
    </div>

    <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-user">
      <ul
        class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
        <li>
          <a href="<?php echo ($_SESSION['role'] === 'applicant') ? 'applicant_dashboard.php' : 'hr_dashboard.php'; ?>"
            class="block py-2 px-3 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 md:dark:text-blue-500"
            aria-current="page">Dashboard</a>
        </li>
        <?php if (basename($_SERVER['PHP_SELF']) === 'applicant_dashboard.php'): ?>
          <li>
            <a href="my_application.php"
              class="block py-2 px-3 text-black bg-blue-700 rounded md:bg-transparent md:text-black-700 md:p-0 md:dark:text-black-500"
              aria-current="page">My Applications</a>
          </li>
        <?php endif; ?>

        <?php if (basename($_SERVER['PHP_SELF']) === 'hr_dashboard.php'): ?>
          <li>
            <a href="applicants.php"
              class="block py-2 px-3 text-black bg-blue-700 rounded md:bg-transparent md:text-black-700 md:p-0 md:dark:text-black-500"
              aria-current="page">Applications</a>
          </li>
        <?php endif; ?>

        <li>
          <a href="inbox.php"
            class="block py-2 px-3 text-black bg-blue-700 rounded md:bg-transparent md:text-black-700 md:p-0 md:dark:text-black-500"
            aria-current="page">Inbox</a>
        </li>
      </ul>
    </div>
  </div>
</nav>