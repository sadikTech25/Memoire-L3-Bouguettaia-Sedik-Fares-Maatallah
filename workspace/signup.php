<?php
session_start(); ob_start();

// Include the core_manager class for database connection
include_once('core.php');

// Connect to the database
$pdo = core_manager::generate_pdo();

// Handle form submission (if submitted)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // Sanitize form data (to prevent potential security vulnerabilities)
  $nom = filter_var($_POST['nom'], FILTER_SANITIZE_STRING);
  $prenom = filter_var($_POST['prenom'], FILTER_SANITIZE_STRING);
  $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
  $mobile = filter_var($_POST['mobile'], FILTER_SANITIZE_STRING);
  $birthday = filter_var($_POST['birthday'], FILTER_SANITIZE_STRING);
  $addresse = filter_var($_POST['addresse'], FILTER_SANITIZE_STRING);
  $password = $_POST['password']; // Further validation might be needed
  $confirmpassword = $_POST['confirmpassword']; // Further validation might be needed
  $type = filter_var($_POST['typeutilisateur'], FILTER_SANITIZE_STRING);
  // Validate form data (add your own validation rules here)
  $errors = [];  // Array to store any validation errors

  if (empty($nom)) {
    $errors[] = "Nom is required.";
  }

  if (empty($prenom)) {
    $errors[] = "Prénom is required.";
  }

  if (empty($email) || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    $errors[] = "Invalid email address.";
  }

  if (empty($password)) {
    $errors[] = "Password is required.";
  }else if (strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters long.";
  }
  if (empty($confirmpassword)) {
    $errors[] = "Confirm password is required.";
  } else if ($password !== $confirmpassword) {
    $errors[] = "Passwords do not match.";
  }
  if (empty($errors)) {

    // Hash the password for secure storage (using bcrypt recommended)
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
  // Check if email already exists (assuming a unique email constraint)
  $stmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateur WHERE email_utilisateur = :email");
  $stmt->bindParam(':email', $email, PDO::PARAM_STR);
  $stmt->execute();
  $rowCount = $stmt->fetchColumn();

  if ($rowCount > 0) {
    $errors[] = "Email address already exists.";
  }

  // If no validation errors, proceed with registration
  if (empty($errors)) {

    // Hash the password for secure storage (using bcrypt recommended)
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    // Prepare and execute insert query
    $stmt = $pdo->prepare("INSERT INTO utilisateur (nom_utilisateur, prenom_utilisateur, email_utilisateur, password_utilisateur,adresse_utilisateur, mobile_utilisateur,date_naissance_utilisateur,type_utilisateur) VALUES (:nom, :prenom, :email, :password, :addresse,:mobile,:birthday,:typeutilisateur)");
    $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
    $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
    $stmt->bindParam(':addresse', $addresse, PDO::PARAM_STR);
    $stmt->bindParam(':mobile', $mobile, PDO::PARAM_STR);
    $stmt->bindParam(':birthday', $birthday, PDO::PARAM_STR);
    $stmt->bindParam(':typeutilisateur', $type, PDO::PARAM_STR);




    $success = $stmt->execute();

    if ($success) {
      // Registration successful, redirect to login or appropriate page
      $_SESSION['registration_success'] = true;
      header('location: signin.php');  // Replace with your desired redirect
      exit;
    } else {
      // Registration failed, display error message
      $errors[] = "An error occurred during registration. Please try again.";
    }
  }
}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    
    <meta charset="utf-8">
    <title>Ultra Event - Sign Up</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="../img/logo.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <style>
        .background-image {
            background-image: url('/img/slider-1.jpg');
            background-size: cover;
            background-position: center;
            height: 100vh;
        }
        .background-overlay {
            background-color: rgba(255, 255, 255, 0.8); /* Adjust the opacity as needed */
        }
    </style>
    
</head>

<body>
<?php
  // Display any validation errors if they exist
  if (isset($errors) && !empty($errors)) {
    echo '<ul class="error-messages">';
    foreach ($errors as $error) {
      echo "<li>$error</li>";
    }
    echo '</ul>';
  }

  // Display success message if registration was successful (from session)
  if (isset($_SESSION['registration_success']) && $_SESSION['registration_success'] === true) {
    echo '<p class="success-message">Registration successful! Please login.</p>';
    unset($_SESSION['registration_success']);  // Clear session variable after display
  }
  ?>

    <div class="container-fluid position-relative bg-white d-flex p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->


        <!-- Sign Up Start -->
        
        <div class="container-fluid">
            <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
                <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                    <div class="bg-light rounded p-4 p-sm-5 my-4 mx-3">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <a href="../index.html" class="">
                                <img src="../img/logo.png" alt="UltraEvent Logo" width="155" height="55">                            </a>
                            </a>
                            <h3>Sign Up</h3>
                        </div>
                        <form action="" method="post">
                        <div class="form-floating mb-3">
                            <input type="text" name="nom" class="form-control" id="floatingText" placeholder="nom" required>
                            <label for="floatingText">First Name</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" name="prenom" class="form-control" id="floatingText" placeholder="prenom" required>
                            <label for="floatingText">Last Name</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="email" name="email" class="form-control" id="floatingInput" placeholder="name@example.com" required>
                            <label for="floatingInput">Email address</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="mobile" name="mobile" class="form-control" id="floatingInput" placeholder="+213555555555" required>
                            <label for="floatingInput">Phone number</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="date" name="birthday" class="form-control" id="floatingInput" placeholder="birthday" required>
                            <label for="floatingInput">Birthday</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="address" name="addresse" class="form-control" id="floatingInput" placeholder="+213555555555" required>
                            <label for="floatingInput">Home address</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password" required>
                            <label for="floatingPassword">Password</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" name="confirmpassword" class="form-control" id="floatingPassword" placeholder="Password" required>
                            <label for="floatingPassword">Confirm Password</label>
                        </div>
                        <div class="form-floating mb-3">
                        <select class="form-select" name="typeutilisateur" id="floatingSelect" aria-label="select user type" required>
                            <option selected="" value="2">Client</option>
                            <option value="4">Fournisseur</option>
                            <option value="3">Manager</option>
                        </select>
                        <label for="floatingSelect">Select User Type</label>
                    </div>
                        <!-- <div class="d-flex align-items-center justify-content-between mb-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                <label class="form-check-label" for="exampleCheck1">Check me out</label>
                            </div>
                        </div> -->
                        <button type="submit" class="btn btn-primary py-3 w-100 mb-4">Sign Up</button>
                    </form>

                        <p class="text-center mb-0">Already have an Account? <a href="signin.php">Sign In</a></p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Sign Up End -->
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/chart/chart.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>