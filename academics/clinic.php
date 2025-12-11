<?php
/**
 * UPHSL University Clinic Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Information about the University Clinic services and facilities
 */
session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Check if this sub-page or Support Services section is in maintenance
if (isSectionInMaintenance('academics', 'clinic') || isSectionInMaintenance('academics')) {
    $page_title = "University Clinic - Maintenance";
    $base_path = '../';
    include '../app/includes/header.php';
    if (displaySectionMaintenance('academics', $base_path, 'clinic')) {
        include '../app/includes/footer.php';
        exit;
    }
}

$page_title = "University Clinic - UPHSL";
// Set base path for subdirectory
$base_path = '../';
// Include header
include '../app/includes/header.php';
?>

    <!-- Main Content -->
    <main class="main-content">
        <!-- News Carousel Section -->
        <?php
        // Get category ID for this academics page
        $academicsSlug = 'clinic';
        $categoryId = getCategoryIdByAcademicsSlug($academicsSlug);
        
        // Include news carousel if category exists and has posts
        if ($categoryId) {
            $sectionTitle = 'Latest ' . getCategoryById($categoryId)['name'] . ' News';
            $sectionDescription = 'Stay updated with the latest news and announcements from the University Clinic.';
            $hideFacebook = true; // Hide Facebook feed on academics pages
            include '../app/includes/news-carousel.php';
        }
        ?>

        <?php include '../app/includes/general-coming-soon.php'; ?>
    </main>

<?php
// Include footer
include '../app/includes/footer.php';
?>
