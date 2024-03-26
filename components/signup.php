<?php
include('landing/landingHeader.php');

?>

<div class="signup-page-wrapper">
    <div class="signup-section-wrapper">
        <div class="form-box">
            <h2>Register</h2>
            <form action="../includes/signup-inc.php" method="post">
                <div class="signup-input-box">
                    <span class="icon"><i class='bx bxs-user'></i></span>
                    <input type="text" name="userID" required>
                    <label>Username</label>
                </div>
                <div class="signup-input-box">
                    <span class="icon"><a href="https://help.steampowered.com/en/faqs/view/2816-BE67-5B69-0FEC" class="question-anchor" target="_blank"><i class='bx bx-question-mark' id="question" title="Steam ID is required in order to give recommendation. Click to find out how to find your Steam ID. NOTE: Your steam profile must be set to public for this app to function!"></i></a></span>
                    <input type="text" name="steamID" required>
                    <label>Steam ID</label>
                </div>
                <div class="signup-input-box">
                    <span class="icon"><i class='bx bxs-lock-alt'></i></span>
                    <input type="password" name="pwd" required>
                    <label>Password</label>
                </div>
                <div class="signup-input-box">
                    <span class="icon"><i class='bx bxs-lock-alt'></i></span>
                    <input type="password" name="pwd2" required>
                    <label>Password Confirmation</label>
                </div>
                <div class="terms">
                    <label><input type="checkbox" name="terms">
                        I agree to the terms and conditions.</label>
                </div>
                <button type="submit" name="submit" class="signup-button">Sign Up</button>

                <div class="signup-register">
                    <p>Already have an account?
                        <a href="/game-recommendation-app/components/login.php" class="login-link">Login</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include('landing/landingFooter.php')
?>