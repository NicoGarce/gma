<?php
/**
 * UPHSL GMA Campus BS Accountancy Program Page
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

if (isSectionInMaintenance('programs', 'bs-accountancy') || isSectionInMaintenance('programs')) {
    $page_title = "BS Accountancy - Maintenance";
    $base_path = '../';
    include '../app/includes/header.php';
    if (displaySectionMaintenance('programs', $base_path, 'bs-accountancy')) {
        include '../app/includes/footer.php';
        exit;
    }
}

$page_title = "BS Accountancy - GMA Campus";
$base_path = '../';
include '../app/includes/header.php';
?>

    <section class="page-header" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); color: white; padding: 80px 0;">
        <div class="container">
            <div class="banner-content">
                <h1>Bachelor of Science in Accountancy</h1>
                <p>Comprehensive accounting program preparing students for CPA licensure at UPHSL GMA Campus</p>
            </div>
        </div>
    </section>

    <main class="main-content">
        <div class="container">
            <div class="content-wrapper">
                <div class="content-main">
                    <?php
                    // Get category ID for this program page
                    $programSlug = 'bs-accountancy';
                    $categoryId = getCategoryIdByProgramSlug($programSlug);
                    
                    // Include news carousel if category exists and has posts
                    if ($categoryId) {
                        $sectionTitle = 'Latest ' . getCategoryById($categoryId)['name'] . ' News';
                        $sectionDescription = 'Stay updated with the latest news and updates from the BS Accountancy program.';
                        $hideFacebook = true; // Hide Facebook feed on program pages
                        include '../app/includes/news-carousel.php';
                    }
                    ?>
                    
                    <article class="content-article">
                        <div class="mission-vision-section">
                            <h2>About the Program</h2>
                            <p>The Bachelor of Science in Accountancy program at UPHSL GMA Campus prepares students for CPA licensure and financial management careers. Students master accounting principles, auditing, and financial reporting.</p>
                            
                            <h2>Career Opportunities</h2>
                            <ul>
                                <li>Certified Public Accountant (CPA)</li>
                                <li>Auditor</li>
                                <li>Financial Analyst</li>
                                <li>Tax Consultant</li>
                                <li>Management Accountant</li>
                                <li>Internal Auditor</li>
                            </ul>
                        </div>
                    </article>
                </div>
                
                <aside class="content-sidebar">
                    <div class="sidebar-widget">
                        <h3>Quick Facts</h3>
                        <ul>
                            <li><strong>Duration:</strong> 4 years</li>
                            <li><strong>Degree:</strong> Bachelor of Science</li>
                            <li><strong>Campus:</strong> GMA Campus</li>
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </main>

<?php include '../app/includes/footer.php'; ?>




