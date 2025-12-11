<?php
/**
 * UPHSL GMA Campus Basic Education Program Page
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

if (isSectionInMaintenance('programs', 'basic-education') || isSectionInMaintenance('programs')) {
    $page_title = "Basic Education - Maintenance";
    $base_path = '../';
    include '../app/includes/header.php';
    if (displaySectionMaintenance('programs', $base_path, 'basic-education')) {
        include '../app/includes/footer.php';
        exit;
    }
}

$page_title = "Basic Education - GMA Campus";
$base_path = '../';
include '../app/includes/header.php';
?>

    <section class="page-header" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); color: white; padding: 80px 0;">
        <div class="container">
            <div class="banner-content">
                <h1>Basic Education</h1>
                <p>Pre-School, Grade School, Middle School, and Junior High School programs at UPHSL GMA Campus</p>
            </div>
        </div>
    </section>

    <main class="main-content">
        <div class="container">
            <div class="content-wrapper">
                <div class="content-main">
                    <?php
                    // Get category ID for this program page
                    $programSlug = 'basic-education';
                    $categoryId = getCategoryIdByProgramSlug($programSlug);
                    
                    // Include news carousel if category exists and has posts
                    if ($categoryId) {
                        $sectionTitle = 'Latest ' . getCategoryById($categoryId)['name'] . ' News';
                        $sectionDescription = 'Stay updated with the latest news and updates from the Basic Education program.';
                        $hideFacebook = true; // Hide Facebook feed on program pages
                        include '../app/includes/news-carousel.php';
                    }
                    ?>
                    
                    <article class="content-article">
                        <div class="mission-vision-section">
                            <h2>About the Program</h2>
                            <p>The Basic Education program at UPHSL GMA Campus includes Pre-School, Grade School, Middle School, and Junior High School. These programs build strong academic and character foundations for young learners.</p>
                            
                            <h2>Programs Offered</h2>
                            <ul>
                                <li>Pre-School</li>
                                <li>Grade School (K-6)</li>
                                <li>Middle School</li>
                                <li>Junior High School (7-10)</li>
                            </ul>
                        </div>
                    </article>
                </div>
                
                <aside class="content-sidebar">
                    <div class="sidebar-widget">
                        <h3>Quick Facts</h3>
                        <ul>
                            <li><strong>Levels:</strong> Pre-School to Junior High</li>
                            <li><strong>Campus:</strong> GMA Campus</li>
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </main>

<?php include '../app/includes/footer.php'; ?>




