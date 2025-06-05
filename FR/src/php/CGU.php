<?php
session_start(); // Start the session at the beginning of each PHP page
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <link href="../css/CGU.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="icon" type="image/x-icon" href="../../images/logoAITISAL.ico" />
    <title>AITISAL</title>
</head>
<body>
<header>
    <nav>
        <ul class="menu">
            <li><a href="index.php">Home</a></li>
            <li><a href="chat.php">Let's Chat!</a></li>
            <li><a href="espaceuser.php">Profile</a></li>
        </ul>
    </nav>
</header>
<!-- Logo in the top-left corner -->
<a href="index.php">
    <img src="../../images/logoaitisal.png" id="Logo1" alt="AITISAL Logo" title="AITISAL Logo">
</a>
<h1>Terms of Use - AITISAL</h1>
<!-- Notification badge (top-right) -->
<span id="notification-badge">
  0
</span>

<!-- Notification panel (hidden by default) -->
<section id="notification-box">
    <h3>Notifications</h3>
    <ul id="notification-panel"></ul>
</section>

<div class="section">
    <h2>1. Purpose</h2>
    <p>These Terms of Use (“Terms”) set forth the conditions under which users may access and use the AITISAL website and mobile application (collectively, the “Service”) operated by <strong>AITISAL</strong>, headquartered in Paris, France.</p>
</div>

<div class="section">
    <h2>2. Acceptance of Terms</h2>
    <p>2.1. Access to and use of the Service imply full and unreserved acceptance of these Terms. AITISAL reserves the right to modify these Terms at any time; changes will become effective as soon as they are posted on the Service.</p>
</div>

<div class="section">
    <h2>3. Description of the Service</h2>
    <p>3.1. AITISAL provides a social networking platform specifically for students, designed to foster community, share resources, and combat social isolation. Users may create and customize their profiles, join groups, post content, and interact with other members via the website or mobile application.</p>
</div>

<div class="section">
    <h2>4. Account Creation and Access</h2>
    <p>Certain features of the Service require the creation of a user account. Users agree to provide accurate, current, and complete information during registration and to keep their account credentials confidential. Users are solely responsible for all activities under their accounts.</p>
</div>

<div class="section">
    <h2>5. Intellectual Property</h2>
    <p>All content available on the Service (text, images, logos, videos, audio, etc.) is protected by copyright and trademark law. Unauthorized reproduction, distribution, or commercial use of any material on the Service is strictly prohibited without the express written permission of AITISAL.</p>
</div>

<div class="section">
    <h2>6. Personal Data</h2>
    <p>Personal data collected through the Service is necessary to manage user accounts and facilitate interactions. Such data is processed in accordance with the European General Data Protection Regulation (GDPR) and remains confidential. For more details, please consult our Privacy Policy.</p>
</div>

<div class="section">
    <h2>7. Cookies</h2>
    <p>The Service uses cookies to improve the user experience and measure audience engagement. Users may configure their browser settings to reject cookies, although some features of the Service may be limited as a result.</p>
</div>

<div class="section">
    <h2>8. Liability</h2>
    <p>AITISAL employs all reasonable measures to ensure the availability and quality of the Service but cannot guarantee it will be error-free or uninterrupted. AITISAL’s liability cannot be engaged for indirect damages or losses resulting from the use of the Service.</p>
</div>

<div class="section">
    <h2>9. Modification of the Service</h2>
    <p>AITISAL reserves the right to modify, suspend, or discontinue any part or feature of the Service at any time, without notice or liability.</p>
</div>

<div class="section">
    <h2>10. Term and Termination</h2>
    <p>These Terms remain in effect for as long as the user has access to the Service. AITISAL reserves the right to suspend or delete any user account in the event of a breach of these Terms.</p>
</div>

<div class="section">
    <h2>11. Governing Law and Jurisdiction</h2>
    <p>These Terms are governed by French law. Any dispute arising out of or in connection with these Terms shall be submitted to the competent courts of Paris.</p>
</div>

<div class="section">
    <h2>12. Contact</h2>
    <p>For any questions regarding these Terms:</p>
    <p><a href="faq.php" id="ga" target="_blank">Contact Us</a></p>
</div>

<footer class="mentions-legales">
    <h2>Legal Notice:</h2>
    <p>AITISAL is operated by AITISAL. Any use of the Service is subject to these Terms of Use.</p>
    <p>AITISAL is responsible for data processing in accordance with applicable laws. <br /><br /><br /><br /></p>
</footer>

<img src="../../images/footeraitisal.png" id="LogosFooter" alt="Footer Logos" title="Footer Logos">

<footer>
    <div class="footer">
        <nav>
            <ul>
                <li><a href="CGU.php" id="ga" target="_blank">G.C.U</a></li>
                <li><a href="https://www.isep.fr/" id="ga" target="_blank">Our investors</a></li>
                <li><a href="faq.php" id="ga" target="_blank">Contact</a></li>
            </ul>
        </nav>
    </div>
</footer>

<script src="../js/CGU.js"></script>
<script src="../js/notifications.js"></script>
</body>
</html>
