<?php

include 'gameSite/sidePageHeader.php';

?>

<div class="contact-wrapper">
    <h1>Contact Us</h1>
    <p>If you have any questions or concerns, please feel free to contact us via email. We will get back to you as soon as possible.</p>
    <div class="contact-inputs">
        <input type="text" name="fullName" id="fullName" placeholder="Enter your full name...">
        <div class="error-name"></div>
        <input type="text" name="email" id="email" placeholder="Enter your email...">
        <div class="error-email"></div>
        <textarea name="message" id="message" placeholder="Message"></textarea>
        <div class="error-message"></div>
        <button onclick="sendMail()">Send</button>
    </div>

</div>

<script>



</script>

<?php

include 'gameSite/sidePageFooter.php';
