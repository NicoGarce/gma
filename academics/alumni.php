<?php
/**
 * UPHSL GMA Campus Alumni Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Information about alumni services and connections at UPHSL GMA Campus
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Check if this sub-page or Academics section is in maintenance
if (isSectionInMaintenance('academics', 'alumni') || isSectionInMaintenance('academics')) {
    $page_title = "Alumni - Maintenance";
    $base_path = '../';
    include '../app/includes/header.php';
    if (displaySectionMaintenance('academics', $base_path, 'alumni')) {
        include '../app/includes/footer.php';
        exit;
    }
}

$base_path = '../';
$page_title = "Alumni - GMA Campus";
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

.benefits-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin: 2rem 0;
    max-width: 1200px;
    margin-left: auto;
    margin-right: auto;
}

.benefit-card {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid rgba(44, 90, 160, 0.1);
    border-left: 4px solid var(--primary-color);
}

.benefit-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
}

.benefit-card h3 {
    color: var(--primary-color);
    font-size: 1.5rem;
    margin-bottom: 1rem;
    font-weight: 700;
}

.benefit-card p {
    color: #666;
    line-height: 1.8;
    margin-bottom: 1rem;
    font-size: 1rem;
}

.benefit-card ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.benefit-card ul li {
    color: #444;
    line-height: 1.7;
    margin-bottom: 0.8rem;
    padding-left: 1.5rem;
    position: relative;
    font-size: 1rem;
}

.benefit-card ul li::before {
    content: "✓";
    color: var(--secondary-color);
    font-weight: bold;
    position: absolute;
    left: 0;
    top: 0;
    font-size: 1.2rem;
}

.alumni-form-box {
    max-width: 800px;
    margin: 2rem auto 0;
    background: white;
    padding: 2.5rem;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    border-left: 4px solid var(--secondary-color);
    text-align: center;
}

.alumni-form-box h3 {
    color: var(--primary-color);
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
}

.alumni-form-box p {
    color: #666;
    line-height: 1.8;
    margin-bottom: 1.5rem;
    font-size: 1.1rem;
}

.btn-alumni {
    display: inline-block;
    padding: 15px 40px;
    background: var(--primary-color);
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    border: 2px solid var(--primary-color);
}

.btn-alumni:hover {
    background: var(--secondary-color);
    border-color: var(--secondary-color);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(28, 77, 161, 0.3);
    color: white;
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

.contact-box h4 {
    color: var(--primary-color);
    font-size: 1.5rem;
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
    
    .benefits-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .benefit-card {
        padding: 1.5rem;
    }
    
    .alumni-form-box,
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
                <h2>Alumni</h2>
                <p class="intro-description">Stay connected with your alma mater - UPHSL GMA Campus. Join our vibrant alumni community and continue your journey of excellence.</p>
            </div>
        </div>
    </section>

    <!-- News Carousel Section -->
    <?php
    $categoryId = 'Alumni';
    $sectionTitle = 'Alumni News & Updates';
    $sectionDescription = 'Stay updated with the latest news and announcements for UPHSL GMA Campus alumni.';
    include '../app/includes/news-carousel.php';
    ?>

    <!-- Alumni Benefits Section -->
    <section class="content-section">
        <div class="container">
            <h2 class="section-title">Alumni Services</h2>
            <p class="section-subtitle">Benefits and services available to UPHSL GMA Campus alumni</p>
            <div class="benefits-grid">
                <div class="benefit-card">
                    <h3>Alumni Benefits</h3>
                    <p>As a UPHSL GMA Campus alumnus, you have access to various services and benefits:</p>
                    <ul>
                        <li>Transcript of Records and Document Requests</li>
                        <li>Alumni ID and Library Access</li>
                        <li>Networking and Career Opportunities</li>
                        <li>Alumni Events and Reunions</li>
                        <li>Continuing Education Programs</li>
                    </ul>
                </div>
                
                <div class="benefit-card">
                    <h3>Get Involved</h3>
                    <p>Stay connected with your fellow Perpetualites and contribute to the growth of our community:</p>
                    <ul>
                        <li>Join Alumni Association</li>
                        <li>Mentor Current Students</li>
                        <li>Share Your Success Stories</li>
                        <li>Participate in Career Fairs</li>
                        <li>Support University Initiatives</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Alumni Registration Section -->
    <section class="content-section">
        <div class="container">
            <div class="alumni-form-box">
                <h3>Alumni Registration</h3>
                <p>Register as an alumnus to stay updated with news, events, and opportunities from UPHSL GMA Campus.</p>
                <a href="https://docs.google.com/forms/d/e/1FAIpQLSea8-O2OuuKWgZ17XgKkyLQ7dDOawW31a8vq1nTWDRREODVMQ/viewform" target="_blank" class="btn-alumni">
                    Register Now <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            
            <div class="contact-box">
                <h4>Contact Alumni Affairs</h4>
                <p>For inquiries about alumni services at GMA Campus:</p>
                <p><strong>Email:</strong> <a href="mailto:info@uphsl.edu.ph">info@uphsl.edu.ph</a></p>
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
