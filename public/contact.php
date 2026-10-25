<?php
require_once "../includes/config.php";
$page_title = "Contact Us | Bean & Brew";
include "../includes/header.php";
?>

<div class="container">
    <div class="contact-card" style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
        <h2 style="color: #4e342e; margin-top: 0;">Get in Touch</h2>
        <p>Have questions? We'd love to hear from you!</p>
        
        <div class="info-item" style="margin-bottom: 20px;">
            <strong style="display: block; color: #795548;">📍 Location</strong>
            123 Coffee Lane, Brewtown, BT 5678
        </div>

        <div class="info-item" style="margin-bottom: 20px;">
            <strong style="display: block; color: #795548;">📞 Phone</strong>
            +1 (555) 123-4567
        </div>

        <div class="info-item" style="margin-bottom: 20px;">
            <strong style="display: block; color: #795548;">✉️ Email</strong>
            hello@beanandbrew.com
        </div>

        <form action="#" method="POST" style="margin-top: 30px; display: flex; flex-direction: column; gap: 15px;">
            <label for="name" style="font-weight: bold;">Name</label>
            <input type="text" id="name" name="name" required placeholder="Your Name" style="padding: 10px; border: 1px solid #ddd; border-radius: 5px;">

            <label for="email" style="font-weight: bold;">Email</label>
            <input type="email" id="email" name="email" required placeholder="your@email.com" style="padding: 10px; border: 1px solid #ddd; border-radius: 5px;">

            <label for="message" style="font-weight: bold;">Message</label>
            <textarea id="message" name="message" rows="5" required placeholder="How can we help?" style="padding: 10px; border: 1px solid #ddd; border-radius: 5px;"></textarea>

            <button type="submit" style="background-color: #4e342e; color: white; padding: 12px; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; transition: background 0.3s;">Send Message</button>
        </form>
    </div>
</div>

<?php include "../includes/footer.php"; ?>
