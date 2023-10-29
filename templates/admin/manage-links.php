<?php
// templates/admin/manage-links.php

// Include any necessary functions or database connections here

?>

<!DOCTYPE html>
<html>
<head>
    <title>LinkWiz SaaS - Manage Links</title>
    <link rel="stylesheet" type="text/css" href="<?php echo plugins_url('/assets/css/styles.css', __FILE__); ?>">
</head>
<body>

<header class="header">
    <h1>LinkWiz SaaS - Manage Links</h1>
</header>

<nav class="menu">
    <ul>
        <li><a href="#">All Links</a></li>
        <li><a href="#">Create New Link</a></li>
    </ul>
</nav>

<div class="container">
    <h2>All Links</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>URL</th>
            <th>QR Code</th>
            <th>Actions</th>
        </tr>
        <tr>
            <td>1</td>
            <td>Sample Link 1</td>
            <td>http://example.com/link1</td>
            <td><img src="link_qr_code.png" alt="QR Code"></td>
            <td>
                <a href="#">Edit</a>
                <a href="#">Delete</a>
            </td>
        </tr>
        <!-- Add more link rows dynamically from your database -->
    </table>
</div>

<footer class="footer">
    <p>&copy; <?php echo date('Y'); ?> LinkWiz SaaS. All rights reserved.</p>
</footer>

</body>
</html>
