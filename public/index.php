<?php
require_once "../includes/config.php";
$page_title = "Bean & Brew | Coffee for your everyday";
include "../includes/header.php";
?>

<section class="hero-panel">
    <div class="container py-5">
        <div class="col-lg-7 py-5">
            <span class="badge rounded-pill text-bg-warning mb-3">Your neighborhood coffee ritual</span>
            <h1 class="display-3 fw-bold lh-1 mb-4">Good coffee.<br>Better mornings.</h1>
            <p class="lead text-white-50 mb-4">Small-batch roasted, carefully brewed, and always served with a little warmth.</p>
            <a class="btn btn-warning btn-lg rounded-pill px-4" href="menu.php">Explore the menu</a>
        </div>
    </div>
</section>

<section class="container py-5">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-end gap-3 mb-4">
        <div>
            <p class="text-uppercase small fw-semibold text-secondary mb-2">Made for your moment</p>
            <h2 class="display-6 fw-bold mb-0">The crowd favorites</h2>
        </div>
        <a class="link-dark fw-semibold" href="menu.php">See the full menu &rarr;</a>
    </div>
    <div class="row g-4">
        <?php foreach (array_slice($coffee_items, 0, 3) as $item): ?>
            <div class="col-md-4">
                <article class="card product-card h-100 shadow-sm">
                    <a href="product.php?id=<?php echo (int)$item['id']; ?>" class="text-decoration-none">
                        <div class="product-art" aria-hidden="true">
                            <img src="<?php echo htmlspecialchars(catalogImageUrl($item['image']), ENT_QUOTES, 'UTF-8'); ?>" alt="" style="width: 100%; height: 150px; object-fit: cover;">
                        </div>
                    </a>
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge rounded-pill text-bg-light border"><?php echo htmlspecialchars($item['category']); ?></span>
                            <span class="fw-semibold">$<?php echo number_format($item['price'], 2); ?></span>
                        </div>
                        <h3 class="h4"><a class="text-dark text-decoration-none" href="product.php?id=<?php echo (int)$item['id']; ?>"><?php echo htmlspecialchars($item['name']); ?></a></h3>
                        <p class="text-secondary"><?php echo htmlspecialchars($item['description']); ?></p>
                        <form action="cart_handler.php" method="POST" class="js-cart-form">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="item_id" value="<?php echo (int)$item['id']; ?>">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                            <input type="hidden" name="redirect" value="index.php">
                            <button class="btn btn-warning rounded-pill w-100 fw-semibold" type="submit">Add to cart</button>
                        </form>
                    </div>
                </article>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="container pb-5">
    <div class="row g-3">
        <div class="col-md-4"><div class="h-100 bg-white rounded-4 p-4 border"><div class="fw-bold mb-2">Carefully brewed</div><p class="text-secondary mb-0">Thoughtful recipes and beans roasted for a balanced cup.</p></div></div>
        <div class="col-md-4"><div class="h-100 bg-white rounded-4 p-4 border"><div class="fw-bold mb-2">Good ingredients</div><p class="text-secondary mb-0">Simple, quality ingredients in every drink and pastry.</p></div></div>
        <div class="col-md-4"><div class="h-100 bg-white rounded-4 p-4 border"><div class="fw-bold mb-2">Your local place</div><p class="text-secondary mb-0">Come by for a quick pick-me-up or stay a little longer.</p></div></div>
    </div>
</section>

<?php include "../includes/footer.php"; ?>
