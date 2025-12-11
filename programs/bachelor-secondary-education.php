<?php
/**
 * UPHSL GMA Campus Bachelor of Secondary Education Program Page
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

if (isSectionInMaintenance('programs', 'bachelor-secondary-education') || isSectionInMaintenance('programs')) {
    $page_title = "Bachelor of Secondary Education - Maintenance";
    $base_path = '../';
    include '../app/includes/header.php';
    if (displaySectionMaintenance('programs', $base_path, 'bachelor-secondary-education')) {
        include '../app/includes/footer.php';
        exit;
    }
}

$page_title = "Bachelor of Secondary Education - GMA Campus";
$base_path = '../';
include '../app/includes/header.php';
?>

    <section class="page-header" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); color: white; padding: 80px 0;">
        <div class="container">
            <div class="banner-content">
                <h1>Bachelor of Secondary Education</h1>
                <p>Program preparing future high school teachers with subject specialization at UPHSL GMA Campus</p>
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
                            <p>The Bachelor of Secondary Education program at UPHSL GMA Campus prepares future high school teachers with subject specialization and pedagogical expertise. Students develop teaching skills and subject mastery.</p>
                            
                            <h2>Career Opportunities</h2>
                            <ul>
                                <li>High School Teacher</li>
                                <li>Subject Specialist</li>
                                <li>Curriculum Developer</li>
                                <li>Educational Administrator</li>
                                <li>Instructional Designer</li>
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


