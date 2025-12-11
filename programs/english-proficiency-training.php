<?php
/**
 * UPHSL GMA Campus English Proficiency Training Program Page
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

if (isSectionInMaintenance('programs', 'english-proficiency-training') || isSectionInMaintenance('programs')) {
    $page_title = "English Proficiency Training - Maintenance";
    $base_path = '../';
    include '../app/includes/header.php';
    if (displaySectionMaintenance('programs', $base_path, 'english-proficiency-training')) {
        include '../app/includes/footer.php';
        exit;
    }
}

$page_title = "English Proficiency Training - GMA Campus";
$base_path = '../';
include '../app/includes/header.php';
?>

    <section class="page-header" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); color: white; padding: 80px 0;">
        <div class="container">
            <div class="banner-content">
                <h1>English Proficiency Training</h1>
                <p>Intensive English language training program to enhance communication skills and proficiency at UPHSL GMA Campus</p>
            </div>
        </div>
    </section>

    <main class="main-content">
        <div class="container">
            <div class="content-wrapper">
                <div class="content-main">
                    <?php
                    // Get category ID for this program page
                    $programSlug = 'english-proficiency-training';
                    $categoryId = getCategoryIdByProgramSlug($programSlug);
                    
                    // Include news carousel if category exists and has posts
                    if ($categoryId) {
                        $sectionTitle = 'Latest ' . getCategoryById($categoryId)['name'] . ' News';
                        $sectionDescription = 'Stay updated with the latest news and updates from the English Proficiency Training program.';
                        $hideFacebook = true; // Hide Facebook feed on program pages
                        include '../app/includes/news-carousel.php';
                    }
                    ?>
                    
                    <article class="content-article">
                        <div class="mission-vision-section">
                            <h2>About the Program</h2>
                            <p>The English Proficiency Training program at UPHSL GMA Campus provides intensive English language training to enhance communication skills and proficiency. Students develop speaking, writing, reading, and listening skills.</p>
                            
                            <h2>Program Benefits</h2>
                            <ul>
                                <li>Improved English communication skills</li>
                                <li>Enhanced career opportunities</li>
                                <li>Better academic performance</li>
                                <li>Increased confidence in English usage</li>
                            </ul>
                        </div>
                    </article>
                </div>
                
                <aside class="content-sidebar">
                    <div class="sidebar-widget">
                        <h3>Quick Facts</h3>
                        <ul>
                            <li><strong>Type:</strong> Training Program</li>
                            <li><strong>Campus:</strong> GMA Campus</li>
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </main>

<?php include '../app/includes/footer.php'; ?>




