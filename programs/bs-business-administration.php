<?php
/**
 * UPHSL GMA Campus BS Business Administration Program Page
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

if (isSectionInMaintenance('programs', 'bs-business-administration') || isSectionInMaintenance('programs')) {
    $page_title = "BS Business Administration - Maintenance";
    $base_path = '../';
    include '../app/includes/header.php';
    if (displaySectionMaintenance('programs', $base_path, 'bs-business-administration')) {
        include '../app/includes/footer.php';
        exit;
    }
}

$page_title = "BS Business Administration - GMA Campus";
$base_path = '../';
include '../app/includes/header.php';
?>

    <section class="page-header" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); color: white; padding: 80px 0;">
        <div class="container">
            <div class="banner-content">
                <h1>Bachelor of Science in Business Administration</h1>
                <p>Program covering management, marketing, operations, and strategic business planning at UPHSL GMA Campus</p>
            </div>
        </div>
    </section>

    <main class="main-content">
        <div class="container">
            <div class="content-wrapper">
                <div class="content-main">
                    <article class="content-article">
                        <div class="mission-vision-section">
                            <h2>About the Program</h2>
                            <p>The Bachelor of Science in Business Administration program at UPHSL GMA Campus covers management, marketing, operations, and strategic business planning. Students develop leadership and entrepreneurial skills.</p>
                            
                            <h2>Career Opportunities</h2>
                            <ul>
                                <li>Business Manager</li>
                                <li>Marketing Manager</li>
                                <li>Operations Manager</li>
                                <li>Entrepreneur</li>
                                <li>Business Consultant</li>
                                <li>Sales Manager</li>
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


