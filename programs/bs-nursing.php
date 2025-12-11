<?php
/**
 * UPHSL GMA Campus BS Nursing Program Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Information about the BS Nursing program at UPHSL GMA Campus
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Check if this sub-page or Programs section is in maintenance
if (isSectionInMaintenance('programs', 'bs-nursing') || isSectionInMaintenance('programs')) {
    $page_title = "BS Nursing - Maintenance";
    $base_path = '../';
    include '../app/includes/header.php';
    if (displaySectionMaintenance('programs', $base_path, 'bs-nursing')) {
        include '../app/includes/footer.php';
        exit;
    }
}

// Set page title
$page_title = "BS Nursing - GMA Campus";

// Set base path for assets
$base_path = '../';

// Include header
include '../app/includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); color: white; padding: 80px 0;">
        <div class="container">
            <div class="banner-content">
                <h1>Bachelor of Science in Nursing</h1>
                <p>Comprehensive nursing education program at UPHSL GMA Campus</p>
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
                            <p>The Bachelor of Science in Nursing program at UPHSL GMA Campus is designed to prepare students for professional nursing practice. Our comprehensive curriculum combines theoretical knowledge with hands-on clinical experience to develop competent, compassionate, and ethical nursing professionals.</p>
                            
                            <h2>Program Objectives</h2>
                            <ul>
                                <li>Produce competent and compassionate nursing professionals</li>
                                <li>Develop critical thinking and clinical decision-making skills</li>
                                <li>Instill Christian values and ethical principles in nursing practice</li>
                                <li>Prepare graduates for licensure examinations and professional practice</li>
                                <li>Foster leadership and research capabilities in healthcare</li>
                            </ul>

                            <h2>Career Opportunities</h2>
                            <p>Graduates of the BS Nursing program can pursue careers as:</p>
                            <ul>
                                <li>Registered Nurse in hospitals and healthcare facilities</li>
                                <li>Community Health Nurse</li>
                                <li>School Nurse</li>
                                <li>Occupational Health Nurse</li>
                                <li>Nurse Educator</li>
                                <li>Nurse Researcher</li>
                                <li>Nurse Administrator</li>
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
                        <p>For more information about the BS Nursing program at GMA Campus, please contact:</p>
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


