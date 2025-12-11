<?php
/**
 * UPHSL GMA Campus BS Engineering Program Page
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

if (isSectionInMaintenance('programs', 'bs-engineering') || isSectionInMaintenance('programs')) {
    $page_title = "BS Engineering - Maintenance";
    $base_path = '../';
    include '../app/includes/header.php';
    if (displaySectionMaintenance('programs', $base_path, 'bs-engineering')) {
        include '../app/includes/footer.php';
        exit;
    }
}

$page_title = "BS Engineering - GMA Campus";
$base_path = '../';
include '../app/includes/header.php';
?>

    <section class="page-header" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); color: white; padding: 80px 0;">
        <div class="container">
            <div class="banner-content">
                <h1>Bachelor of Science in Engineering</h1>
                <p>Comprehensive engineering program covering various engineering disciplines at UPHSL GMA Campus</p>
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
                            <p>The Bachelor of Science in Engineering program at UPHSL GMA Campus covers various engineering disciplines and technical applications. Students develop strong analytical and problem-solving skills.</p>
                            
                            <h2>Career Opportunities</h2>
                            <ul>
                                <li>Engineer (various specializations)</li>
                                <li>Project Manager</li>
                                <li>Design Engineer</li>
                                <li>Quality Assurance Engineer</li>
                                <li>Research and Development Engineer</li>
                            </ul>
                        </div>
                    </article>
                </div>
                
                <aside class="content-sidebar">
                    <div class="sidebar-widget">
                        <h3>Quick Facts</h3>
                        <ul>
                            <li><strong>Duration:</strong> 4-5 years</li>
                            <li><strong>Degree:</strong> Bachelor of Science</li>
                            <li><strong>Campus:</strong> GMA Campus</li>
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </main>

<?php include '../app/includes/footer.php'; ?>


