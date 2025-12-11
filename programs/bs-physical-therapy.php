<?php
/**
 * UPHSL GMA Campus BS Physical Therapy Program Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Information about the BS Physical Therapy program at UPHSL GMA Campus
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Check if this sub-page or Programs section is in maintenance
if (isSectionInMaintenance('programs', 'bs-physical-therapy') || isSectionInMaintenance('programs')) {
    $page_title = "BS Physical Therapy - Maintenance";
    $base_path = '../';
    include '../app/includes/header.php';
    if (displaySectionMaintenance('programs', $base_path, 'bs-physical-therapy')) {
        include '../app/includes/footer.php';
        exit;
    }
}

// Set page title
$page_title = "BS Physical Therapy - GMA Campus";

// Set base path for assets
$base_path = '../';

// Include header
include '../app/includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); color: white; padding: 80px 0;">
        <div class="container">
            <div class="banner-content">
                <h1>Bachelor of Science in Physical Therapy</h1>
                <p>Rehabilitation and movement science program at UPHSL GMA Campus</p>
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
                            <p>The Bachelor of Science in Physical Therapy program at UPHSL GMA Campus focuses on rehabilitation, movement science, and therapeutic interventions. Students learn to assess, diagnose, and treat individuals with movement dysfunctions to improve their quality of life.</p>
                            
                            <h2>Program Objectives</h2>
                            <ul>
                                <li>Develop competent physical therapy professionals</li>
                                <li>Master assessment and treatment techniques</li>
                                <li>Understand human movement and biomechanics</li>
                                <li>Apply evidence-based practice in rehabilitation</li>
                                <li>Prepare for licensure and professional practice</li>
                            </ul>

                            <h2>Career Opportunities</h2>
                            <p>Graduates can work as:</p>
                            <ul>
                                <li>Licensed Physical Therapist</li>
                                <li>Sports Physical Therapist</li>
                                <li>Pediatric Physical Therapist</li>
                                <li>Geriatric Physical Therapist</li>
                                <li>Rehabilitation Specialist</li>
                                <li>Physical Therapy Researcher</li>
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


