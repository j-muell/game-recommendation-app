<?php
include('landing/landingHeader.php');

?>

<div class="login-page-wrapper">
    <div class="login-section-wrapper">
        <div class="form-box login">
            <h2>Login</h2>

            <form action="../includes/login-inc.php" method="post">
                <div class="login-input-box">
                    <span class="icon"><i class='bx bxs-user'></i></span>
                    <input type="text" name="userID" placeholder="Username">
                    <div class="error-login">
                        <?php
                        if (isset($_GET["error"])) {
                            if ($_GET["error"] == "wronglogin") {
                                echo "<p>Incorrect username.</p>";
                            } else if ($_GET["error"] == "emptyinput") {
                                echo "<p>All fields must be filled.</p>";
                            }
                        }
                        ?>
                    </div>
                </div>
                <div class="login-input-box">
                    <span class="icon"><i class='bx bxs-lock-alt'></i></span>
                    <input type="password" name="pwd" placeholder="Password">
                    <div class="error-login">
                        <?php
                        if (isset($_GET["error"])) {
                            if ($_GET["error"] == "wrongpass") {
                                echo "<p>Incorrect password.</p>";
                            } else if ($_GET["error"] == "emptyinput") {
                                echo "<p>All fields must be filled.</p>";
                            }
                        }
                        ?>
                    </div>
                </div>
                <button type="submit" name="submit" class="login-button">Login</button>

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