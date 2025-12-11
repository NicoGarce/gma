<?php
/**
 * UPHSL GMA Campus BS Hospitality Management Program Page
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

if (isSectionInMaintenance('programs', 'bs-hospitality-management') || isSectionInMaintenance('programs')) {
    $page_title = "BS Hospitality Management - Maintenance";
    $base_path = '../';
    include '../app/includes/header.php';
    if (displaySectionMaintenance('programs', $base_path, 'bs-hospitality-management')) {
        include '../app/includes/footer.php';
        exit;
    }
}

$page_title = "BS Hospitality Management - GMA Campus";
$base_path = '../';
include '../app/includes/header.php';
?>

    <section class="page-header" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); color: white; padding: 80px 0;">
        <div class="container">
            <div class="banner-content">
                <h1>Bachelor of Science in Hospitality Management</h1>
                <p>Program focused on hotel and restaurant management with international standards at UPHSL GMA Campus</p>
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
                            <p>The Bachelor of Science in Hospitality Management program at UPHSL GMA Campus focuses on hotel and restaurant management with international standards. Students learn operations, customer service, and business management.</p>
                            
                            <h2>Career Opportunities</h2>
                            <ul>
                                <li>Hotel Manager</li>
                                <li>Restaurant Manager</li>
                                <li>Event Coordinator</li>
                                <li>Food and Beverage Manager</li>
                                <li>Hospitality Consultant</li>
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


