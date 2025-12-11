<?php
/**
 * UPHSL GMA Campus BS Computer Science Program Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Information about the BS Computer Science program at UPHSL GMA Campus
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Check if this sub-page or Programs section is in maintenance
if (isSectionInMaintenance('programs', 'bs-computer-science') || isSectionInMaintenance('programs')) {
    $page_title = "BS Computer Science - Maintenance";
    $base_path = '../';
    include '../app/includes/header.php';
    if (displaySectionMaintenance('programs', $base_path, 'bs-computer-science')) {
        include '../app/includes/footer.php';
        exit;
    }
}

// Set page title
$page_title = "BS Computer Science - GMA Campus";

// Set base path for assets
$base_path = '../';

// Include header
include '../app/includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); color: white; padding: 80px 0;">
        <div class="container">
            <div class="banner-content">
                <h1>Bachelor of Science in Computer Science</h1>
                <p>Program focused on algorithms, programming, software engineering, and computer systems at UPHSL GMA Campus</p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="content-wrapper">
                <div class="content-main">
                    <article class="content-article">
                        <div class="mission-vision-section">
                            <h2>About the Program</h2>
                            <p>The Bachelor of Science in Computer Science program at UPHSL GMA Campus focuses on algorithms, programming languages, software engineering, and computer systems. Students develop strong problem-solving skills and learn to design and implement software solutions.</p>
                            
                            <h2>Program Objectives</h2>
                            <ul>
                                <li>Master programming languages and software development</li>
                                <li>Understand algorithms and data structures</li>
                                <li>Learn software engineering principles</li>
                                <li>Develop problem-solving and analytical skills</li>
                                <li>Prepare for careers in software development and research</li>
                            </ul>

                            <h2>Career Opportunities</h2>
                            <p>Graduates can work as:</p>
                            <ul>
                                <li>Software Engineer</li>
                                <li>Software Developer</li>
                                <li>Systems Analyst</li>
                                <li>Application Developer</li>
                                <li>Computer Programmer</li>
                                <li>Software Architect</li>
                                <li>Research Scientist</li>
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
                    
                    <div class="sidebar-widget">
                        <h3>Contact</h3>
                        <p>For more information, please contact:</p>
                        <p><strong>Email:</strong> <a href="mailto:admission@gma.uphsl.edu.ph">admission@gma.uphsl.edu.ph</a></p>
                        <p><strong>Phone:</strong> (046) 460-4086</p>
                    </div>
                </aside>
            </div>
        </div>
    </main>

<?php
// Include footer
include '../app/includes/footer.php';
?>


