<?php
/**
 * UPHSL GMA Campus BS Information Technology Program Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Information about the BS Information Technology program at UPHSL GMA Campus
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Check if this sub-page or Programs section is in maintenance
if (isSectionInMaintenance('programs', 'bs-information-technology') || isSectionInMaintenance('programs')) {
    $page_title = "BS Information Technology - Maintenance";
    $base_path = '../';
    include '../app/includes/header.php';
    if (displaySectionMaintenance('programs', $base_path, 'bs-information-technology')) {
        include '../app/includes/footer.php';
        exit;
    }
}

// Set page title
$page_title = "BS Information Technology - GMA Campus";

// Set base path for assets
$base_path = '../';

// Include header
include '../app/includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); color: white; padding: 80px 0;">
        <div class="container">
            <div class="banner-content">
                <h1>Bachelor of Science in Information Technology</h1>
                <p>Comprehensive IT program covering systems, networking, and software development at UPHSL GMA Campus</p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="content-wrapper">
                <div class="content-main">
                    <?php
                    // Get category ID for this program page
                    $programSlug = 'bs-information-technology';
                    $categoryId = getCategoryIdByProgramSlug($programSlug);
                    
                    // Include news carousel if category exists and has posts
                    if ($categoryId) {
                        $sectionTitle = 'Latest ' . getCategoryById($categoryId)['name'] . ' News';
                        $sectionDescription = 'Stay updated with the latest news and updates from the BS Information Technology program.';
                        $hideFacebook = true; // Hide Facebook feed on program pages
                        include '../app/includes/news-carousel.php';
                    }
                    ?>
                    
                    <article class="content-article">
                        <div class="mission-vision-section">
                            <h2>About the Program</h2>
                            <p>The Bachelor of Science in Information Technology program at UPHSL GMA Campus provides students with comprehensive knowledge in IT systems administration, networking, database management, and software development. The program prepares students for careers in the rapidly evolving technology industry.</p>
                            
                            <h2>Program Objectives</h2>
                            <ul>
                                <li>Develop expertise in IT systems and infrastructure</li>
                                <li>Master networking and security principles</li>
                                <li>Learn software development and database management</li>
                                <li>Apply IT solutions to real-world problems</li>
                                <li>Prepare for industry certifications and professional practice</li>
                            </ul>

                            <h2>Career Opportunities</h2>
                            <p>Graduates can pursue careers as:</p>
                            <ul>
                                <li>Systems Administrator</li>
                                <li>Network Administrator</li>
                                <li>Database Administrator</li>
                                <li>Software Developer</li>
                                <li>IT Support Specialist</li>
                                <li>Web Developer</li>
                                <li>IT Project Manager</li>
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




