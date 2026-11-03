<?php
// footer.php
?>
<footer class="site-footer py-4 mt-5">
    <div class="container text-center small">
        &copy; <?php echo date("Y"); ?> Bean & Brew Coffee Shop. Crafted for good coffee.
    </div>
</footer>

<div class="toast-container position-fixed bottom-0 end-0 p-3" aria-live="polite" aria-atomic="true">
    <div id="app-toast" class="toast align-items-center border-0 text-bg-dark" role="status">
        <div class="d-flex">
            <div class="toast-body" data-toast-message>Added to your cart.</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
<script src="assets/vendor/bootstrap/bootstrap.bundle.min.js"></script>
<script src="assets/js/cart.js"></script>
</main>
</body>
</html>
