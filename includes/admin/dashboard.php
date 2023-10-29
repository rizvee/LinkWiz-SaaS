<?php
// admin/dashboard.php

// Include any necessary functions or database connections here

?>

<!DOCTYPE html>
<html>
<head>
    <title>LinkWiz SaaS - Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="<?php echo plugins_url('/assets/css/styles.css', __FILE__); ?>">
</head>
<body>

<header class="header">
    <h1>LinkWiz SaaS Admin Dashboard</h1>
</header>

<nav class="menu">
    <ul>
        <li><a href="#">Dashboard</a></li>
        <li><a href="#">Links</a></li>
        <li><a href="#">Users</a></li>
        <li><a href="#">Settings</a></li>
    </ul>
</nav>

<div class="container">
    <h2>System Overview</h2>
    <p>Welcome to the LinkWiz SaaS admin dashboard. Here, you can manage links, users, and settings for your system.</p>

    <h2>Statistics</h2>
    <p>Display relevant statistics, charts, and data here.</p>

    <h2>Actions</h2>
    <a href="#" class="button">Create New Link</a>
    <a href="#" class="button">Manage Users</a>
</div>

<footer class="footer">
    <p>&copy; <?php echo date('Y'); ?> LinkWiz SaaS. All rights reserved.</p>
</footer>

</body>
</html>
