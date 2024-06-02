<?php
session_start();
ob_start();
$account = isset($_SESSION['account']) ? (object)$_SESSION['account'] : header('location: core/user/deconnection.php');

// Include the core_manager class for database connection
include_once('core.php');

// Connect to the database
$pdo = core_manager::generate_pdo();

// Handle form submission (if submitted)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Form data
    $titre = filter_var($_POST['titre'], FILTER_SANITIZE_STRING);
    $type = filter_var($_POST['type'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $datedebut = filter_var($_POST['datedebut'], FILTER_SANITIZE_STRING);
    $datefin = filter_var($_POST['datefin'], FILTER_SANITIZE_STRING);
    $lieu = filter_var($_POST['lieu'], FILTER_SANITIZE_STRING);
    $id = $account->id_utilisateur;

    // Validate form data
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

    if (empty($lieu)) {
        $errors[] = "Location is required.";
    }

    if (empty($errors)) {
        // Prepare and execute insert query
        $stmt = $pdo->prepare("INSERT INTO evenement (titre_evenement, type_evenement, description_evenement, date_debut_evenement, date_fin_evenement, lieu_evenement, client_evenement) 
            VALUES (:titre, :type, :description, :datedebut, :datefin, :lieu, :idclient)");
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
                $stmt_ressource = $pdo->prepare("INSERT INTO ressource_demanded (id_evenement, quantite_ressource, type_ressource) 
                    VALUES (:id_evenement, :quantite_ressource, :type_ressource)");
                $stmt_ressource->bindParam(':id_evenement', $event_id, PDO::PARAM_INT);
                $stmt_ressource->bindParam(':type_ressource', $selected_ressource, PDO::PARAM_STR);
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

// Handle file upload
if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $tempFile = $_FILES['image']['tmp_name'];
    $fileName = $_FILES['image']['name'];
    $targetFile = "img";/* specify the directory where you want to save the uploaded image */

    // Move the uploaded file to the target location
    if (move_uploaded_file($tempFile, $targetFile)) {
        // File upload successful, proceed to save file data to the database

        // Insert image data into the photo table
        $stmt_photo = $pdo->prepare("INSERT INTO photo (photo) VALUES (:photo)");
        $stmt_photo->bindParam(':photo', $fileName, PDO::PARAM_STR);
        $success_photo = $stmt_photo->execute();

        if ($success_photo) {
            // Get the ID of the saved image
            $imageId = $pdo->lastInsertId();

            // Insert event data along with the image ID into the evenement table
            $stmt_event = $pdo->prepare("UPDATE evenement SET id_photo = :idphoto WHERE id_evenement = :idevent");
            $stmt_event->bindParam(':idphoto', $imageId, PDO::PARAM_INT);
            $stmt_event->bindParam(':idevent', $event_id, PDO::PARAM_INT); // Use the ID of the newly created event
            $success_event = $stmt_event->execute();

            if ($success_event) {
                // Event updated with image ID
            } else {
                // Event update failed
            }
        } else {
            // Image insertion failed
        }
    } else {
        // File upload failed
    }
}
?>
