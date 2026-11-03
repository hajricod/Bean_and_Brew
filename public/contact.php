<?php
require_once "../includes/config.php";
$page_title = "Contact Us | Bean & Brew";
include "../includes/header.php";
?>

<section class="container py-4 py-lg-5">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9">
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4 p-md-5">
        <h1 class="h2 text-dark">Get in Touch</h1>
        <p class="text-secondary mb-4">Have questions? We'd love to hear from you!</p>
        
        <div class="row g-3 mb-4">
        <div class="col-md-4">
        <div class="info-item h-100 rounded-3 bg-light p-3">
            <strong class="d-flex align-items-center gap-2 text-secondary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--coffee-brown)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/>
                    <circle cx="12" cy="10" r="2.5"/>
                </svg>
                Location
            </strong>
            <div class="mt-2">123 Coffee Lane, Brewtown, BT 5678</div>
        </div>
        </div>
        <div class="col-md-4">
        <div class="info-item h-100 rounded-3 bg-light p-3">
            <strong class="d-flex align-items-center gap-2 text-secondary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--coffee-brown)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M21 16.5v3a1.5 1.5 0 0 1-1.6 1.5 18.9 18.9 0 0 1-8.2-2.9 18.6 18.6 0 0 1-5.8-5.8A18.9 18.9 0 0 1 2.5 4.1 1.5 1.5 0 0 1 4 2.5h3a1.5 1.5 0 0 1 1.5 1.3c.1 1 .4 2 .7 2.9a1.5 1.5 0 0 1-.3 1.6L7.6 9.6a15 15 0 0 0 5.8 5.8l1.3-1.3a1.5 1.5 0 0 1 1.6-.3c.9.3 1.9.6 2.9.7a1.5 1.5 0 0 1 1.8 2Z"/>
                </svg>
                Phone
            </strong>
            <div class="mt-2">+1 (555) 123-4567</div>
        </div>
        </div>
        <div class="col-md-4">
        <div class="info-item h-100 rounded-3 bg-light p-3">
            <strong class="d-flex align-items-center gap-2 text-secondary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--coffee-brown)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <rect x="3" y="5" width="18" height="14" rx="2"/>
                    <path d="m3 7 9 6 9-6"/>
                </svg>
                Email
            </strong>
            <div class="mt-2">hello@beanandbrew.com</div>
        </div>
        </div>
        </div>

        <form action="#" method="POST" class="vstack gap-3">
            <div>
                <label for="name" class="form-label fw-semibold">Name</label>
                <input type="text" id="name" name="name" class="form-control" required placeholder="Your Name" autocomplete="name">
            </div>
            <div>
                <label for="email" class="form-label fw-semibold">Email</label>
                <input type="email" id="email" name="email" class="form-control" required placeholder="your@email.com" autocomplete="email">
            </div>
            <div>
                <label for="message" class="form-label fw-semibold">Message</label>
                <textarea id="message" name="message" class="form-control" rows="5" required placeholder="How can we help?"></textarea>
            </div>
            <button type="submit" class="btn btn-warning rounded-pill fw-semibold align-self-md-start px-4">Send Message</button>
        </form>
        </div>
    </div>
        </div>
    </div>
</section>

<?php include "../includes/footer.php"; ?>
