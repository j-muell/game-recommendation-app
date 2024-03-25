<?php
include('landing/landingHeader.php');

?>

<div class="signup-page-wrapper">
    <div class="signup-section-wrapper">
        <div class="form-box">
            <h2>Register</h2>
            <form action="register-inc.php" method="post">
                <div class="signup-input-box">
                    <span class="icon"><i class='bx bxs-user'></i></span>
                    <input type="text" name="userID" required>
                    <label>Username</label>
                </div>
                <div class="signup-input-box">
                    <span class="icon"><i class='bx bxl-steam'></i></span>
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
                <button type="submit" class="signup-button">Sign Up</button>

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