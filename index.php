<?php
/**
 * UPHSL Homepage
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Main homepage for the University of Perpetual Help System Laguna website
 */

session_start();
require_once 'app/config/database.php';
require_once 'app/includes/functions.php';

// Check if this is the first time setup
$pdo = getDBConnection();
$stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
$result = $stmt->fetch();

// If no users exist, redirect to setup page
if ($result['count'] == 0) {
    header('Location: auth/init.php');
    exit();
}

// Check if Home section is in maintenance
if (isSectionInMaintenance('home')) {
    $page_title = "Home - Maintenance";
    $base_path = '';
    include 'app/includes/header.php';
    $maintenance_message = getSectionMaintenanceMessage('home');
    ?>
    <main class="main-content" style="min-height: 60vh; display: flex; align-items: center; justify-content: center; padding: 4rem 2rem;">
        <div class="maintenance-message" style="text-align: center; max-width: 600px; padding: 3rem; background: white; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
            <i class="fas fa-tools" style="font-size: 4rem; color: var(--primary-color); margin-bottom: 1.5rem;"></i>
            <h1 style="font-size: 2rem; color: var(--primary-color); margin-bottom: 1rem;">Under Maintenance</h1>
            <p style="font-size: 1.1rem; color: #666; line-height: 1.6; margin-bottom: 2rem;"><?php echo htmlspecialchars($maintenance_message); ?></p>
            <a href="<?php echo $base_path; ?>index.php" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: var(--primary-color); color: white; text-decoration: none; border-radius: 8px; font-weight: 600;">
                <i class="fas fa-refresh"></i>
                Refresh Page
            </a>
        </div>
    </main>
    <?php include 'app/includes/footer.php'; ?>
    <?php exit; }

// Get recent posts for homepage
$homepage_recent_posts = (int)getSetting('homepage_recent_posts', '6');
$recent_posts = getRecentPosts($homepage_recent_posts);

// Get hero post (selected by super admin or default to latest)
$hero_post = getHeroPost();

// Set page title
$page_title = "Home";

// Set base path for assets
$base_path = ''; // Empty for root directory

