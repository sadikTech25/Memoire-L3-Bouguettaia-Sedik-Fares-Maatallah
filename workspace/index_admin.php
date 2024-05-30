<?php
session_start();
ob_start();
$account = isset($_SESSION['account']) ? (object) $_SESSION['account'] : header('location: core/user/deconnection.php');

switch($account->type_utilisateur)
{
    case 2: header('location: index_client.php'); exit;
    case 3: header('location: index_manager.php'); exit;
    case 4: header('location: index_fournisseur.php'); exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Dashboard - UltraEvent</title>
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
                <a href="index_admin.php" class="navbar-brand mx-4 mb-3">
                    <img src="../img/logo.png" alt="UltraEvent Logo" width="150" height="60">
                </a>
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative">
                        <img class="rounded-circle" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                        <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0"><?php echo $account->nom_utilisateur.' '.$account->prenom_utilisateur; ?></h6>
                        <span>Admin</span>
                    </div>
                </div>
                <div class="navbar-nav w-100">
                    <a href="index_admin.php" class="nav-item nav-link active"><i class="fa fa-list me-2"></i>Resources</a>
                    <a href="managers.php" class="nav-item nav-link"><i class="fa fa-user me-2"></i>Managers</a>
                    <a href="suppliers.php" class="nav-item nav-link"><i class="fa fa-truck me-2"></i>Suppliers</a>
                    <a href="events.php" class="nav-item nav-link"><i class="fa fa-calendar-check me-2"></i>Events</a>
                </div>
            </nav>
        </div>
        <!-- Sidebar End -->

        <!-- Content Start -->
        <div class="content">
            <!-- Navbar Start -->
            <nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">
                <a href="index.html" class="navbar-brand d-flex d-lg-none me-4"></a>
                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>
                <div>
                    <form class="d-none d-md-flex ms-4">
                        <input class="form-control border-0" id="searchInput" type="search" placeholder="Search">
                    </form>
                </div>

                <div class="navbar-nav align-items-center ms-auto">
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" style="margin:8px">
                            <span class="d-none d-lg-inline-flex"><?php echo $account->nom_utilisateur.' '.$account->prenom_utilisateur; ?></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                            <a href="profile.php" class="dropdown-item">My Profile</a>
                            <a href="core/user/deconnection.php" class="dropdown-item">Log Out</a>
                        </div>
                    </div>
                </div>
            </nav>
            <div class="dropdown-menu" id="searchResults" style="width: 40%;"></div>
            <!-- Navbar End -->

            <!-- Recent Sales Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="bg-light text-center rounded p-4" style="min-height:430px">
                    <div id="display" class="table-responsive"></div>
                </div>
            </div>
            <!-- Recent Sales End -->

            <!-- Footer Start -->
            <div class="container-fluid pt-4 px-4">
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
            </div>
            <!-- Footer End -->
        </div>
        <!-- Content End -->

        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
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
    <script src="js/ressource.js"></script>
    <script>
        $(document).ready(function () {
    $('#searchInput').on('keyup', function() {
        var queryressource = $(this).val();
        if (queryressource.length > 0) {
            $.ajax({
                url: 'search.php',
                method: 'POST',
                data: { queryressource: queryressource },
                success: function(data) {
                    $('#searchResults').html(data).show();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        } else {
            $('#searchResults').hide();
        }
    });

    // Hide dropdown when clicking outside
    $(document).on('click', function(ressource) {
        if (!$(ressource.target).closest('#searchInput').length) {
            $('#searchResults').hide();
        }
    });

    $('#searchResults').on('click', '.result-item', function() {
        var id = $(this).attr('id');
        $('td[data-id="' + id + '"]').css('background-color', 'yellow');
    });
});

    </script>
</body>

</html>
