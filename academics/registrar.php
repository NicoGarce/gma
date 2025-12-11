<?php
/**
 * UPHSL GMA Campus Registrar Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Information about registrar services at UPHSL GMA Campus
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Check if this sub-page or Academics section is in maintenance
if (isSectionInMaintenance('academics', 'registrar') || isSectionInMaintenance('academics')) {
    $page_title = "Registrar - Maintenance";
    $base_path = '../';
    include '../app/includes/header.php';
    if (displaySectionMaintenance('academics', $base_path, 'registrar')) {
        include '../app/includes/footer.php';
        exit;
    }
}

$base_path = '../';
$page_title = "Registrar - GMA Campus";
include '../app/includes/header.php';
?>

<style>
body {
    padding: 0 !important;
    margin: 0 !important;
}

.main-content {
    padding-top: 100px;
}

@media (max-width: 1200px) {
    .main-content {
        padding-top: 90px;
    }
}

@media (max-width: 992px) {
    .main-content {
        padding-top: 80px;
    }
}

@media (max-width: 768px) {
    .main-content {
        padding-top: 70px;
    }
}

@media (max-width: 576px) {
    .main-content {
        padding-top: 60px;
    }
}

.intro-section {
    margin-top: 0;
}

.intro-section {
    background: linear-gradient(135deg, rgba(44, 90, 160, 0.9), rgba(255, 198, 62, 0.9)), url('<?php echo $base_path; ?>assets/images/campuses/gma-college.jpeg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    color: white;
    padding: 2rem 0;
    margin: 0;
    position: relative;
    overflow: hidden;
}

.intro-content {
    text-align: center;
    max-width: 800px;
    margin: 0 auto;
    padding: 0 1rem;
}

.intro-content h2 {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    font-weight: 700;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    letter-spacing: -0.5px;
}

.intro-description {
    font-size: 0.9rem;
    line-height: 1.5;
    margin-bottom: 1.5rem;
    opacity: 0.95;
    max-width: 700px;
    margin-left: auto;
    margin-right: auto;
}

.content-section {
    padding: 4rem 0;
    background: white;
}

.content-section:nth-child(even) {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.section-title {
    text-align: center;
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 1rem;
    position: relative;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 3px;
    background: var(--secondary-color);
    border-radius: 2px;
}

.section-subtitle {
    text-align: center;
    font-size: 1.1rem;
    color: #666;
    margin-bottom: 2rem;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin: 2rem 0;
    max-width: 1200px;
    margin-left: auto;
    margin-right: auto;
}

.service-card {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid rgba(44, 90, 160, 0.1);
    border-left: 4px solid var(--primary-color);
}

.service-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
}

.service-card h3 {
    color: var(--primary-color);
    font-size: 1.5rem;
    margin-bottom: 1rem;
    font-weight: 700;
}

.service-card ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.service-card ul li {
    color: #444;
    line-height: 1.7;
    margin-bottom: 0.8rem;
    padding-left: 1.5rem;
    position: relative;
    font-size: 1rem;
}

.service-card ul li::before {
    content: "✓";
    color: var(--secondary-color);
    font-weight: bold;
    position: absolute;
    left: 0;
    top: 0;
    font-size: 1.2rem;
}

.hours-box {
    max-width: 800px;
    margin: 2rem auto 0;
    background: white;
    padding: 2.5rem;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    border-left: 4px solid var(--secondary-color);
    text-align: center;
}

.hours-box h4 {
    color: var(--primary-color);
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
}

.hours-box p {
    color: #666;
    line-height: 1.8;
    margin-bottom: 0.8rem;
    font-size: 1rem;
}

.contact-box {
    max-width: 800px;
    margin: 2rem auto 0;
    background: white;
    padding: 2.5rem;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    border-left: 4px solid var(--secondary-color);
    text-align: center;
}

.contact-box h3 {
    color: var(--primary-color);
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
}

.contact-box p {
    color: #666;
    line-height: 1.8;
    margin-bottom: 1rem;
    font-size: 1rem;
}

.contact-box a {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 600;
}

.contact-box a:hover {
    text-decoration: underline;
}

@media (max-width: 768px) {
    .intro-content h2 {
        font-size: 2rem;
    }
    
    .intro-description {
        font-size: 1rem;
    }
    
    .services-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .service-card {
        padding: 1.5rem;
    }
    
    .hours-box,
    .contact-box {
        padding: 2rem;
    }
    
    .section-title {
        font-size: 2rem;
    }
}
</style>

<main class="main-content">
    <!-- Introduction Section -->
    <section class="intro-section">
        <div class="container">
            <div class="intro-content">
                <h2>Registrar</h2>
                <p class="intro-description">Academic records and enrollment services at UPHSL GMA Campus. We provide comprehensive registrar services to support your academic journey.</p>
            </div>
        </div>
    </section>

    <!-- News Carousel Section -->
    <?php
    $categoryId = 'Registrar';
    $sectionTitle = 'Registrar News & Updates';
    $sectionDescription = 'Stay updated with the latest news and announcements from the Registrar\'s Office.';
    include '../app/includes/news-carousel.php';
    ?>

    <!-- Registrar Services Section -->
    <section class="content-section">
        <div class="container">
            <h2 class="section-title">Registrar Services</h2>
            <p class="section-subtitle">Comprehensive services to support your academic needs</p>
            <div class="services-grid">
                <div class="service-card">
                    <h3>Student Records</h3>
                    <ul>
                        <li>Transcript of Records</li>
                        <li>Diploma</li>
                        <li>Certificate of Enrollment</li>
                        <li>Certificate of Grades</li>
                        <li>Certificate of Good Moral Character</li>
                        <li>Authentication of Documents</li>
                    </ul>
                </div>
                
                <div class="service-card">
                    <h3>Enrollment Services</h3>
                    <ul>
                        <li>Course Registration</li>
                        <li>Schedule Adjustment</li>
                        <li>Adding/Dropping of Subjects</li>
                        <li>Cross Enrollment</li>
                        <li>Leave of Absence</li>
                        <li>Withdrawal</li>
                    </ul>
                </div>
                
                <div class="service-card">
                    <h3>Other Services</h3>
                    <ul>
                        <li>Student ID Processing</li>
                        <li>Clearance Processing</li>
                        <li>Transfer Credential Processing</li>
                        <li>Graduation Application</li>
                        <li>Document Request</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Office Hours & Contact Section -->
    <section class="content-section">
        <div class="container">
            <div class="hours-box">
                <h4>Office Hours</h4>
                <p><strong>Weekdays:</strong> 8:00 AM - 5:00 PM</p>
                <p><strong>Saturday:</strong> 8:00 AM - 5:00 PM</p>
                <p><strong>Sunday:</strong> Closed</p>
            </div>
            
            <div class="contact-box">
                <h3>Contact the Registrar's Office</h3>
                <p>For inquiries about academic records and enrollment at GMA Campus:</p>
                <p><strong>Email:</strong> <a href="mailto:registrar@gma.uphsl.edu.ph">registrar@gma.uphsl.edu.ph</a></p>
                <p><strong>Phone:</strong> <a href="tel:0464604086">(046) 460-4086</a> | <a href="tel:025194100">(02) 519-4100</a></p>
                <p><strong>Location:</strong> San Gabriel, General Mariano Alvarez, Cavite</p>
            </div>
        </div>
    </section>
</main>

<?php
// Include footer
include '../app/includes/footer.php';
?>
