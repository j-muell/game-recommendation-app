<?php
include('landing/landingHeader.php');

?>

<div class="login-page-wrapper">
    <div class="login-section-wrapper">
        <div class="form-box login">
            <h2>Login</h2>
            <form action="login-inc.php" method="post">
                <div class="login-input-box">
                    <span class="icon"><i class='bx bxs-user'></i></span>
                    <input type="text" name="userID" required>
                    <label>Username</label>
                </div>
                <div class="login-input-box">
                    <span class="icon"><i class='bx bxs-lock-alt'></i></span>
                    <input type="password" name="pwd" required>
                    <label>Password</label>
                </div>
                <button type="submit" class="login-button">Login</button>

                <div class="login-register">
                    <p>Don't have an account?
                        <a href="/game-recommendation-app/components/signup.php" class="register-link">Register</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include('landing/landingFooter.php')
?>