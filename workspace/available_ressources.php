<?php session_start(); ob_start();

$account = isset($_SESSION['account']) ? (object) $_SESSION['account'] : header('location: core/user/deconnection.php');
switch($account->type_utilisateur)
{
    case 2: header('location: index_client.php'); break;
    case 4: header('location: index_fournisseur.php'); break;
    case 1: header('location: index_admin.php'); break;
}

$panier = isset($_SESSION['panier']) ? $_SESSION['panier'] : array();
$idrd = isset($_POST['idrd']) ? $_POST['idrd'] : 0;
$utrd = isset($_POST['utrd']) ? $_POST['utrd'] : 0;
$ide = isset($_GET['ide']) ? $_GET['ide'] : 0; 

if($idrd > 0 && $utrd > 0)
{
    $ressource_demanded = array($idrd, $utrd);
    array_push($panier, $ressource_demanded);
    $_SESSION['panier'] = $panier;
    echo 'Ajout au panier termine avec succes..';
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Available Ressources - UltraEvent</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">
    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/chart/chart.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Template Javascript -->
    <script src="./js/main.js"></script>
    <script src="./js/ressource.js"></script>
    <!-- Favicon -->
    <link href="../img/logo.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet"-->

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
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative">
                        <img class="rounded-circle" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                        <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
                    </div>
                    <div class="ms-3">
                    <h6 class="mb-0"><?php echo $account->nom_utilisateur.' '.$account->prenom_utilisateur; ?></h6>
                        <span>Manager</span>
                    </div>
                </div>
                <div class="navbar-nav w-100">
                    
                <a href="index_manager.php" class="nav-item nav-link"><i class="fa fa-calendar-check me-2" aria-hidden="true"></i>My Events</a>
                <a href="available_ressources.php" class="nav-item nav-link active"><i class="fa fa-list me-2"></i>Ressources</a>        
                </div>
            </nav>
        </div>
        <!-- Sidebar End -->


        <!-- Content Start -->
        <div class="content">
            <!-- Navbar Start -->
            <nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">
                <a href="index.html" class="navbar-brand d-flex d-lg-none me-4">
                    <h2 class="text-primary mb-0"><i class="fa fa-hashtag"></i></h2>
                </a>
                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>
                <div>
                    
                </div>
            
                <div class="navbar-nav align-items-center ms-auto"> 
                    
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <span class="d-none d-lg-inline-flex"style="margin:8px"><?php echo $account->nom_utilisateur.' '.$account->prenom_utilisateur; ?></span>
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

            <!-- Recent Sales Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="bg-light text-center rounded p-4" style="min-height:450px;">

                    <div id="available_ressources" class="table-responsive" style="min-height: 280px;">
                    </div>
                </div>
            </div>
            <!-- Recent Sales End -->
             <!-- Footer Start -->
             <div class="container-fluid pt-4 px-4">
                <div class="bg-light rounded-top p-4">
                    <div class="row">
                        <div class="col-12 col-sm-6 text-center text-sm-start">
                        <small><small>&copy; <a href="#">UltraEvent</a>, All Right Reserved. </small></small>
                        </div>
                        <div class="col-12 col-sm-6 text-center text-sm-end">
                            <small><small>Designed By <a href="#">S.Bouguettaia & F.Matallah</a></small></small>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer End -->
        </div>
        <!-- Content End -->
        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
     <!--<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/chart/chart.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    
    <script>
        $(document).ready(function () {
            $('div#available_ressources').load('core/ressorces/list_available_ressources.php?ide='+<?php echo $ide;?>);
            $('#searchInput').on('keyup', function() {
        var query = $(this).val();
        if (query.length > 0) { // Start searching after 3 characters
            $.ajax({
                url: 'search.php', // Server-side script to process the search
                method: 'POST',
                data: { query: query },
                success: function(data) {
                    $('#searchResults').html(data).show(); // Show the dropdown menu with results
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        } else {
            $('#searchResults').hide(); // Hide the dropdown menu if the query is too short
        }
    });

    // Hide dropdown when clicking outside
    $(document).on('click', function(event) {
        if (!$(event.target).closest('#searchInput').length) {
            $('#searchResults').hide();
        }
    });
        });
    </script>
</body>
</html>