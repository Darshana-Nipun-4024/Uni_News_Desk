<?php
// Smart path resolution for root and subdirectories
if (!isset($base_path)) {
    $base_path = file_exists('assets') ? '' : '../';
}
?>
    <footer class="site-footer">
        <div class="footer-glass-wrapper">
            <div class="footer-content">
                <!-- Column 1: Brand Info -->
                <div class="footer-col brand-col">
                    <div class="footer-logo">
                        <i class="fa-solid fa-newspaper"></i>
                        <span>NSBM NEWSDESK</span>
                    </div>
                    <p class="footer-desc">
                        The official campus news management system of NSBM Green University. Delivering authentic, timely, and engaging updates across academics, events, sports, and student life.
                    </p>
                    <div class="social-links">
                        <a href="https://facebook.com" target="_blank" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="https://instagram.com" target="_blank" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                        <a href="https://linkedin.com" target="_blank" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                        <a href="https://youtube.com" target="_blank" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
                    </div>
                </div>

                <!-- Column 2: News Categories -->
                <div class="footer-col">
                    <h4 class="footer-heading">Categories</h4>
                    <ul class="footer-links">
                        <li><a href="<?= $base_path ?>category.php?slug=sports"><i class="fa-solid fa-angle-right"></i> Sports</a></li>
                        <li><a href="<?= $base_path ?>category.php?slug=academic"><i class="fa-solid fa-angle-right"></i> Academic</a></li>
                        <li><a href="<?= $base_path ?>category.php?slug=events"><i class="fa-solid fa-angle-right"></i> Events</a></li>
                        <li><a href="<?= $base_path ?>category.php?slug=general"><i class="fa-solid fa-angle-right"></i> General</a></li>
                        <li><a href="<?= $base_path ?>category.php?slug=clubs"><i class="fa-solid fa-angle-right"></i> Clubs & Societies</a></li>
                    </ul>
                </div>

                <!-- Column 3: Quick Navigation -->
                <div class="footer-col">
                    <h4 class="footer-heading">Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="<?= $base_path ?>index.php"><i class="fa-solid fa-angle-right"></i> Home</a></li>
                        <li><a href="<?= $base_path ?>breaking_news.php"><i class="fa-solid fa-angle-right"></i> Breaking News</a></li>
                        <li><a href="<?= $base_path ?>submit_news.php"><i class="fa-solid fa-angle-right"></i> Submit Article</a></li>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                <li><a href="<?= $base_path ?>admin/dashboard.php"><i class="fa-solid fa-angle-right"></i> Admin Dashboard</a></li>
                            <?php else: ?>
                                <li><a href="<?= $base_path ?>user/profile.php"><i class="fa-solid fa-angle-right"></i> Student Portal</a></li>
                            <?php endif; ?>
                            <li><a href="<?= $base_path ?>logout.php"><i class="fa-solid fa-angle-right"></i> Logout</a></li>
                        <?php else: ?>
                            <li><a href="<?= $base_path ?>login.php"><i class="fa-solid fa-angle-right"></i> Student / Admin Login</a></li>
                            <li><a href="<?= $base_path ?>register.php"><i class="fa-solid fa-angle-right"></i> Register Account</a></li>
                        <?php endif; ?>
                    </ul>
                </div>

                <!-- Column 4: Campus Contact Info -->
                <div class="footer-col">
                    <h4 class="footer-heading">NSBM Green University</h4>
                    <ul class="contact-info">
                        <li><i class="fa-solid fa-location-dot"></i> Pitipana, Thalagala Road, Homagama, Sri Lanka</li>
                        <li><i class="fa-solid fa-envelope"></i> newsdesk@nsbm.ac.lk</li>
                        <li><i class="fa-solid fa-phone"></i> +94 11 544 5000</li>
                        <li><i class="fa-solid fa-globe"></i> <a href="https://www.nsbm.ac.lk" target="_blank">www.nsbm.ac.lk</a></li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Copyright Bar -->
            <div class="footer-bottom">
                <p>&copy; <?= date('Y'); ?> <strong>NSBM NewsDesk</strong>. All Rights Reserved | Faculty of Computing Project</p>
                <div class="footer-bottom-links">
                    <a href="#">Privacy Policy</a>
                    <span>•</span>
                    <a href="#">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Main JS File -->
    <script src="<?= $base_path ?>assets/js/main.js"></script>
</body>
</html>