// Include header
include 'app/includes/header.php';
?>

    <style>
        /* Hero Ticker & Clock */
        .hero { position: relative; }
        .hero-ticker {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.55);
            color: #fff;
            z-index: 2;
        }
        .hero-ticker-inner {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 10px 16px;
        }
        .hero-clock {
            display: flex;
            flex-direction: column;
            line-height: 1.1;
        }
        .hero-time {
            font-weight: 600;
            white-space: nowrap;
            font-variant-numeric: tabular-nums;
        }
        .hero-date {
            font-size: 12px;
            opacity: 0.9;
            white-space: nowrap;
        }
        .hero-ticker-track {
            overflow: hidden;
            flex: 1;
            position: relative;
            min-height: 30px;
        }
        .hero-ticker-content {
            position: relative;
            overflow: hidden;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .ticker-item {
            color: #fff;
            text-decoration: none;
            font-weight: 500;
            transition: opacity 0.5s ease, transform 0.5s ease;
            opacity: 0;
            transform: translateY(20px);
            position: absolute;
            width: 100%;
            text-align: center;
            visibility: hidden;
            top: 0;
            left: 0;
        }
        .ticker-item.active {
            opacity: 1;
            transform: translateY(0);
            visibility: visible;
        }
        .ticker-item:hover { 
            text-decoration: underline; 
        }
        @media (max-width: 768px) {
            .hero-ticker-inner { gap: 12px; padding: 8px 12px; }
            .hero-clock { font-size: 14px; }
            .ticker-item { 
                font-size: 14px; 
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 100%;
            }
        }
        
        @media (max-width: 480px) {
            .hero-ticker-inner { gap: 8px; padding: 6px 10px; }
            .hero-clock { font-size: 12px; }
            .ticker-item { 
                font-size: 12px; 
                line-height: 1.2;
            }
        }
        
        @media (max-width: 360px) {
            .hero-ticker-inner { gap: 6px; padding: 5px 8px; }
            .hero-clock { font-size: 11px; }
            .ticker-item { 
                font-size: 11px; 
                line-height: 1.1;
            }
        }
        
        /* Interactive Education Level Buttons */
        .education-levels {
            grid-column: 1 / -1;
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(28, 77, 161, 0.1);
        }
        
        .level-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .level-btn {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 20px;
            background: #f8f9fa;
            border: 2px solid transparent;
            border-radius: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: left;
            width: 100%;
        }
        
        .level-btn:hover {
            background: #e3f2fd;
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(28, 77, 161, 0.1);
        }
        
        .level-btn.active {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-color: var(--primary-color);
            box-shadow: 0 5px 20px rgba(28, 77, 161, 0.3);
        }
        
        .btn-icon {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }
        
        .level-btn.active .btn-icon {
            background: rgba(255, 255, 255, 0.3);
        }
        
        .btn-content h4 {
            margin: 0 0 5px 0;
            font-size: 1.1rem;
            font-weight: 700;
        }
        
        .btn-content p {
            margin: 0;
            font-size: 0.9rem;
            opacity: 0.8;
        }
        
        .level-content {
            position: relative;
            min-height: 300px;
        }
        
        .content-panel {
            display: none;
            animation: fadeIn 0.3s ease-in-out;
        }
        
        .content-panel.active {
            display: block;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .panel-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .panel-header h3 {
            color: var(--primary-color);
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .panel-header p {
            color: #666;
            font-size: 1.1rem;
            margin: 0;
        }
        
        .programs-preview {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .program-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px 20px;
            background: #f8f9fa;
            border-radius: 12px;
            transition: all 0.3s ease;
            border-left: 4px solid var(--primary-color);
            text-decoration: none;
            color: inherit;
        }
        
        .program-item:hover {
            background: #e3f2fd;
            transform: translateX(5px);
            text-decoration: none;
            color: inherit;
        }
        
        .program-item i {
            color: var(--primary-color);
            font-size: 1.2rem;
            width: 20px;
            text-align: center;
        }
        
        .program-item span {
            font-weight: 600;
            color: #333;
        }
        
        .view-all-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--primary-color);
            color: white;
            padding: 15px 30px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            margin: 0 auto;
            display: block;
            text-align: center;
            max-width: 300px;
        }
        
        .view-all-btn:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(28, 77, 161, 0.3);
        }
        
        @media (max-width: 768px) {
            .level-buttons {
                grid-template-columns: 1fr;
                gap: 10px;
            }
            
            .level-btn {
                padding: 15px;
            }
            
            .btn-icon {
                width: 40px;
                height: 40px;
                font-size: 1.2rem;
            }
            
            .programs-preview {
                grid-template-columns: 1fr;
                gap: 10px;
            }
            
            .program-item {
                padding: 12px 15px;
            }
        }
    </style>

    <script>
        // Hero Slider Auto-Rotation
        document.addEventListener('DOMContentLoaded', function() {
            const slider = document.getElementById('heroSlider');
            const slides = slider.querySelectorAll('.hero-slide');
            
            let currentSlide = 0;
            let autoSlideInterval;
            
            // Function to show specific slide
            function showSlide(index) {
                // Remove active class from all slides
                slides.forEach(slide => slide.classList.remove('active'));
                
                // Add active class to current slide
                slides[index].classList.add('active');
                
                currentSlide = index;
            }
            
            // Function to go to next slide
            function nextSlide() {
                const nextIndex = (currentSlide + 1) % slides.length;
                showSlide(nextIndex);
            }
            
            // Function to start auto-slide
            function startAutoSlide() {
                autoSlideInterval = setInterval(nextSlide, 20000); // Change slide every 20 seconds
            }
            
            // Function to stop auto-slide
            function stopAutoSlide() {
                if (autoSlideInterval) {
                    clearInterval(autoSlideInterval);
                    autoSlideInterval = null;
                }
            }
            
            // Start auto-slide
            startAutoSlide();
            
            // Pause auto-slide on hover
            slider.addEventListener('mouseenter', stopAutoSlide);
            
            // Resume auto-slide when mouse leaves
            slider.addEventListener('mouseleave', startAutoSlide);
        });
    </script>

    <!-- Hero Section with Image Background -->
    <section class="hero">
        <div class="hero-background">
            <img 
                src="assets/images/slider-1.jpg" 
                alt="University of Perpetual Help System - GMA Campus" 
                class="hero-image">
        </div>
        <div class="video-overlay">
            <div class="hero-slider-container">
                <div class="hero-slider" id="heroSlider">
                    <!-- Slide 1: Latest News -->
                    <div class="hero-slide active">
                        <div class="hero-slide-content">
                            <?php if ($hero_post): ?>
                                <div class="latest-post-card">
                                    <div class="post-meta">
                                        <span class="latest-label">Latest</span>
                                        <span class="post-date">
                                            <i class="fas fa-calendar"></i>
                                            <?php echo formatDate($hero_post['published_at'] ?: $hero_post['created_at']); ?>
                                        </span>
                                    </div>
                                    <h2 class="latest-post-title">
                                        <a href="post.php?slug=<?php echo $hero_post['slug']; ?>">
                                            <?php echo htmlspecialchars($hero_post['title']); ?>
                                        </a>
                                    </h2>
                                    <div class="hero-buttons">
                                        <a href="#news" class="btn btn-primary">View News</a>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="latest-post-card">
                                    <h2 class="latest-post-title">Stay Updated</h2>
                                    <p class="latest-post-excerpt">
                                        Check back soon for the latest news and announcements from the University of Perpetual Help System - GMA Campus.
                                    </p>
                                    <a href="#news" class="btn btn-outline">View News</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Slide 2: Tagline -->
                    <div class="hero-slide">
                        <div class="hero-slide-content">
                            <div class="hero-tagline">
                                <div class="hero-content">
                                    <div class="tagline-container">
                                        <h1 class="tagline">Character Building is Nation Building</h1>
                                    </div>
                                    <p class="hero-description">
                                        Excellence in education, character formation, and nation building. Join our community of learners and discover endless opportunities for academic and personal growth.
                                    </p>
                                    <div class="hero-buttons">
                                        <a href="#programs" class="btn btn-primary">Explore Programs</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if (!empty($recent_posts) && count($recent_posts) > 1): ?>
        <div class="hero-ticker">
            <div class="hero-ticker-inner">
                <div class="hero-clock">
                    <div class="hero-time" id="heroClock">--:--</div>
                    <div class="hero-date" id="heroDate">---</div>
                </div>
                <div class="hero-ticker-track">
                    <div class="hero-ticker-content" id="heroTickerContent">
                        <?php if (!empty($recent_posts) && count($recent_posts) > 1): ?>
                            <?php foreach (array_slice($recent_posts, 1) as $index => $post): ?>
                                <a href="post.php?slug=<?php echo $post['slug']; ?>" class="ticker-item <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo $index; ?>"><?php echo htmlspecialchars($post['title']); ?></a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </section>

    <!-- News & Facebook Feed Section (replacing YouTube video section) -->
    <section class="news-section" id="news" style="padding: 4rem 0;">
        <div class="container">
            <div class="news-layout">
                <div class="news-content">
                    <div class="section-header" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); padding: 2rem; border-radius: 12px; margin-bottom: 2rem; text-align: center;">
                        <h2 class="section-title" style="color: white; margin-bottom: 0.5rem; font-size: 1.5rem;">News & Announcements</h2>
                        <p class="section-description" style="color: rgba(255, 255, 255, 0.9); margin: 0; font-size: 0.85rem;">Stay updated with the latest news and announcements from the University of Perpetual Help System - GMA Campus</p>
                    </div>
                    
                    <?php if (!empty($recent_posts)): ?>
                        <div class="news-carousel-container">
                            <div class="news-carousel" id="newsCarousel">
                                <?php foreach ($recent_posts as $index => $post): ?>
                                    <div class="news-slide <?php echo $index === 0 ? 'active' : ''; ?>">
                                        <div class="news-slide-meta">
                                            <span class="news-slide-date">
                                                <i class="fas fa-calendar"></i>
                                                <?php echo formatDate($post['published_at'] ?: $post['created_at']); ?>
                                            </span>
                                        </div>
                                        <div class="news-slide-title-overlay">
                                            <h3 class="news-slide-title">
                                                <a href="post.php?slug=<?php echo $post['slug']; ?>">
                                                    <?php echo htmlspecialchars($post['title']); ?>
                                                </a>
                                            </h3>
                                        </div>
                                        <div class="news-slide-image">
                                            <?php if ($post['featured_image']): ?>
                                                <?php 
                                                    $img = $post['featured_image'];
                                                    $imgSrc = (strpos($img, 'uploads/') === 0) ? $img : 'uploads/' . $img;
                                                ?>
                                                <img src="<?php echo htmlspecialchars($imgSrc); ?>" 
                                                     alt="<?php echo htmlspecialchars($post['title']); ?>"
                                                     decoding="async">
                                            <?php else: ?>
                                                <div class="news-slide-placeholder">
                                                    <i class="fas fa-newspaper"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <!-- Navigation Arrows -->
                            <button class="carousel-nav carousel-prev" id="newsPrev">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button class="carousel-nav carousel-next" id="newsNext">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                            
                            <!-- Dots Indicator -->
                            <div class="carousel-dots" id="newsDots"></div>
                        </div>
                        
                        <div class="news-actions">
                            <a href="posts.php" class="btn btn-primary">
                                <i class="fas fa-newspaper"></i>
                                View All Posts
                            </a>
                            <div class="social-media-icons">
                                <a href="https://www.youtube.com/@uphsltv1397" target="_blank" rel="noopener" class="social-icon youtube" title="Subscribe to our YouTube">
                                    <i class="fab fa-youtube"></i>
                                    <span class="social-label">YouTube</span>
                                </a>
                                <a href="https://www.instagram.com/uphs.laguna" target="_blank" rel="noopener" class="social-icon instagram" title="Follow us on Instagram">
                                    <i class="fab fa-instagram"></i>
                                    <span class="social-label">Instagram</span>
                                </a>
                                <a href="https://tiktok.com/@uphs.laguna" target="_blank" rel="noopener" class="social-icon tiktok" title="Follow us on TikTok">
                                    <i class="fab fa-tiktok"></i>
                                    <span class="social-label">TikTok</span>
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="empty-news">
                            <i class="fas fa-newspaper"></i>
                            <h3>No News Available</h3>
                            <p>Check back later for the latest news and announcements.</p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="facebook-feed">
                    <?php $facebook_url = getSetting('facebook_url', 'https://www.facebook.com/uphgma.info.ph'); ?>
                    <a href="<?php echo htmlspecialchars($facebook_url); ?>" target="_blank" rel="noopener" class="facebook-header">
                        <h3 class="facebook-title">
                            <i class="fab fa-facebook"></i>
                            Follow Us on Facebook
                        </h3>
                        <p class="facebook-subtitle">Stay connected with our latest updates</p>
                    </a>
                    <div class="facebook-embed">
                        <div class="fb-page" data-href="<?php echo htmlspecialchars($facebook_url); ?>" data-tabs="timeline" data-width="" data-height="650" data-small-header="true" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .programs-section {
            background: #f8f9fa;
            padding: 4rem 0;
        }
        
        .programs-section .section-title {
            position: relative;
            z-index: 1;
            margin-bottom: 3rem;
            padding-bottom: 1rem;
        }
        
        .programs-section .row.row-gutter {
            margin-top: 1rem;
            position: relative;
            z-index: 0;
        }
        
        .programs-section .row.row-gutter .col-md-4:first-child .program-card,
        .programs-section .row.row-gutter .col-md-4:nth-child(2) .program-card,
        .programs-section .row.row-gutter .col-md-4:nth-child(3) .program-card {
            margin-top: 1rem;
        }
        
        .program-card {
            background: white;
            padding: 1.75rem 1.25rem;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            border-top: 4px solid var(--primary-color);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            position: relative;
            overflow: visible;
        }
        
        .program-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }
        
        .program-card:hover::before {
            transform: scaleX(1);
        }
        
        .program-icon-wrapper {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(28, 77, 161, 0.1), rgba(82, 123, 189, 0.1));
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem auto;
            transition: all 0.3s ease;
            position: relative;
            flex-shrink: 0;
        }
        
        .program-icon-wrapper i {
            font-size: 2.2rem;
            color: var(--primary-color);
            display: block;
            line-height: 1;
        }
        
        .program-card:hover .program-icon-wrapper {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 8px 25px rgba(28, 77, 161, 0.3);
        }
        
        .program-card:hover .program-icon-wrapper i {
            color: white;
        }
        
        .program-title {
            color: var(--primary-color);
            margin-bottom: 0;
            font-weight: 600;
            font-size: 0.95rem;
            line-height: 1.3;
        }
        
        .program-description {
            color: #666;
            margin: 0.5rem 0 0 0;
            font-size: 0.8rem;
            line-height: 1.4;
        }
        
        .featured-text {
            width: 100%;
        }
        
        .program-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.15);
            border-top-color: var(--secondary-color);
            z-index: 10;
            position: relative;
        }
        
        @media (max-width: 992px) {
            .col-md-4.col-gutter {
                flex: 0 0 50% !important;
                max-width: 50% !important;
            }
        }
        
        @media (max-width: 768px) {
            .col-md-4.col-gutter {
                flex: 0 0 100% !important;
                max-width: 100% !important;
            }
            
            .program-card {
                padding: 2rem 1.5rem;
            }
            
            .program-icon-wrapper {
                width: 85px;
                height: 85px;
            }
            
            .program-icon-wrapper i {
                font-size: 2.5rem;
            }
        }
    </style>

    <!-- Programs Offered Section -->
    <section class="section-features programs-section" id="programs">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="section-title">
                        <h2>Programs Offered</h2>
                        <p style="color: #666; margin-top: 1rem; max-width: 700px; margin-left: auto; margin-right: auto;">Explore our comprehensive range of academic programs designed to shape future leaders and professionals</p>
            </div>
                            </div>
                            </div>
            <div class="row row-gutter" style="display: flex; flex-wrap: wrap; margin: 0 -15px;">
                <div class="col-md-4 col-gutter" style="padding: 0 15px; flex: 0 0 33.333%; max-width: 33.333%;">
                    <div class="program-card">
                        <div class="program-icon-wrapper">
                            <i class="fas fa-user-nurse"></i>
                            </div>
                        <div class="featured-text">
                            <h4 class="program-title">BS Nursing</h4>
                            </div>
                            </div>
                    <div class="program-card">
                        <div class="program-icon-wrapper">
                            <i class="fas fa-heartbeat"></i>
                            </div>
                        <div class="featured-text">
                            <h4 class="program-title">BS Physical Therapy</h4>
                    </div>
                            </div>
                    <div class="program-card">
                        <div class="program-icon-wrapper">
                                    <i class="fas fa-laptop-code"></i>
                        </div>
                        <div class="featured-text">
                            <h4 class="program-title">BS Information Technology</h4>
                        </div>
                    </div>
                    <div class="program-card">
                        <div class="program-icon-wrapper">
                            <i class="fas fa-computer"></i>
                        </div>
                        <div class="featured-text">
                            <h4 class="program-title">BS Computer Science</h4>
                        </div>
                    </div>
                    <div class="program-card">
                        <div class="program-icon-wrapper">
                            <i class="fas fa-microphone"></i>
                        </div>
                        <div class="featured-text">
                            <h4 class="program-title">BA Communication Arts</h4>
                        </div>
                    </div>
                    <div class="program-card">
                        <div class="program-icon-wrapper">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="featured-text">
                            <h4 class="program-title">Senior High School Tracks/Strands</h4>
                            <p class="program-description">Academic | Art and Design | Sports | Technical-Vocational Livelihood</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-gutter" style="padding: 0 15px; flex: 0 0 33.333%; max-width: 33.333%;">
                    <div class="program-card">
                        <div class="program-icon-wrapper">
                                    <i class="fas fa-cogs"></i>
                            </div>
                        <div class="featured-text">
                            <h4 class="program-title">BS Engineering</h4>
                        </div>
                            </div>
                    <div class="program-card">
                        <div class="program-icon-wrapper">
                            <i class="fas fa-calculator"></i>
                        </div>
                        <div class="featured-text">
                            <h4 class="program-title">BS Accountancy</h4>
                        </div>
                    </div>
                    <div class="program-card">
                        <div class="program-icon-wrapper">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <div class="featured-text">
                            <h4 class="program-title">BS Business Administration</h4>
                        </div>
                    </div>
                    <div class="program-card">
                        <div class="program-icon-wrapper">
                                    <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                        <div class="featured-text">
                            <h4 class="program-title">Bachelor of Secondary Education</h4>
                        </div>
                            </div>
                    <div class="program-card">
                        <div class="program-icon-wrapper">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="featured-text">
                            <h4 class="program-title">Call Center Training Program</h4>
                        </div>
                    </div>
                    <div class="program-card">
                        <div class="program-icon-wrapper">
                                    <i class="fas fa-school"></i>
                            </div>
                        <div class="featured-text">
                            <h4 class="program-title">Basic Education</h4>
                            <p class="program-description">Pre-School | Grade School | Middle School | Junior High School</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-gutter" style="padding: 0 15px; flex: 0 0 33.333%; max-width: 33.333%;">
                    <div class="program-card">
                        <div class="program-icon-wrapper">
                            <i class="fas fa-book-reader"></i>
            </div>
                        <div class="featured-text">
                            <h4 class="program-title">Bachelor of Elementary Education</h4>
        </div>
            </div>
                    <div class="program-card">
                        <div class="program-icon-wrapper">
                            <i class="fas fa-hotel"></i>
                </div>
                        <div class="featured-text">
                            <h4 class="program-title">BS Hospitality Management</h4>
                                        </div>
                                    </div>
                    <div class="program-card">
                        <div class="program-icon-wrapper">
                            <i class="fas fa-umbrella-beach"></i>
                                    </div>
                        <div class="featured-text">
                            <h4 class="program-title">BS Tourism Management</h4>
                            <p class="program-description">Hotel and Restaurant Management | Nutrition and Dietetics | Bachelor of Science in Tourism Management</p>
                                </div>
                        </div>
                    <div class="program-card">
                        <div class="program-icon-wrapper">
                            <i class="fas fa-brain"></i>
                    </div>
                        <div class="featured-text">
                            <h4 class="program-title">AB/BS Psychology</h4>
                </div>
                    </div>
                    <div class="program-card">
                        <div class="program-icon-wrapper">
                            <i class="fas fa-language"></i>
                        </div>
                        <div class="featured-text">
                            <h4 class="program-title">English Proficiency Training</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Us Section (Brief CTA) -->
    <section id="about" class="light-bg" style="background: linear-gradient(135deg, rgba(28, 77, 161, 0.05) 0%, rgba(82, 123, 189, 0.05) 100%); padding: 4rem 0;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="section-title">
                        <h2>About Us</h2>
                    </div>
                    <p style="max-width: 800px; margin: 1.5rem auto; line-height: 1.8; color: #666; font-size: 1.1rem;">
                        The University of Perpetual Help System – GMA Campus started its operation in 1997. Located in San Gabriel, General Mariano Alvarez, Cavite, we are committed to developing Filipino leaders through quality education, character formation, and community service.
                    </p>
                    <p style="max-width: 800px; margin: 1rem auto; line-height: 1.8; color: #666;">
                        Guided by our philosophy "Character Building is Nation Building," we continue to uphold our founding principles of excellence and service.
                    </p>
                    <div style="margin-top: 2.5rem; text-align: center;">
                        <a href="<?php echo $base_path; ?>about.php" class="btn btn-primary" style="padding: 1rem 2.5rem; font-size: 1.1rem; text-decoration: none; display: inline-block;">
                            Learn More About Us
                    </a>
                </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Academics & Online Services Section -->
    <style>
        .academics-services-section {
            background: #f8f9fa;
            padding: 4rem 0;
        }
        
        .academics-container, .services-container {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
            height: 100%;
            min-height: 100%;
        }
        
        .academics-services-row {
            display: flex;
            flex-wrap: nowrap;
            gap: 2rem;
            align-items: stretch;
        }
        
        .academics-column, .services-column {
            flex: 1 1 50%;
            min-width: 0;
            display: flex;
        }
        
        .section-subtitle {
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            text-align: center;
            font-size: 1.4rem;
            font-weight: 600;
        }
        
        .academics-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        
        .services-grid-top {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .services-grid-bottom {
            grid-column: 1 / -1;
        }
        
        .mz-module-compact {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            padding: 1.25rem;
            border-radius: 10px;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid rgba(28, 77, 161, 0.1);
            position: relative;
            overflow: hidden;
        }
        
        .mz-module-compact::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }
        
        .mz-module-compact:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(28, 77, 161, 0.15);
            border-color: var(--primary-color);
        }
        
        .mz-module-compact:hover::before {
            transform: scaleX(1);
        }
        
        .mz-module-icon {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 0.75rem;
            display: block;
        }
        
        .mz-module-title {
            margin: 0.5rem 0;
            font-size: 0.95rem;
            font-weight: 600;
        }
        
        .mz-module-title a {
            color: #333;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .mz-module-title a:hover {
            color: var(--primary-color);
        }
        
        .mz-module-desc {
            color: #666;
            font-size: 0.8rem;
            margin: 0.5rem 0 0.75rem 0;
            line-height: 1.4;
        }
        
        .mz-module-link {
            display: inline-block;
            margin-top: 0.5rem;
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .mz-module-link:hover {
            color: var(--secondary-color);
            transform: translateX(3px);
        }
        
        .mz-module-link::after {
            content: ' →';
            transition: transform 0.3s ease;
            display: inline-block;
        }
        
        .mz-module-link:hover::after {
            transform: translateX(3px);
        }
        
        @media (max-width: 1200px) {
            .academics-services-row {
                flex-wrap: wrap;
                gap: 1.5rem;
                justify-content: center;
            }
            
            .academics-column, .services-column {
                flex: 0 0 100%;
                min-width: 100%;
                max-width: 100%;
                width: 100%;
            }
            
            .academics-container, .services-container {
                width: 100%;
                max-width: 100%;
            }
        }
        
        @media (max-width: 992px) {
            .academics-services-row {
                flex-wrap: wrap;
                gap: 1.5rem;
                justify-content: center;
            }
            
            .academics-column, .services-column {
                flex: 0 0 100%;
                min-width: 100%;
                max-width: 100%;
                width: 100%;
            }
            
            .academics-container, .services-container {
                width: 100%;
                max-width: 100%;
            }
            
            .academics-grid {
                grid-template-columns: 1fr;
            }
            
            .services-grid-top {
                grid-template-columns: 1fr;
            }
            
            .services-grid-bottom {
                grid-column: 1;
            }
        }
        
        @media (min-width: 768px) and (max-width: 1200px) {
            .academics-services-row {
                flex-direction: column;
                align-items: stretch;
            }
            
            .academics-column, .services-column {
                width: 100% !important;
                max-width: 100% !important;
                flex: 1 1 100% !important;
            }
            
            .academics-container, .services-container {
                width: 100% !important;
                max-width: 100% !important;
            }
        }
        
        @media (max-width: 768px) {
            .academics-container, .services-container {
                padding: 1.5rem;
            }
            
            .section-subtitle {
                font-size: 1.2rem;
            }
            
            .mz-module-compact {
                padding: 1rem;
            }
            
            .mz-module-icon {
                font-size: 1.75rem;
            }
        }
    </style>
    
    <section id="academics-services" class="academics-services-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="section-title">
                        <h2>Academics & Online Services</h2>
                    </div>
                </div>
            </div>
            <div class="academics-services-row" style="margin-top: 2rem;">
                <!-- Academics Column -->
                <div class="academics-column">
                    <div class="academics-container">
                        <h3 class="section-subtitle">Academics</h3>
                        <div class="academics-grid">
                            <div class="mz-module-compact">
                                <i class="fas fa-briefcase mz-module-icon"></i>
                                <h4 class="mz-module-title">
                                    <a href="academics/admission-scholarship.php">Admission & Scholarship</a>
                                </h4>
                                <p class="mz-module-desc">Admission procedures | Requirements | Scholarship Application</p>
                                <a href="academics/admission-scholarship.php" class="mz-module-link">read more</a>
                            </div>
                            <div class="mz-module-compact">
                                <i class="fas fa-file-alt mz-module-icon"></i>
                                <h4 class="mz-module-title">
                                    <a href="academics/registrar.php">Registrar</a>
                                </h4>
                                <p class="mz-module-desc">Announcement | Document Request | Evaluation</p>
                                <a href="academics/registrar.php" class="mz-module-link">read more</a>
                            </div>
                            <div class="mz-module-compact">
                                <i class="fas fa-book mz-module-icon"></i>
                                <h4 class="mz-module-title">
                                    <a href="academics/library.php">Library</a>
                                </h4>
                                <p class="mz-module-desc">Services | New Acquisitions | Facilities</p>
                                <a href="support-services/library.php" class="mz-module-link">read more</a>
                            </div>
                            <div class="mz-module-compact">
                                <i class="fas fa-users mz-module-icon"></i>
                                <h4 class="mz-module-title">
                                    <a href="https://docs.google.com/forms/d/e/1FAIpQLSea8-O2OuuKWgZ17XgKkyLQ7dDOawW31a8vq1nTWDRREODVMQ/viewform" target="_blank">Alumni</a>
                                </h4>
                                <p class="mz-module-desc">Announcement | Upcoming Activities</p>
                                <a href="https://docs.google.com/forms/d/e/1FAIpQLSea8-O2OuuKWgZ17XgKkyLQ7dDOawW31a8vq1nTWDRREODVMQ/viewform" target="_blank" class="mz-module-link">read more</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Online Services Column -->
                <div class="services-column">
                    <div class="services-container">
                        <h3 class="section-subtitle">Online Services</h3>
                        <div class="services-grid-top">
                            <div class="mz-module-compact">
                                <i class="fab fa-google mz-module-icon"></i>
                                <h4 class="mz-module-title">
                                    <a href="https://accounts.google.com/signin/v2/identifier?passive=1209600&continue=https%3A%2F%2Faccounts.google.com%2FManageAccount&followup=https%3A%2F%2Faccounts.google.com%2FManageAccount&flowName=GlifWebSignIn&flowEntry=ServiceLogin" target="_blank">Google Apps for Education</a>
                                </h4>
                                <p class="mz-module-desc">Email | Google Drive | Google Classroom</p>
                                <a href="https://accounts.google.com/signin/v2/identifier?passive=1209600&continue=https%3A%2F%2Faccounts.google.com%2FManageAccount&followup=https%3A%2F%2Faccounts.google.com%2FManageAccount&flowName=GlifWebSignIn&flowEntry=ServiceLogin" target="_blank" class="mz-module-link">read more</a>
                            </div>
                            <div class="mz-module-compact">
                                <i class="fas fa-list-alt mz-module-icon"></i>
                                <h4 class="mz-module-title">
                                    <a href="http://gti-gma.dyndns.org:8339/PARENTS_STUDENTS/parents_student_index.htm" target="_blank">Online Grades</a>
                                </h4>
                                <p class="mz-module-desc">Grades | Student Account</p>
                                <a href="http://gti-gma.dyndns.org:8339/PARENTS_STUDENTS/parents_student_index.htm" target="_blank" class="mz-module-link">read more</a>
                            </div>
                        </div>
                        <div class="mz-module-compact services-grid-bottom">
                            <i class="fas fa-tablet-alt mz-module-icon"></i>
                            <h4 class="mz-module-title">
                                <a href="https://uphslms.com/blended/course/index.php?categoryid=9" target="_blank">eLearning Portal</a>
                            </h4>
                            <p class="mz-module-desc">Lectures | Announcements</p>
                            <a href="https://uphslms.com/blended/course/index.php?categoryid=9" target="_blank" class="mz-module-link">read more</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <style>
        /* Responsive styles for GMA layout */
        @media (max-width: 992px) {
            .col-md-4.col-gutter,
            .col-md-3.text-center {
                flex: 0 0 50% !important;
                max-width: 50% !important;
            }
        }
        
        @media (max-width: 768px) {
            .col-md-4.col-gutter,
            .col-md-3.text-center,
            .col-md-4 {
                flex: 0 0 100% !important;
                max-width: 100% !important;
            }
            
            .section-features .row-gutter {
                margin: 0 !important;
            }
            
            .featured-item {
                margin-bottom: 1.5rem !important;
            }
            
        }
        
        
        .mz-module:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15) !important;
        }
        
        .team-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15) !important;
        }
        
        /* Section title styling */
        .section-title h2 {
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
    </style>



<?php
// Include footer
include 'app/includes/footer.php';
?>

    <script>
        // Interactive Education Level Buttons
        document.addEventListener('DOMContentLoaded', function() {
            const levelButtons = document.querySelectorAll('.level-btn');
            const contentPanels = document.querySelectorAll('.content-panel');
            
            levelButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetLevel = this.getAttribute('data-level');
                    
                    // Remove active class from all buttons
                    levelButtons.forEach(btn => btn.classList.remove('active'));
                    
                    // Add active class to clicked button
                    this.classList.add('active');
                    
                    // Hide all content panels
                    contentPanels.forEach(panel => panel.classList.remove('active'));
                    
                    // Show target content panel
                    const targetPanel = document.getElementById(targetLevel + '-content');
                    if (targetPanel) {
                        targetPanel.classList.add('active');
                    }
                });
            });

            // News Ticker Cycling
            const tickerItems = document.querySelectorAll('.ticker-item');
            if (tickerItems.length > 0) {
                let currentIndex = 0;
                
                // Ensure first item is visible immediately
                tickerItems[0].classList.add('active');
                
                if (tickerItems.length > 1) {
                    function cycleTicker() {
                        // Remove active class from current item
                        tickerItems[currentIndex].classList.remove('active');
                        
                        // Move to next item
                        currentIndex = (currentIndex + 1) % tickerItems.length;
                        
                        // Add active class to new item
                        tickerItems[currentIndex].classList.add('active');
                    }
                    
                    // Start cycling after 3 seconds, then every 4 seconds
                    setTimeout(() => {
                        setInterval(cycleTicker, 4000);
                    }, 3000);
                }
            }
        });
    </script>

