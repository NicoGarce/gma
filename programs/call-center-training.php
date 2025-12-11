<?php
/**
 * UPHSL GMA Campus Call Center Training Program Page
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

if (isSectionInMaintenance('programs', 'call-center-training') || isSectionInMaintenance('programs')) {
    $page_title = "Call Center Training - Maintenance";
    $base_path = '../';
    include '../app/includes/header.php';
    if (displaySectionMaintenance('programs', $base_path, 'call-center-training')) {
        include '../app/includes/footer.php';
        exit;
    }
}

$page_title = "Call Center Training Program - GMA Campus";
$base_path = '../';
include '../app/includes/header.php';
?>

    <section class="page-header" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); color: white; padding: 80px 0;">
        <div class="container">
            <div class="banner-content">
                <h1>Call Center Training Program</h1>
                <p>Comprehensive training program preparing students for careers in customer service and call center operations at UPHSL GMA Campus</p>
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
                            <p>The Call Center Training Program at UPHSL GMA Campus provides comprehensive training for careers in customer service and call center operations. Students develop communication, problem-solving, and customer service skills.</p>
                            
                            <h2>Career Opportunities</h2>
                            <ul>
                                <li>Call Center Agent</li>
                                <li>Customer Service Representative</li>
                                <li>Technical Support Specialist</li>
                                <li>Team Leader</li>
                                <li>Quality Assurance Specialist</li>
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


