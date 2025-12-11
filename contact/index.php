<?php
/**
 * UPHSL GMA Campus Contact Us Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Contact information for the University of Perpetual Help System Laguna - GMA Campus
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Check if this sub-page or About section is in maintenance
if (isSectionInMaintenance('about', 'contact') || isSectionInMaintenance('about')) {
    $page_title = "Contact Us - Maintenance";
    $base_path = '../';
    include '../app/includes/header.php';
    if (displaySectionMaintenance('about', $base_path, 'contact')) {
        include '../app/includes/footer.php';
        exit;
    }
}

// Set page title
$page_title = "Contact Us";
$base_path = '../';

// Include header
include '../app/includes/header.php';
?>

<style>
/* Contact Page Styles */
.contact-hero {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    color: white;
    padding: 80px 0;
    position: relative;
    overflow: hidden;
}

.contact-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>');
    opacity: 0.3;
}

.contact-hero .container {
    position: relative;
    z-index: 2;
}

.hero-content {
    text-align: center;
    max-width: 800px;
    margin: 0 auto;
}

.hero-title {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 20px;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
}

.hero-subtitle {
    font-size: 1.2rem;
    margin-bottom: 40px;
    opacity: 0.95;
    line-height: 1.6;
}

.contact-layout {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    padding: 60px 0;
    min-height: 80vh;
}

.contact-column {
    display: flex;
    flex-direction: column;
    gap: 40px;
}

.info-column {
    display: flex;
    flex-direction: column;
}

.section-title {
    font-size: 2rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 20px;
    text-align: left;
}

.contact-methods-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 20px;
}

.visit-card {
    grid-column: 1 / -1;
}

.map-container {
    margin: 20px 0;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.map-container iframe {
    width: 100%;
    height: 300px;
    border: none;
    display: block;
}

.modern-card {
    background: white;
    border-radius: 20px;
    padding: 40px 30px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    border: 1px solid rgba(28, 77, 161, 0.1);
    position: relative;
    overflow: hidden;
}

.modern-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
}

.modern-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(28, 77, 161, 0.15);
}

.contact-card:not(.visit-card) {
    padding: 25px 20px;
}

.contact-card:not(.visit-card) .card-icon {
    width: 60px;
    height: 60px;
    font-size: 1.4rem;
}

.contact-card:not(.visit-card) .card-content h3 {
    font-size: 1.2rem;
    margin-bottom: 12px;
}

.contact-card:not(.visit-card) .card-content p {
    font-size: 0.9rem;
    line-height: 1.5;
}

.card-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    margin-bottom: 25px;
    font-size: 2rem;
    box-shadow: 0 8px 20px rgba(28, 77, 161, 0.3);
}

.card-content h3 {
    color: var(--primary-color);
    margin-bottom: 15px;
    font-size: 1.4rem;
    font-weight: 700;
}

.card-content p {
    color: #666;
    line-height: 1.6;
    margin-bottom: 20px;
    font-size: 0.95rem;
}

.card-content p strong {
    color: var(--primary-color);
    font-weight: 600;
}

.card-content a {
    color: var(--primary-color);
    text-decoration: none;
    transition: color 0.3s ease;
}

.card-content a:hover {
    color: var(--secondary-color);
    text-decoration: underline;
}

.info-container {
    background: white;
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(28, 77, 161, 0.1);
}

.info-header {
    text-align: center;
    margin-bottom: 40px;
    padding-bottom: 20px;
    border-bottom: 2px solid rgba(28, 77, 161, 0.1);
}

.info-header h2 {
    color: var(--primary-color);
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 10px;
}

.info-header p {
    color: #666;
    font-size: 1rem;
    line-height: 1.6;
}

.info-features {
    display: flex;
    flex-direction: column;
    gap: 25px;
    margin-bottom: 40px;
}

.info-feature {
    display: flex;
    gap: 20px;
    align-items: flex-start;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 15px;
    transition: all 0.3s ease;
}

.info-feature:hover {
    background: #e9ecef;
    transform: translateX(5px);
}

.feature-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    flex-shrink: 0;
    box-shadow: 0 5px 15px rgba(28, 77, 161, 0.3);
}

.feature-content h3 {
    color: var(--primary-color);
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 8px;
}

.feature-content p {
    color: #666;
    font-size: 0.95rem;
    line-height: 1.6;
    margin: 0;
}

.cta-section {
    background: linear-gradient(135deg, rgba(28, 77, 161, 0.05), rgba(82, 123, 189, 0.05));
    padding: 30px;
    border-radius: 15px;
    text-align: center;
    margin-bottom: 30px;
    border: 2px solid rgba(28, 77, 161, 0.1);
}

.cta-section h3 {
    color: var(--primary-color);
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 10px;
}

.cta-section p {
    color: #666;
    font-size: 1rem;
    margin-bottom: 20px;
    line-height: 1.6;
}

.cta-buttons {
    display: flex;
    justify-content: center;
    gap: 15px;
    flex-wrap: wrap;
}

.btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 12px 24px;
    background: var(--primary-color);
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
    border: 2px solid var(--primary-color);
}

