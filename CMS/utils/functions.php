<?php

session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../../auth/login.php");
    exit;
}

function template_header($title) {
	$username  = $_SESSION["username"];
  $role = $_SESSION["id_role"];
  
echo <<<EOT
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>My CMS</title>
		<!--Bootstrap-->
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
    <!--Font Awesome-->
		<script src="https://kit.fontawesome.com/44e2662d8b.js" crossorigin="anonymous"></script>
    <!--CSS-->
    <link href="../../utils/styles.css" rel="stylesheet" type="text/css">
  </head>
	<body>
	<nav class="navbar sticky-top navbar-expand-lg">

        <a class="navtitle navbar-brand" href="../../../index.php">Portfolio</a>
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbar">
            <span class="navtitle" role="button" ><i class="fa fa-bars" aria-hidden="true"></i></span>
        </button>
        <div class="collapse navbar-collapse" id="navbar">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
EOT;
if($role == 1) {
echo <<<EOT
            <li class="nav-item">
                <a class="nav-link navtext" href="../profile/read.php">Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link navtext" href="../about/read.php">About</a>
            </li>
            <li class="nav-item">
                <a class="nav-link navtext" href="../socials/read.php">Socials</a>
            </li>
            <li class="nav-item">
              <a class="nav-link navtext" href="../languages/read.php">Languages</a>
            </li>
          <li class="nav-item">
            <a class="nav-link navtext" href="../education/read.php">Education</a>
          </li>
          <li class="nav-item">
            <a class="nav-link navtext" href="../skills/read.php">Skills</a>
          </li>
          <li class="nav-item">
            <a class="nav-link navtext" href="../users/read.php">Users</a>
          </li>
EOT;
}if($role == 1 || $role == 2){
echo <<<EOT
            <li class="nav-item">
              <a class="nav-link navtext" href="../contact_requests/read.php">Contact Requests</a>
            </li>
            <li class="nav-item">
            <a class="nav-link navtext" href="../salary_simulator/index.php">Salary Simulator</a>
          </li>
          </ul>
          <ul class="navbar-nav me-2 mb-2 mb-lg-0">
            <li class="nav-item">
              <button id="theme-toggle" class="theme-toggle-button fas"></button>
            </li>
            <li class="nav-item">
              <a class="nav-link navtext" href="../../auth/logout.php">Logout</a>
            </li>  
            
            </ul>
EOT;
}
echo <<<EOT
        </div>

    </nav>
EOT;
}
function template_footer() {
echo <<<EOT
      <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
      <script type="text/javascript" src="../../utils/main.js"></script>
    </body>
    
</html>
EOT;
}
?>
