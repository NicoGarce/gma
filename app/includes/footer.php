    <!-- 
    Footer - UPHSL Website
    @author Nico Roell D. Garce
    @title UPHSL Web Administrator 2025
    -->
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <!-- University Info Column -->
                <div class="footer-section university-info">
                    <div class="footer-logo-section">
                        <img src="<?php echo $base_path; ?>assets/images/Logos/Logo2025.png" alt="University of Perpetual Help System" class="footer-logo">
                        <div class="university-details">
                            <h3 class="university-name"><?php echo htmlspecialchars(getSetting('site_name', 'University of Perpetual Help System Laguna')); ?></h3>
                            <p class="university-tagline"><?php echo htmlspecialchars(getSetting('site_tagline', 'Character Building is Nation Building')); ?></p>
                        </div>
                    </div>
                    <div class="social-links" style="margin-top: 15px;">
                        <?php $facebook_url = getSetting('facebook_url', 'https://www.facebook.com/uphsl.info.ph'); ?>
                        <?php if (!empty($facebook_url)): ?>
                        <a href="<?php echo htmlspecialchars($facebook_url); ?>" target="_blank" rel="noopener" class="social-link facebook" title="Follow us on Facebook">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <?php endif; ?>
                        <?php $youtube_url = getSetting('youtube_url', 'https://www.youtube.com/@uphsltv1397'); ?>
                        <?php if (!empty($youtube_url)): ?>
                        <a href="<?php echo htmlspecialchars($youtube_url); ?>" target="_blank" rel="noopener" class="social-link youtube" title="Subscribe to our YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <?php endif; ?>
                        <?php $instagram_url = getSetting('instagram_url', 'https://www.instagram.com/uphs.laguna'); ?>
                        <?php if (!empty($instagram_url)): ?>
                        <a href="<?php echo htmlspecialchars($instagram_url); ?>" target="_blank" rel="noopener" class="social-link instagram" title="Follow us on Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <?php endif; ?>
                        <?php $tiktok_url = getSetting('tiktok_url', 'https://tiktok.com/@uphs.laguna'); ?>
                        <?php if (!empty($tiktok_url)): ?>
                        <a href="<?php echo htmlspecialchars($tiktok_url); ?>" target="_blank" rel="noopener" class="social-link tiktok" title="Follow us on TikTok">
                            <i class="fab fa-tiktok"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Online Services Column -->
                <div class="footer-section">
                    <h4 class="footer-subtitle">Online Services</h4>
                    <ul class="footer-links">
                        <li><a href="https://accounts.google.com/signin/v2/identifier?passive=1209600&continue=https%3A%2F%2Faccounts.google.com%2FManageAccount&followup=https%3A%2F%2Faccounts.google.com%2FManageAccount&flowName=GlifWebSignIn&flowEntry=ServiceLogin" target="_blank" class="service-link">
                            <i class="fab fa-google"></i>
                            Google Apps for Education
                        </a></li>
                        <li><a href="http://gti-gma.dyndns.org:8339/PARENTS_STUDENTS/parents_student_index.htm" target="_blank" class="service-link">
                            <i class="fas fa-list-alt"></i>
                            Online Grades
                        </a></li>
                        <li><a href="https://uphslms.com/blended/course/index.php?categoryid=9" target="_blank" class="service-link">
                            <i class="fas fa-book"></i>
                            Moodle (eLearning Portal)
                        </a></li>
                    </ul>
                    <div class="contact-item" style="margin-top: 1.5rem;">
                        <h5 style="margin-bottom: 0.75rem;">Email</h5>
                        <ul class="email-links">
                            <li><a href="mailto:info@uphsl.edu.ph">info@uphsl.edu.ph</a></li>
                            <li><a href="mailto:admission@gma.uphsl.edu.ph">admission@gma.uphsl.edu.ph</a></li>
                            <li><a href="mailto:registrar@gma.uphsl.edu.ph">registrar@gma.uphsl.edu.ph</a></li>
                        </ul>
                    </div>
                </div>
                
                <!-- Quick Links Column -->
                <div class="footer-section">
                    <h4 class="footer-subtitle">Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="<?php echo $base_path; ?>">Home</a></li>
                        <li><a href="<?php echo $base_path; ?>about.php">About</a></li>
                        <li><a href="<?php echo $base_path; ?>contact/">Contact</a></li>
                        <li><a href="<?php echo $base_path; ?>programs.php">Programs</a></li>
                        <li><a href="<?php echo $base_path; ?>academics/admission-scholarship.php">Admission & Scholarship</a></li>
                        <li><a href="<?php echo $base_path; ?>academics/registrar.php">Registrar</a></li>
                        <li><a href="<?php echo $base_path; ?>academics/library.php">Library</a></li>
                        <li><a href="<?php echo $base_path; ?>enrollment.php">Enrollment</a></li>
                    </ul>
                </div>
                
                <!-- Contact Information Column -->
                <div class="footer-section">
                    <h4 class="footer-subtitle">Contact Information</h4>
                    <div class="contact-details">
                        <div class="contact-item">
                            <h5>Our Business Office</h5>
                            <p>San Gabriel, General Mariano Alvarez, Cavite</p>
                        </div>
                        
                        <div class="contact-item">
                            <h5>Phone</h5>
                            <p>
                                <a href="tel:0464604086">(046) 460-4086</a><br>
                                <a href="tel:025194100">(02) 519-4100</a><br>
                                <a href="tel:09098094200">0909-809-4200</a>
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- COR Certificate Section -->
                <div class="footer-section">
                    <div class="cor-certificate" style="text-align: center; display: flex; align-items: center; justify-content: center; min-height: 150px;">
                        <img src="<?php echo htmlspecialchars($base_path . 'assets/images/COR.png', ENT_QUOTES, 'UTF-8'); ?>" 
                             alt="National Privacy Commission - Data Protection Officer/Data Processing System Registered" 
                             class="cor-image" 
                             style="max-width: 120px; height: auto; display: block; margin: 0 auto;"
                             onerror="this.style.display='none'; this.parentElement.innerHTML='<p style=\'color: #999; font-size: 0.85rem;\'>COR Certificate</p>';">
                    </div>
                </div>
            </div>
            
            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div class="footer-bottom-content">
                    <p class="copyright">&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars(getSetting('site_name', 'University of Perpetual Help System Laguna')); ?>. All rights reserved.</p>
                    <div class="footer-bottom-links">
                        <a href="<?php echo $base_path; ?>privacy-policy.php" class="footer-bottom-link">Privacy Policy</a>
                        <a href="<?php echo $base_path; ?>terms-of-service.php" class="footer-bottom-link">Terms of Service</a>
                        <a href="<?php echo $base_path; ?>accessibility.php" class="footer-bottom-link">Accessibility</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="<?php echo $base_path; ?>assets/js/script.js"></script>
    
    <?php if (isset($additional_js)): ?>
        <?php foreach ($additional_js as $js): ?>
            <script src="<?php echo $js; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <?php if (isset($inline_js)): ?>
        <script>
            <?php echo $inline_js; ?>
        </script>
    <?php endif; ?>
    
    <!-- Back to Top Button -->
    <button id="backToTop" aria-label="Back to top">
        <i class="fas fa-arrow-up"></i>
    </button>
    
    <!-- Facebook SDK -->
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v24.0"></script>
    
</body>
</html>
