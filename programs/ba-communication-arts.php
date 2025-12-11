<?php
/**
 * UPHSL GMA Campus BA Communication Arts Program Page
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

if (isSectionInMaintenance('programs', 'ba-communication-arts') || isSectionInMaintenance('programs')) {
    $page_title = "BA Communication Arts - Maintenance";
    $base_path = '../';
    include '../app/includes/header.php';
    if (displaySectionMaintenance('programs', $base_path, 'ba-communication-arts')) {
        include '../app/includes/footer.php';
        exit;
    }
}

$page_title = "BA Communication Arts - GMA Campus";
$base_path = '../';
include '../app/includes/header.php';
?>

    <section class="page-header" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); color: white; padding: 80px 0;">
        <div class="container">
            <div class="banner-content">
                <h1>Bachelor of Arts in Communication Arts</h1>
                <p>Media, journalism, broadcasting, and digital communication at UPHSL GMA Campus</p>
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
                            <p>The Bachelor of Arts in Communication Arts program at UPHSL GMA Campus covers media, journalism, broadcasting, and digital communication. Students develop skills in writing, production, and media analysis.</p>
                            
                            <h2>Career Opportunities</h2>
                            <ul>
                                <li>Journalist</li>
                                <li>Broadcaster</li>
                                <li>Media Producer</li>
                                <li>Public Relations Specialist</li>
                                <li>Content Creator</li>
                                <li>Digital Media Specialist</li>
                            </ul>
                        </div>
                    </article>
                </div>
                
                <aside class="content-sidebar">
                    <div class="sidebar-widget">
                        <h3>Quick Facts</h3>
                        <ul>
                            <li><strong>Duration:</strong> 4 years</li>
                            <li><strong>Degree:</strong> Bachelor of Arts</li>
                            <li><strong>Campus:</strong> GMA Campus</li>
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </main>

<?php include '../app/includes/footer.php'; ?>


