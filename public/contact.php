<?php
require_once "../includes/config.php";
$page_title = "Contact Us | Bean & Brew";
include "../includes/header.php";
?>

<div class="container">
    <div class="contact-card" style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
        <h2 style="color: #4e342e; margin-top: 0;">Get in Touch</h2>
        <p>Have questions? We'd love to hear from you!</p>
        
        <div class="info-item mb-3">
            <strong class="d-flex align-items-center gap-2 text-secondary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--coffee-brown)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/>
                    <circle cx="12" cy="10" r="2.5"/>
                </svg>
                Location
            </strong>
            123 Coffee Lane, Brewtown, BT 5678
        </div>

        <div class="info-item mb-3">
            <strong class="d-flex align-items-center gap-2 text-secondary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--coffee-brown)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M21 16.5v3a1.5 1.5 0 0 1-1.6 1.5 18.9 18.9 0 0 1-8.2-2.9 18.6 18.6 0 0 1-5.8-5.8A18.9 18.9 0 0 1 2.5 4.1 1.5 1.5 0 0 1 4 2.5h3a1.5 1.5 0 0 1 1.5 1.3c.1 1 .4 2 .7 2.9a1.5 1.5 0 0 1-.3 1.6L7.6 9.6a15 15 0 0 0 5.8 5.8l1.3-1.3a1.5 1.5 0 0 1 1.6-.3c.9.3 1.9.6 2.9.7a1.5 1.5 0 0 1 1.8 2Z"/>
                </svg>
                Phone
            </strong>
            +1 (555) 123-4567
        </div>

        <div class="info-item mb-3">
            <strong class="d-flex align-items-center gap-2 text-secondary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--coffee-brown)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <rect x="3" y="5" width="18" height="14" rx="2"/>
                    <path d="m3 7 9 6 9-6"/>
                </svg>
                Email
            </strong>
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
