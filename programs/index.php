<?php
/**
 * UPHSL Programs Index Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Main programs page listing all available programs at UPHSL
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Check if Programs section or Programs Index is in maintenance
if (isSectionInMaintenance('programs', 'programs-index') || isSectionInMaintenance('programs')) {
    $page_title = "Programs - Maintenance";
    $base_path = '../';
    include '../app/includes/header.php';
    if (displaySectionMaintenance('programs', $base_path, 'programs-index')) {
        include '../app/includes/footer.php';
        exit;
    }
}

$page_title = "Academic Programs";
$base_path = '../';

// Include header
include '../app/includes/header.php';
?>

<style>
    /* New page hero - matching SDG banner styling */
    .page-hero { position: relative; padding: 80px 0; color: #fff; text-align: center; isolation: isolate; overflow: hidden; background: url('<?php echo $base_path; ?>assets/images/FACADE.jpg') center/cover no-repeat; }
    .page-hero::after { content: ''; position: absolute; inset: 0; background: linear-gradient(135deg, rgba(28,77,161,.85), rgba(82,123,189,.85)); z-index: 1; }
    .page-hero .content { position: relative; z-index: 2; display: inline-block; padding: 24px 28px; border-radius: 16px; background: rgba(0,0,0,.55); -webkit-backdrop-filter: blur(10px); backdrop-filter: blur(10px); box-shadow: 0 16px 40px rgba(0,0,0,.35); }
    .page-hero .title { font-size: 3rem; font-weight: 800; line-height: 1.1; margin-bottom: 18px; text-shadow: 2px 2px 4px rgba(0,0,0,.3); }
    .page-hero .subtitle { font-size: 1.05rem; margin: 0; }
    @media (max-width: 1024px){ .page-hero{ padding:60px 0; } .page-hero .content{ padding:16px 18px; border-radius:12px; } .page-hero .title{ font-size:2.2rem; } .page-hero .subtitle{ font-size:1rem; } }
    
</style>


    <!-- New Banner -->
    <section class="page-hero">
        <div class="container">
            <div class="content">
                <h1 class="title">Academic Programs</h1>
                <p class="subtitle">Discover our comprehensive range of academic programs designed to shape future leaders and innovators</p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">

            <!-- Program Categories -->
            <div class="programs-layout">
                <!-- Basic Education -->
                <?php if (isNavbarItemVisible('programs', 'basic-education') || isNavbarItemVisible('programs', 'senior-high-school')): ?>
                <section class="program-category">
                    <div class="category-header">
                        <div class="category-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="category-info">
                            <h3 class="category-title">Basic Education</h3>
                            <p class="category-description">Foundation programs that build strong academic and character foundations</p>
                        </div>
                    </div>
                    <div class="programs-grid">
                        <?php if (isNavbarItemVisible('programs', 'basic-education')): ?>
                        <div class="program-card modern-card">
                            <div class="card-header">
                                <div class="program-icon">
                                    <i class="fas fa-school"></i>
                                </div>
                                <div class="program-badge">K-12</div>
                            </div>
                            <div class="card-content">
                                <h4 class="program-title">Basic Education</h4>
                                <p class="program-description">Pre-School, Grade School, Middle School, and Junior High School programs that build strong academic and character foundations.</p>
                                <div class="program-features">
                                    <span class="feature-tag">Pre-School</span>
                                    <span class="feature-tag">Elementary</span>
                                    <span class="feature-tag">Junior High</span>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="basic-education.php" class="program-link">
                                    Learn More <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (isNavbarItemVisible('programs', 'senior-high-school')): ?>
                        <div class="program-card modern-card">
                            <div class="card-header">
                                <div class="program-icon">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                                <div class="program-badge">11-12</div>
                            </div>
                            <div class="card-content">
                                <h4 class="program-title">Senior High School</h4>
                                <p class="program-description">Specialized tracks and strands preparing students for college and career paths.</p>
                                <div class="program-features">
                                    <span class="feature-tag">Tracks</span>
                                    <span class="feature-tag">Strands</span>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="senior-high-school.php" class="program-link">
                                    Learn More <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </section>
                <?php endif; ?>

                <!-- Health Sciences -->
                <?php if (isNavbarItemVisible('programs', 'bs-nursing') || isNavbarItemVisible('programs', 'bs-physical-therapy')): ?>
                <section class="program-category">
                    <div class="category-header">
                        <div class="category-icon">
                            <i class="fas fa-heartbeat"></i>
                        </div>
                        <div class="category-info">
                            <h3 class="category-title">Health Sciences</h3>
                            <p class="category-description">Programs focused on healthcare and medical sciences</p>
                        </div>
                    </div>
                    <div class="programs-grid">
                        <?php if (isNavbarItemVisible('programs', 'bs-nursing')): ?>
                        <div class="program-card modern-card">
                            <div class="card-header">
                                <div class="program-icon">
                                    <i class="fas fa-user-nurse"></i>
                                </div>
                                <div class="program-badge">Bachelor</div>
                            </div>
                            <div class="card-content">
                                <h4 class="program-title">BS Nursing</h4>
                                <p class="program-description">Comprehensive nursing education program preparing students for professional nursing practice.</p>
                                <div class="program-features">
                                    <span class="feature-tag">Healthcare</span>
                                    <span class="feature-tag">Clinical</span>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="bs-nursing.php" class="program-link">
                                    Learn More <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (isNavbarItemVisible('programs', 'bs-physical-therapy')): ?>
                        <div class="program-card modern-card">
                            <div class="card-header">
                                <div class="program-icon">
                                    <i class="fas fa-dumbbell"></i>
                                </div>
                                <div class="program-badge">Bachelor</div>
                            </div>
                            <div class="card-content">
                                <h4 class="program-title">BS Physical Therapy</h4>
                                <p class="program-description">Program focused on rehabilitation, movement science, and therapeutic interventions.</p>
                                <div class="program-features">
                                    <span class="feature-tag">Rehabilitation</span>
                                    <span class="feature-tag">Therapy</span>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="bs-physical-therapy.php" class="program-link">
                                    Learn More <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </section>
                <?php endif; ?>

                <!-- Information Technology & Computer Science -->
                <?php if (isNavbarItemVisible('programs', 'bs-information-technology') || isNavbarItemVisible('programs', 'bs-computer-science')): ?>
                <section class="program-category">
                    <div class="category-header">
                        <div class="category-icon">
                            <i class="fas fa-laptop-code"></i>
                        </div>
                        <div class="category-info">
                            <h3 class="category-title">Information Technology & Computer Science</h3>
                            <p class="category-description">Programs in technology, computing, and digital innovation</p>
                        </div>
                    </div>
                    <div class="programs-grid">
                        <?php if (isNavbarItemVisible('programs', 'bs-information-technology')): ?>
                        <div class="program-card modern-card">
                            <div class="card-header">
                                <div class="program-icon">
                                    <i class="fas fa-server"></i>
                                </div>
                                <div class="program-badge">Bachelor</div>
                            </div>
                            <div class="card-content">
                                <h4 class="program-title">BS Information Technology</h4>
                                <p class="program-description">Comprehensive IT program covering systems administration, networking, and software development.</p>
                                <div class="program-features">
                                    <span class="feature-tag">IT Systems</span>
                                    <span class="feature-tag">Networking</span>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="bs-information-technology.php" class="program-link">
                                    Learn More <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (isNavbarItemVisible('programs', 'bs-computer-science')): ?>
                        <div class="program-card modern-card">
                            <div class="card-header">
                                <div class="program-icon">
                                    <i class="fas fa-code"></i>
                                </div>
                                <div class="program-badge">Bachelor</div>
                            </div>
                            <div class="card-content">
                                <h4 class="program-title">BS Computer Science</h4>
                                <p class="program-description">Program focused on algorithms, programming, software engineering, and computer systems.</p>
                                <div class="program-features">
                                    <span class="feature-tag">Programming</span>
                                    <span class="feature-tag">Software Engineering</span>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="bs-computer-science.php" class="program-link">
                                    Learn More <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </section>
                <?php endif; ?>

                <!-- Business & Accountancy -->
                <?php if (isNavbarItemVisible('programs', 'bs-accountancy') || isNavbarItemVisible('programs', 'bs-business-administration')): ?>
                <section class="program-category">
                    <div class="category-header">
                        <div class="category-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="category-info">
                            <h3 class="category-title">Business & Accountancy</h3>
                            <p class="category-description">Programs in business management, administration, and accounting</p>
                        </div>
                    </div>
                    <div class="programs-grid">
                        <?php if (isNavbarItemVisible('programs', 'bs-accountancy')): ?>
                        <div class="program-card modern-card">
                            <div class="card-header">
                                <div class="program-icon">
                                    <i class="fas fa-calculator"></i>
                                </div>
                                <div class="program-badge">Bachelor</div>
                            </div>
                            <div class="card-content">
                                <h4 class="program-title">BS Accountancy</h4>
                                <p class="program-description">Comprehensive accounting program preparing students for CPA licensure and financial management careers.</p>
                                <div class="program-features">
                                    <span class="feature-tag">CPA Track</span>
                                    <span class="feature-tag">Finance</span>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="bs-accountancy.php" class="program-link">
                                    Learn More <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (isNavbarItemVisible('programs', 'bs-business-administration')): ?>
                        <div class="program-card modern-card">
                            <div class="card-header">
                                <div class="program-icon">
                                    <i class="fas fa-briefcase"></i>
                                </div>
                                <div class="program-badge">Bachelor</div>
                            </div>
                            <div class="card-content">
                                <h4 class="program-title">BS Business Administration</h4>
                                <p class="program-description">Program covering management, marketing, operations, and strategic business planning.</p>
                                <div class="program-features">
                                    <span class="feature-tag">Management</span>
                                    <span class="feature-tag">Marketing</span>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="bs-business-administration.php" class="program-link">
                                    Learn More <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </section>
                <?php endif; ?>

                <!-- Education -->
                <?php if (isNavbarItemVisible('programs', 'bachelor-elementary-education') || isNavbarItemVisible('programs', 'bachelor-secondary-education')): ?>
                <section class="program-category">
                    <div class="category-header">
                        <div class="category-icon">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <div class="category-info">
                            <h3 class="category-title">Education</h3>
                            <p class="category-description">Teacher education programs for elementary and secondary levels</p>
                        </div>
                    </div>
                    <div class="programs-grid">
                        <?php if (isNavbarItemVisible('programs', 'bachelor-elementary-education')): ?>
                        <div class="program-card modern-card">
                            <div class="card-header">
                                <div class="program-icon">
                                    <i class="fas fa-child"></i>
                                </div>
                                <div class="program-badge">Bachelor</div>
                            </div>
                            <div class="card-content">
                                <h4 class="program-title">Bachelor of Elementary Education</h4>
                                <p class="program-description">Program preparing future elementary school teachers with comprehensive pedagogical training.</p>
                            </div>
                            <div class="card-footer">
                                <a href="bachelor-elementary-education.php" class="program-link">
                                    Learn More <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (isNavbarItemVisible('programs', 'bachelor-secondary-education')): ?>
                        <div class="program-card modern-card">
                            <div class="card-header">
                                <div class="program-icon">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                                <div class="program-badge">Bachelor</div>
                            </div>
                            <div class="card-content">
                                <h4 class="program-title">Bachelor of Secondary Education</h4>
                                <p class="program-description">Program preparing future high school teachers with subject specialization and pedagogical expertise.</p>
                            </div>
                            <div class="card-footer">
                                <a href="bachelor-secondary-education.php" class="program-link">
                                    Learn More <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </section>
                <?php endif; ?>

                <!-- Engineering -->
                <?php if (isNavbarItemVisible('programs', 'bs-engineering')): ?>
                <section class="program-category">
                    <div class="category-header">
                        <div class="category-icon">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <div class="category-info">
                            <h3 class="category-title">Engineering</h3>
                            <p class="category-description">Engineering programs for technical and innovative careers</p>
                        </div>
                    </div>
                    <div class="programs-grid">
                        <?php if (isNavbarItemVisible('programs', 'bs-engineering')): ?>
                        <div class="program-card modern-card">
                            <div class="card-header">
                                <div class="program-icon">
                                    <i class="fas fa-tools"></i>
                                </div>
                                <div class="program-badge">Bachelor</div>
                            </div>
                            <div class="card-content">
                                <h4 class="program-title">BS Engineering</h4>
                                <p class="program-description">Comprehensive engineering program covering various engineering disciplines and technical applications.</p>
                                <div class="program-features">
                                    <span class="feature-tag">Technical</span>
                                    <span class="feature-tag">Innovation</span>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="bs-engineering.php" class="program-link">
                                    Learn More <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </section>
                <?php endif; ?>

                <!-- Hospitality & Tourism -->
                <?php if (isNavbarItemVisible('programs', 'bs-hospitality-management') || isNavbarItemVisible('programs', 'bs-tourism-management')): ?>
                <section class="program-category">
                    <div class="category-header">
                        <div class="category-icon">
                            <i class="fas fa-concierge-bell"></i>
                        </div>
                        <div class="category-info">
                            <h3 class="category-title">Hospitality & Tourism</h3>
                            <p class="category-description">Programs in hospitality management and tourism</p>
                        </div>
                    </div>
                    <div class="programs-grid">
                        <?php if (isNavbarItemVisible('programs', 'bs-hospitality-management')): ?>
                        <div class="program-card modern-card">
                            <div class="card-header">
                                <div class="program-icon">
                                    <i class="fas fa-hotel"></i>
                                </div>
                                <div class="program-badge">Bachelor</div>
                            </div>
                            <div class="card-content">
                                <h4 class="program-title">BS Hospitality Management</h4>
                                <p class="program-description">Program focused on hotel and restaurant management with international standards.</p>
                                <div class="program-features">
                                    <span class="feature-tag">Hotel Management</span>
                                    <span class="feature-tag">Restaurant</span>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="bs-hospitality-management.php" class="program-link">
                                    Learn More <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (isNavbarItemVisible('programs', 'bs-tourism-management')): ?>
                        <div class="program-card modern-card">
                            <div class="card-header">
                                <div class="program-icon">
                                    <i class="fas fa-plane"></i>
                                </div>
                                <div class="program-badge">Bachelor</div>
                            </div>
                            <div class="card-content">
                                <h4 class="program-title">BS Tourism Management</h4>
                                <p class="program-description">Program covering tourism operations, travel management, and destination marketing.</p>
                                <div class="program-features">
                                    <span class="feature-tag">Tourism</span>
                                    <span class="feature-tag">Travel</span>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="bs-tourism-management.php" class="program-link">
                                    Learn More <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </section>
                <?php endif; ?>

                <!-- Arts & Sciences -->
                <?php if (isNavbarItemVisible('programs', 'ba-communication-arts') || isNavbarItemVisible('programs', 'ab-bs-psychology')): ?>
                <section class="program-category">
                    <div class="category-header">
                        <div class="category-icon">
                            <i class="fas fa-palette"></i>
                        </div>
                        <div class="category-info">
                            <h3 class="category-title">Arts & Sciences</h3>
                            <p class="category-description">Liberal arts, communication, and psychology programs</p>
                        </div>
                    </div>
                    <div class="programs-grid">
                        <?php if (isNavbarItemVisible('programs', 'ba-communication-arts')): ?>
                        <div class="program-card modern-card">
                            <div class="card-header">
                                <div class="program-icon">
                                    <i class="fas fa-microphone"></i>
                                </div>
                                <div class="program-badge">Bachelor</div>
                            </div>
                            <div class="card-content">
                                <h4 class="program-title">AB Communication Arts</h4>
                                <p class="program-description">Program covering media, journalism, broadcasting, and digital communication.</p>
                            </div>
                            <div class="card-content">
                                <h4 class="program-title">Bachelor of Elementary Education</h4>
                                <p class="program-description">Program preparing future elementary school teachers with comprehensive pedagogical training.</p>
                            </div>
                            <div class="card-content">
                                <h4 class="program-title">Bachelor of Secondary Education</h4>
                                <p class="program-description">Program preparing future high school teachers with subject specialization and pedagogical expertise.</p>
                            </div>
                            <div class="card-content">
                                <h4 class="program-title">AB/BS Psychology</h4>
                                <p class="program-description">Program studying human behavior, mental processes, and psychological principles.</p>
                            </div>
                            <div class="card-content">
                                <h4 class="program-title">BS Criminology</h4>
                                <p class="program-description">Program focused on law enforcement, criminal justice, and forensic science.</p>
                            </div>
                            <div class="card-content">
                                <h4 class="program-title">BS Information Technology</h4>
                                <p class="program-description">Comprehensive IT program covering systems administration, networking, and software development.</p>
                            </div>
                            <div class="card-content">
                                <h4 class="program-title">BS Computer Science</h4>
                                <p class="program-description">Program focused on algorithms, programming, software engineering, and computer systems.</p>
                            </div>
                            <div class="card-content">
                                <h4 class="program-title">BS Nursing</h4>
                                <p class="program-description">Comprehensive nursing education program preparing students for professional nursing practice.</p>
                            </div>
                            <div class="card-content">
                                <h4 class="program-title">BS Accountancy</h4>
                                <p class="program-description">Comprehensive accounting program preparing students for CPA licensure and financial management careers.</p>
                            </div>
                            <div class="card-content">
                                <h4 class="program-title">BS Business Administration</h4>
                                <p class="program-description">Program covering management, marketing, operations, and strategic business planning.</p>
                            </div>
                            <div class="card-content">
                                <h4 class="program-title">BS Tourism Management</h4>
                                <p class="program-description">Program covering tourism operations, travel management, and destination marketing.</p>
                            </div>
                            <div class="card-content">
                                <h4 class="program-title">BS Hospitality Management</h4>
                                <p class="program-description">Program focused on hotel and restaurant management with international standards.</p>
                            </div>
                            <div class="card-content">
                                <h4 class="program-title">BS Civil Engineering</h4>
                                <p class="program-description">Comprehensive engineering program covering civil infrastructure and construction management.</p>
                            </div>
                            <div class="card-content">
                                <h4 class="program-title">BS Industrial Engineering</h4>
                                <p class="program-description">Program focused on optimizing complex systems and processes in manufacturing and services.</p>
                            </div>
                            <div class="card-content">
                                <h4 class="program-title">BS Computer Engineering</h4>
                                <p class="program-description">Program combining computer science and electrical engineering for hardware and software integration.</p>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </section>
                <?php endif; ?>

                <!-- Training Programs -->
                <?php if (isNavbarItemVisible('programs', 'call-center-training') || isNavbarItemVisible('programs', 'english-proficiency-training')): ?>
                <section class="program-category">
                    <div class="category-header">
                        <div class="category-icon">
                            <i class="fas fa-certificate"></i>
                        </div>
                        <div class="category-info">
                            <h3 class="category-title">Training Programs</h3>
                            <p class="category-description">Specialized training programs for skill development</p>
                        </div>
                    </div>
                    <div class="programs-grid">
                        <?php if (isNavbarItemVisible('programs', 'call-center-training')): ?>
                        <div class="program-card modern-card">
                            <div class="card-header">
                                <div class="program-icon">
                                    <i class="fas fa-headset"></i>
                                </div>
                                <div class="program-badge">Training</div>
                            </div>
                            <div class="card-content">
                                <h4 class="program-title">Call Center Training Program</h4>
                                <p class="program-description">Comprehensive training program preparing students for careers in customer service and call center operations.</p>
                                <div class="program-features">
                                    <span class="feature-tag">Customer Service</span>
                                    <span class="feature-tag">BPO</span>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="call-center-training.php" class="program-link">
                                    Learn More <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (isNavbarItemVisible('programs', 'english-proficiency-training')): ?>
                        <div class="program-card modern-card">
                            <div class="card-header">
                                <div class="program-icon">
                                    <i class="fas fa-language"></i>
                                </div>
                                <div class="program-badge">Training</div>
                            </div>
                            <div class="card-content">
                                <h4 class="program-title">English Proficiency Training</h4>
                                <p class="program-description">Intensive English language training program to enhance communication skills and proficiency.</p>
                                <div class="program-features">
                                    <span class="feature-tag">Language</span>
                                    <span class="feature-tag">Communication</span>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="english-proficiency-training.php" class="program-link">
                                    Learn More <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </section>
                <?php endif; ?>
            </div>

        </div>
</main>

<?php include '../app/includes/footer.php'; ?>
