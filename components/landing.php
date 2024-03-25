<?php

# test for any _SESSION variable
include('landing/landingHeader.php');
?>

<div class="landing-container">
  <h1>Welcome to GameQuest</h1>
  <img src="/game-recommendation-app/images/controller.png" alt="">
  <div class="landing-text-sub-headers">

    <h3>Looking For a New Game to Play?</h3>
    <h3>We can help with that.</h3>
  </div>

  <button class="register-button"><a href="/game-recommendation-app/components/register.php">REGISTER NOW</a></button>
</div>

<div class="testimonial-container">
  <div class="testimonial-header">
    <h5>TESTIMONIALS</h5>
    <p>Check Testimonials From Our Users</p>
  </div>
  <div class="testimonial-grid">
    <div class="testimonial-card">
      <span><i class='bx bxs-quote-alt-left'></i></span>
      <p>
        Lorem ipsum dolor sit amet consectetur adipisicing elit. Recusandae placeat esse debitis iusto aliquid eius.
      </p>
      <hr />
      <img src="../images/person1.jpg" alt="User">
      <p class="testimonial-name">Allan Collins</p>
    </div>
    <div class="testimonial-card">
      <span><i class='bx bxs-quote-alt-left'></i></span>
      <p>
        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Qui, odit.
      </p>
      <hr />
      <img src="../images/person2.jpeg" alt="User">
      <p class="testimonial-name">John Forsythe</p>
    </div>
    <div class="testimonial-card">
      <span><i class='bx bxs-quote-alt-left'></i></span>
      <p>
        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Beatae eum odio iure est, cum illum?
      </p>
      <hr />
      <img src="../images/person3.jpg" alt="User">
      <p class="testimonial-name">Amy Grant</p>
    </div>
  </div>
</div>

<?php
include('landing/landingFooter.php');
?>