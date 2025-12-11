<?php
/**
 * UPHSL GMA Campus Bachelor of Elementary Education Program Page
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

if (isSectionInMaintenance('programs', 'bachelor-elementary-education') || isSectionInMaintenance('programs')) {
    $page_title = "Bachelor of Elementary Education - Maintenance";
    $base_path = '../';
    include '../app/includes/header.php';
    if (displaySectionMaintenance('programs', $base_path, 'bachelor-elementary-education')) {
        include '../app/includes/footer.php';
        exit;
    }
}

$page_title = "Bachelor of Elementary Education - GMA Campus";
$base_path = '../';
include '../app/includes/header.php';
?>

    <section class="page-header" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); color: white; padding: 80px 0;">
        <div class="container">
            <div class="banner-content">
                <h1>Bachelor of Elementary Education</h1>
                <p>Program preparing future elementary school teachers at UPHSL GMA Campus</p>
            </div>
        </div>
    </section>

    <main class="main-content">
        <div class="container">
            <div class="content-wrapper">
                <div class="content-main">
                    <?php
                    // Get category ID for this program page
                    $programSlug = 'bachelor-elementary-education';
                    $categoryId = getCategoryIdByProgramSlug($programSlug);
                    
                    // Include news carousel if category exists and has posts
                    if ($categoryId) {
                        $sectionTitle = 'Latest ' . getCategoryById($categoryId)['name'] . ' News';
                        $sectionDescription = 'Stay updated with the latest news and updates from the Bachelor of Elementary Education program.';
                        $hideFacebook = true; // Hide Facebook feed on program pages
                        include '../app/includes/news-carousel.php';
                    }
                    ?>
                    
                    <article class="content-article">
                        <div class="mission-vision-section">
                            <h2>About the Program</h2>
                            <p>The Bachelor of Elementary Education program at UPHSL GMA Campus prepares future elementary school teachers with comprehensive pedagogical training. Students develop teaching skills for young learners.</p>
                            
                            <h2>Career Opportunities</h2>
                            <ul>
                                <li>Elementary School Teacher</li>
                                <li>Grade School Teacher</li>
                                <li>Curriculum Developer</li>
                                <li>Educational Administrator</li>
                            </ul>
                        </div>
                    </article>
                </div>
                
                <aside class="content-sidebar">
                    <div class="sidebar-widget">
                        <h3>Quick Facts</h3>
                        <ul>
                            <li><strong>Duration:</strong> 4 years</li>
                            <li><strong>Degree:</strong> Bachelor</li>
                            <li><strong>Campus:</strong> GMA Campus</li>
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </main>

<?php include '../app/includes/footer.php'; ?>




