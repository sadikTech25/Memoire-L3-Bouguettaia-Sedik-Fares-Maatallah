<?php
session_start(); ob_start();
$account = (object) $_SESSION['account'];

// Include the core_manager class for database connection
include_once('core.php');

// Connect to the database
$pdo = core_manager::generate_pdo();

// Handle form submission (if submitted)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // $object = (isset($_POST) && count($_POST) > 0) ? (object) $_POST : NULL;
  $titre = filter_var($_POST['titre'], FILTER_SANITIZE_STRING);
  $type = filter_var($_POST['type'], FILTER_SANITIZE_STRING);
  $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
  $datedebut = filter_var($_POST['datedebut'], FILTER_SANITIZE_STRING);
  $datefin = filter_var($_POST['datefin'], FILTER_SANITIZE_STRING);
  $lieu = filter_var($_POST['lieu'], FILTER_SANITIZE_STRING);
  $id= $account->id_utilisateur;
  // Validate form data (add your own validation rules here)
  $errors = [];

  if (empty($titre)) {
    $errors[] = "Event title is required.";
  }

  if (empty($type)) {
    $errors[] = "Event type is required.";
  }

  if (empty($description)) {
    $errors[] = "Event description is required.";
  }

  if (empty($datedebut)) {
    $errors[] = "Start date is required.";
  }

  if (empty($datefin)) {
    $errors[] = "End date is required.";
  }

  if (empty($errors)) {

    // Prepare and execute insert query
    $stmt = $pdo->prepare("INSERT INTO evenement (titre_evenement, type_evenement, description_evenement, date_debut_evenement, date_fin_evenement, lieu_evenement,client_evenement) 
      VALUES (:titre, :type, :description, :datedebut, :datefin, :lieu,:idclient)");

    $stmt->bindParam(':titre', $titre, PDO::PARAM_STR);
    $stmt->bindParam(':type', $type, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->bindParam(':datedebut', $datedebut, PDO::PARAM_STR);
    $stmt->bindParam(':datefin', $datefin, PDO::PARAM_STR);
    $stmt->bindParam(':lieu', $lieu, PDO::PARAM_STR);
    $stmt->bindParam(':idclient', $id, PDO::PARAM_STR);
    $success = $stmt->execute();

    if ($success) {
      // Event creation successful
      $event_id = $pdo->lastInsertId(); // Get the newly created event ID

      // Loop through each submitted resource (assuming multiple resources can be selected)
      foreach ($_POST['type_ressource'] as $key => $selected_ressource) {
        $selected_quantite = $_POST['quantite_ressource'][$key]; // Access corresponding quantity
    
        // Prepare and execute INSERT query for ressource_demandee
        $stmt_ressource = $pdo->prepare("INSERT INTO ressource_demanded (id_evenement, quantite_ressource,type_ressource) 
          VALUES (:id_evenement, :quantite_ressource, :type_ressource)");
        $selected_ressource_type = /* Get resource type based on $selected_ressource */
        $stmt_ressource->bindParam(':id_evenement', $event_id, PDO::PARAM_INT);
        $stmt_ressource->bindParam(':type_ressource', $selected_ressource_type, PDO::PARAM_STR);
        $stmt_ressource->bindParam(':quantite_ressource', $selected_quantite, PDO::PARAM_INT);
        $success_ressource = $stmt_ressource->execute();
      
      if (!$success_ressource) {
        $errors[] = "An error occurred during event creation. Please try again.";
        break; // Optionally stop further insertions if there's an error
      }
    }
    $_SESSION['event_creation_success'] = true;
      header('location: client_demanded_events.php'); // Replace with your desired redirect
      exit;
    } else {
      // Event creation failed
      $errors[] = "An error occurred during event creation. Please try again.";
    }
  }
}
?>