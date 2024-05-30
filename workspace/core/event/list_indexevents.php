<?php
session_start();
ob_start();
require_once '../core.php';
$pdo = core_manager::generate_pdo();
$stmt = $pdo->prepare("SELECT * FROM evenement");
$stmt->execute();

$html = '';
if ($stmt && $stmt->rowCount() > 0) {
    while ($event = $stmt->fetchObject()) {
        // Check if image_evenement is not null or empty
        $imageSrc = !empty($event->image_evenement) ? 'workspace/core/event/display_image.php?id=' . htmlspecialchars($event->id_evenement) : 'img/about-1.jpg'; // Default image path

        $html .= '
        <div class="col-md-6">
            <div class="about-des-col">
                <div class="about-img">
                    <img class="img-fluid" src="' . $imageSrc . '" style="object-fit: cover; width: 555px; height: 277.5px;" />
                </div>
                <h3 class="eventtitle">' . htmlspecialchars($event->titre_evenement) . '</h3>
                <div style="text-align: justify; margin-bottom: 20px; height: 150px; max-height: 150px; padding: 20px; color: white; overflow: hidden;">
                    ' . htmlspecialchars($event->description_evenement) . '
                </div>
                <a href="AIevent.html">See Details</a>
            </div>
        </div>';
    }
}
echo $html;
