<aside class="sidebar">
    <h2><i class="fas fa-newspaper"></i> NewsDesk</h2>
    <nav>
        <a href="dashboard.php" class="<?= basename($_SERVER['PHP_SELF'])=='dashboard.php'?'active':'' ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="manage_articles.php" class="<?= basename($_SERVER['PHP_SELF'])=='manage_articles.php'?'active':'' ?>"><i class="fas fa-file-alt"></i> Articles</a>
        <a href="pending_approvals.php" class="<?= basename($_SERVER['PHP_SELF'])=='pending_approvals.php'?'active':'' ?>"><i class="fas fa-clock"></i> Pending Approvals</a>
        <a href="manage_categories.php" class="<?= basename($_SERVER['PHP_SELF'])=='manage_categories.php'?'active':'' ?>"><i class="fas fa-folder"></i> Categories</a>
        <a href="manage_comments.php" class="<?= basename($_SERVER['PHP_SELF'])=='manage_comments.php'?'active':'' ?>"><i class="fas fa-comments"></i> Comments</a>
        <a href="manage_users.php" class="<?= basename($_SERVER['PHP_SELF'])=='manage_users.php'?'active':'' ?>"><i class="fas fa-users"></i> Users</a>
        <a href="reports.php" class="<?= basename($_SERVER['PHP_SELF'])=='reports.php'?'active':'' ?>"><i class="fas fa-chart-bar"></i> Reports</a>
        <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </nav>
</aside>