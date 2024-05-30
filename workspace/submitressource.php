<?php
session_start(); ob_start();
$account = (object) $_SESSION['account'];

// Include the core_manager class for database connection
include_once('core.php');

// Connect to the database
$pdo = core_manager::generate_pdo();

// Handle form submission (if submitted)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
  $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
  $lieu = filter_var($_POST['lieu'], FILTER_SANITIZE_STRING);
  $type = filter_var($_POST['type'], FILTER_SANITIZE_STRING);
  $quantite = filter_var($_POST['quantite'], FILTER_SANITIZE_STRING);
  $prix = filter_var($_POST['prix'], FILTER_SANITIZE_STRING);
  $file = filter_var($_POST['file'], FILTER_SANITIZE_STRING);
  $id= $account->id_utilisateur;
  
  // Validate form data (add your own validation rules here)
  $errors = [];

  if (empty($name)) {
    $errors[] = "Ressource name is required.";
  }

  if (empty($type)) {
    $errors[] = "Ressource type is required.";
  }

  if (empty($description)) {
    $errors[] = "Ressource description is required.";
  }

  if (empty($lieu)) {
    $errors[] = "Location is required.";
  }
  if (empty($prix)) {
    $errors[] = "Location is required.";
  }


  // You can add further validation for dates (format, consistency)


  if (empty($errors)) {

    // Prepare and execute insert query
    $stmt = $pdo->prepare("INSERT INTO ressource(nom_ressource, type_ressource, description_ressource, lieu_ressource, image_ressource, quantite_ressource,prix_ressource,fournisseur_ressource) 
      VALUES (:name, :type, :description, :lieu, :file, :quantite,:prix,:idfournisseur)");

    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':type', $type, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->bindParam(':lieu', $lieu, PDO::PARAM_STR);
    $stmt->bindParam(':quantite', $quantite, PDO::PARAM_STR);
    $stmt->bindParam(':prix', $prix, PDO::PARAM_STR);
    $stmt->bindParam(':file', $file, PDO::PARAM_STR);
    $stmt->bindParam(':idfournisseur', $id, PDO::PARAM_STR);


    $success = $stmt->execute();

    if ($success) {
      // Event creation successful
      $_SESSION['add_ressource_success'] = true;
      header('location: index_fournisseur.php'); // Replace with your desired redirect
      exit;
    } else {
      // Event creation failed
      $errors[] = "An error occurred during adding ressources. Please try again.";
    }
  }
}
?>