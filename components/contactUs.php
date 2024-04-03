<?php

include 'gameSite/sidePageHeader.php';

?>

<div class="contact-wrapper">
    <h1>Contact Us</h1>
    <p>If you have any questions or concerns, please feel free to contact us via email. We will get back to you as soon as possible.</p>
    <input type="text" name="fullName" id="fullName" placeholder="Enter your full name...">
    <input type="text" name="email" id="email" placeholder="Enter your email...">
    <textarea name="message" id="message" placeholder="Message"></textarea>
    <button onclick="sendMail()">Send</button>
</div>

<?php

include 'gameSite/sidePageFooter.php';
