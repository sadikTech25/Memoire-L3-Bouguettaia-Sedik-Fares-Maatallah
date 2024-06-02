<?php session_start(); ob_start();
$account = isset($_SESSION['account']) ?  (object)$_SESSION['account'] : header('location: core/user/deconnection.php');
switch($account->type_utilisateur)
{
    case 2: header('location: index_client.php'); break;
    case 3: header('location: index_manager.php'); break;
    case 1: header('location: index_admin.php'); break;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Add materials - UltraEvent</title>
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
                <a href="index_fournisseur.php" class="navbar-brand mx-4 mb-3">
                    <img src="../img/logo.png" alt="UltraEvent Logo" width="150" height="60">
                </a>
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative">
                        <img class="rounded-circle" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                        <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0"><?php echo $account->nom_utilisateur.' '.$account->prenom_utilisateur; ?></h6>
                        <span>Supplier</span>
                    </div>
                </div>
                <div class="navbar-nav w-100">
                    <a href="index_fournisseur.php" class="nav-item nav-link"><i class="fa fa-calendar-times me-2"></i>My Ressources</a>
                    <a href="addressources.php" class="nav-item nav-link active"><i class="fas fa-plus me-2"></i>Add Ressources</a>
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
                
                <div class="navbar-nav align-items-center ms-auto" style="margin:13px">
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
            <!-- Navbar End -->
           
            <div class=" bg-light pt-4 px-4">
                <h6 class="mb-4">Add Materials</h6>
                <form action="submitressource.php" method="post">
                <div class="form-floating mb-3">
                    <input  name="name" class="form-control" id="floatingInput" placeholder="title">
                    <label for="floatingInput">Materials Name</label>
                </div>
                <div class="form-floating mb-3">
                    <textarea name="description" class="form-control" placeholder="Leave a comment here" id="floatingTextarea" style="height: 150px;"></textarea>
                    <label for="floatingTextarea">Description</label>
                </div>
                
                <div class="form-floating mb-3">
                    <input name="lieu" class="form-control" id="floatingInput" placeholder="title">
                    <label for="floatingInput">Location</label>
                </div>
                <div class="form-floating mb-3">
                    <select name="type" class="form-select" id="floatingSelect" aria-label="Floating label select example">
                            <option value="10">Tables</option>
                            <option value="1">Chairs</option>
                            <option value="2">Speakers</option>
                            <option value="3">Screens</option>
                            <option value="4">Photograph</option>
                            <option value="5">Security Agent</option>
                            <option value="6">Cars</option>
                            <option value="7">Salle Des Fétes</option>
                            <option value="8">Hotel</option>
                            <option value="9">Salle De Conference</option>


                    </select>
                    <label for="floatingSelect">Ressource Type</label>
                </div>
                <div class="form-floating mb-3">
                    <input name="quantite" type="number" class="form-control quantity-picker-input" id="floatingQuantity" value="1" aria-label="Quantity" required>
                  <label for="floatingQuantity">Select quantity</label>
                </div>
                <div class="form-floating mb-3">
                    <input name="prix" type="number" class="form-control quantity-picker-input" id="floatingQuantity" value="1" aria-label="Quantity" required>
                  <label for="floatingQuantity">Price DA</label>
                </div>
                <input name="file" class="form-control form-control-lg mb-3" id="formFileLg" type="file" required>
                <div class="form-floating mb-3">
                <button type="submit" class="btn btn-primary mb-3">Submit Materials</button>
                <button type="reset" class="btn btn-secondary mb-3">Reset</button>

            </div>
        </form>
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

    <!-- JavaScript Libraries -->

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
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
    <script src="js/addressource.js"></script>

</body>

</html>