.btn-secondary:hover {
    background: var(--secondary-color);
    border-color: var(--secondary-color);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(28, 77, 161, 0.3);
}

@media (max-width: 992px) {
    .contact-layout {
        grid-template-columns: 1fr;
        gap: 40px;
        padding: 40px 0;
    }
    
    .hero-title {
        font-size: 2.2rem;
    }
    
    .hero-subtitle {
        font-size: 1.1rem;
    }
}

@media (max-width: 768px) {
    .contact-hero {
        padding: 60px 0;
    }
    
    .hero-title {
        font-size: 1.8rem;
    }
    
    .hero-subtitle {
        font-size: 1rem;
    }
    
    .section-title {
        font-size: 1.6rem;
    }
    
    .modern-card {
        padding: 25px 20px;
    }
    
    .info-container {
        padding: 25px 20px;
    }
    
    .info-feature {
        flex-direction: column;
        text-align: center;
    }
    
    .feature-icon {
        margin: 0 auto;
    }
}
</style>

    <!-- Hero Section -->
    <section class="contact-hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">Get in Touch</h1>
                <p class="hero-subtitle">We're here to help and answer any questions you might have about UPHSL GMA Campus</p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="contact-layout">
                <!-- Left Column: Contact Information -->
                <div class="contact-column">
                    <h2 class="section-title">Contact Information</h2>
                    <div class="contact-methods-grid">
                        <div class="contact-card modern-card visit-card">
                            <div class="card-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="card-content">
                                <h3>Visit Us</h3>
                                <p>University of Perpetual Help System - GMA Campus<br>
                                San Gabriel, General Mariano Alvarez, Cavite</p>
                                <div class="map-container">
                                    <iframe src="https://www.google.com/maps?q=University+of+Perpetual+Help+System+GMA+Campus,San+Gabriel,General+Mariano+Alvarez,Cavite,Philippines&output=embed" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                </div>
                            </div>
                        </div>
                        
                        <div class="contact-card modern-card">
                            <div class="card-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="card-content">
                                <h3>Call Us</h3>
                                <p><a href="tel:0464604086"><strong>(046) 460-4086</strong></a><br>
                                <a href="tel:025194100"><strong>(02) 519-4100</strong></a><br>
                                <a href="tel:09098094200"><strong>0909-809-4200</strong></a></p>
                            </div>
                        </div>
                        
                        <div class="contact-card modern-card">
                            <div class="card-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="card-content">
                                <h3>Email Us</h3>
                                <p><strong>Corporate Email:</strong> <a href="mailto:info@uphsl.edu.ph">info@uphsl.edu.ph</a><br>
                                <strong>Admission:</strong> <a href="mailto:admission@gma.uphsl.edu.ph">admission@gma.uphsl.edu.ph</a><br>
                                <strong>Registrar:</strong> <a href="mailto:registrar@gma.uphsl.edu.ph">registrar@gma.uphsl.edu.ph</a></p>
                            </div>
                        </div>
                        
                        <div class="contact-card modern-card">
                            <div class="card-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="card-content">
                                <h3>Business Hours</h3>
                                <p><strong>Weekdays:</strong> 8am to 5pm<br>
                                <strong>Saturday:</strong> 8am to 5pm<br>
                                <strong>Sunday:</strong> Closed</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Additional Information -->
                <div class="info-column">
                    <div class="info-container">
                        <div class="info-header">
                            <h2>Why Choose UPHSL GMA Campus?</h2>
                            <p>Discover what makes us the premier educational institution in General Mariano Alvarez, Cavite</p>
                        </div>
                        
                        <div class="info-features">
                            <div class="info-feature">
                                <div class="feature-icon">
                                    <i class="fas fa-award"></i>
                                </div>
                                <div class="feature-content">
                                    <h3>Quality Education</h3>
                                    <p>Committed to providing world-class education with modern facilities and experienced faculty.</p>
                                </div>
                            </div>
                            
                            <div class="info-feature">
                                <div class="feature-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="feature-content">
                                    <h3>Diverse Community</h3>
                                    <p>Join a vibrant community of students from different backgrounds and cultures.</p>
                                </div>
                            </div>
                            
                            <div class="info-feature">
                                <div class="feature-icon">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <div class="feature-content">
                                    <h3>Career Support</h3>
                                    <p>Comprehensive career guidance and job placement assistance for all graduates.</p>
                                </div>
                            </div>
                            
                            <div class="info-feature">
                                <div class="feature-icon">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <div class="feature-content">
                                    <h3>Student Care</h3>
                                    <p>Dedicated support services to ensure every student's success and well-being.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="cta-section">
                            <h3>Ready to Start Your Journey at GMA Campus?</h3>
                            <p>Visit our GMA Campus or contact us today to learn more about our programs and admission requirements.</p>
                            <div class="cta-buttons">
                                <a href="../programs.php" class="btn-secondary">
                                    <i class="fas fa-graduation-cap"></i>
                                    View Programs
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php
// Include footer
include '../app/includes/footer.php';
?>


