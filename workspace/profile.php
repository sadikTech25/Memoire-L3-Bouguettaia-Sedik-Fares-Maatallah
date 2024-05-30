<?php
session_start();
ob_start();
$account = isset($_SESSION['account']) ? (object) $_SESSION['account'] : header('location: core/user/deconnection.php');

require_once 'core/core.php'; // Création d'un objet PDO pour accéder à la base de données

$link = "core/user/deconnection.php";

// Generate link based on user type
switch ($account->type_utilisateur) {
    case 1:
        $link = "index_admin.php";
        break;
    case 2:
        $link = "index_client.php";
        break;
    case 3:
        $link = "index_manager.php";
        break;
    case 4:
        $link = "index_fournisseur.php";
        break;
    // Add more cases for other user types if needed
}

// Check if the user is signed in
if (!isset($_SESSION['account'])) {
    header('Location: core/user/deconnection.php');
    exit(); // Stop further execution
}

// Retrieve the user object from the session
$user = $_SESSION['account'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>My Events - UltraEvent</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="lib/chart/chart.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>
     <!-- Template Javascript -->
     <script src="./js/main.js"></script>
    <script src="./js/event.js"></script>
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
</head>

<body>
<div class="container-fluid position-relative bg-white d-flex p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->


        <!-- Sidebar Start -->
        <div class="sidebar pe-4 pb-3">
            <nav class="navbar bg-light navbar-light">
                <a href="index_manager.php" class="navbar-brand mx-4 mb-3">
                    <img src="../img/logo.png" alt="UltraEvent Logo" width="150" height="60">
                </a>
                <div class="navbar-nav w-100">
                    <a href="<?php echo $link; ?>" class="nav-item nav-link"><i class="fa fa-list me-2"></i></i>Dashboard</a>
                    <a href="profile.php" class="nav-item nav-link active"><i class="bi bi-person-circle me-2"></i>My Profile</a>
                </div>
            </nav>
        </div>
        <!-- Sidebar End -->


        <!-- Content Start -->
        <div class="content">
            <!-- Navbar Start -->
            <nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">
                <a href="index.html" class="navbar-brand d-flex d-lg-none me-4">
                </a>
                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>
                <div><form class="d-none d-md-flex ms-4">
                    <input class="form-control border-0" id="searchInput" type="search" placeholder="Search">
                </div></form>
                <div class="navbar-nav align-items-center ms-auto"style="margin:8px">
                    
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <span class="d-none d-lg-inline-flex"><?php echo $account->nom_utilisateur.' '.$account->prenom_utilisateur; ?></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                            <a href="profile.php" class="dropdown-item">My Profile</a>
                            <a href="core/user/deconnection.php" class="dropdown-item">Log Out</a>
                        </div>
                    </div>
                </div>
            </nav>
            <div class="dropdown-menu " id="searchResults" style="width: 40%;"></div>
            <!-- Navbar End -->
                <div class="container-fluid pt-4 px-4 ">
                <div class="bg-light text-center rounded p-4" style="min-height:450px;">
                <div class="card-body"><div class="d-flex flex-column align-items-center text-center"> <img src="img/avatar7.png" alt="Admin" class="rounded-circle" width="150"><div class="mt-3"><h4><?php 
            switch ($account->type_utilisateur) {
                case 1:
                    echo 'Admin';
                    break;
                case 2:
                    echo 'Client';
                    break;
                case 4:
                    echo 'Fournisseur';
                    break;
                    case 3:
                        echo 'Manager';
                        break;
                default:
                    echo 'Unknown';
            } ?></h4></div></div></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <h6 class="mb-0">Full Name</h6>
                        </div><div class="col-sm-9 text-secondary"> <?php echo $account->nom_utilisateur.' '.$account->prenom_utilisateur; ?>
                    </div></div><hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <h6 class="mb-0">Email</h6>
                        </div>
                        <div class="col-sm-9 text-secondary"> <?php echo $account->email_utilisateur; ?>
                    </div>
                </div><hr>
                <div class="row">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Mobile</h6>
                    </div>
                    <div class="col-sm-9 text-secondary"> <?php echo $account->mobile_utilisateur; ?>
                </div>
            </div><hr>

            <div class="row">
                <div class="col-sm-3">
                    <h6 class="mb-0">Address</h6>
                </div>
                <div class="col-sm-9 text-secondary"> <?php echo $account->adresse_utilisateur; ?>
                </div>
            </div><hr>
            <div class="row">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Birth Date</h6>
                    </div>
                    <div class="col-sm-9 text-secondary"> <?php echo $account->date_naissance_utilisateur; ?>
                </div>
            </div><hr>
    </div>


                <!-- <h1>Welcome to Your Profile</h1>
            <p><strong>Name:</strong> <?php echo $account->nom_utilisateur.' '.$account->prenom_utilisateur; ?></p>
            <p><strong>Email:</strong> <?php echo $account->email_utilisateur; ?></p>
            <p><strong>Type:</strong> <?php 
            switch ($account->type_utilisateur) {
                case 1:
                    echo 'Admin';
                    break;
                case 2:
                    echo 'Client';
                    break;
                case 4:
                    echo 'Fournisseur';
                    break;
                    case 3:
                        echo 'Manager';
                        break;
                default:
                    echo 'Unknown';
            } ?></p>
                </div> -->
            </div>
            </div>

           
               
                <!-- Footer Start -->
                <small><small> <div class="container-fluid pt-4 px-4">
                <div class="bg-light rounded-top p-4">
                    <div class="row">
                        <div class="col-12 col-sm-6 text-center text-sm-start">
                            &copy; <a href="#">UltraEvent</a> All Right Reserved. 
                        </div>
                        <div class="col-12 col-sm-6 text-center text-sm-end">
                            Developed By <a href="#">S.Bouguettaia & F.Matallah</a><br>
                            Template Designed By <a href="htmlcodex.com">htmlcodex.com</a>
                        </div>
                    </div>
                </div>
            </div></small></small>
            <!-- Footer End -->
        </div>
        <!-- Content End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>
        
    </body>
    
    </html>
