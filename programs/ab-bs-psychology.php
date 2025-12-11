<?php
/**
 * UPHSL GMA Campus AB/BS Psychology Program Page
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

if (isSectionInMaintenance('programs', 'ab-bs-psychology') || isSectionInMaintenance('programs')) {
    $page_title = "AB/BS Psychology - Maintenance";
    $base_path = '../';
    include '../app/includes/header.php';
    if (displaySectionMaintenance('programs', $base_path, 'ab-bs-psychology')) {
        include '../app/includes/footer.php';
        exit;
    }
}

$page_title = "AB/BS Psychology - GMA Campus";
$base_path = '../';
include '../app/includes/header.php';
?>

    <section class="page-header" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); color: white; padding: 80px 0;">
        <div class="container">
            <div class="banner-content">
                <h1>AB/BS Psychology</h1>
                <p>Program studying human behavior, mental processes, and psychological principles at UPHSL GMA Campus</p>
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
                            <p>The AB/BS Psychology program at UPHSL GMA Campus studies human behavior, mental processes, and psychological principles. Students develop understanding of human cognition, emotion, and behavior.</p>
                            
                            <h2>Career Opportunities</h2>
                            <ul>
                                <li>Psychologist (with further studies)</li>
                                <li>Human Resources Specialist</li>
                                <li>Counselor</li>
                                <li>Research Assistant</li>
                                <li>Mental Health Worker</li>
                            </ul>
                        </div>
                    </article>
                </div>
                
                <aside class="content-sidebar">
                    <div class="sidebar-widget">
                        <h3>Quick Facts</h3>
                        <ul>
                            <li><strong>Duration:</strong> 4 years</li>
                            <li><strong>Degree:</strong> Bachelor of Arts/Bachelor of Science</li>
                            <li><strong>Campus:</strong> GMA Campus</li>
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </main>

<?php include '../app/includes/footer.php'; ?>


