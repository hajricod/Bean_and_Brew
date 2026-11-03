<?php
require_once "../includes/config.php";
$page_title = "Menu | Bean & Brew";
$categories = array_values(array_unique(array_column($coffee_items, 'category')));
include "../includes/header.php";
?>

<section class="container py-5">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4">
        <div>
            <p class="text-uppercase small fw-semibold text-secondary mb-2">Freshly made, thoughtfully sourced</p>
            <h1 class="display-5 fw-bold mb-2">Find your new favorite.</h1>
            <p class="text-secondary mb-0">Coffee, cold drinks, and bakery treats made for your everyday ritual.</p>
        </div>
    </div>

    <div class="d-flex flex-wrap gap-2 mb-4" role="group" aria-label="Filter menu by category" data-category-filters>
        <button class="btn btn-dark rounded-pill px-4 active" type="button" data-category-filter="all" aria-pressed="true">Everything</button>
        <?php foreach ($categories as $category): ?>
            <button class="btn btn-outline-dark rounded-pill px-4" type="button" data-category-filter="<?php echo htmlspecialchars($category, ENT_QUOTES, 'UTF-8'); ?>" aria-pressed="false"><?php echo htmlspecialchars($category); ?></button>
        <?php endforeach; ?>
    </div>

    <div class="row g-4" data-product-grid>
        <?php foreach ($coffee_items as $item): ?>
            <div class="col-sm-6 col-xl-4 product-filter-item" data-category="<?php echo htmlspecialchars($item['category'], ENT_QUOTES, 'UTF-8'); ?>">
                <article class="card product-card h-100 shadow-sm">
                    <a class="text-decoration-none" href="product.php?id=<?php echo (int)$item['id']; ?>" aria-label="View <?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?> details">
                        <div class="product-art" aria-hidden="true">
                            <img src="<?php echo htmlspecialchars(catalogImageUrl($item['image']), ENT_QUOTES, 'UTF-8'); ?>" alt="" style="width: 100%; height: 150px; object-fit: cover;">
                        </div>
                    </a>
                    <div class="card-body d-flex flex-column flex-grow-1 p-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge rounded-pill text-bg-light border"><?php echo htmlspecialchars($item['category']); ?></span>
                            <span class="fw-semibold">$<?php echo number_format($item['price'], 2); ?></span>
                        </div>
                        <h2 class="h4 card-title mb-2"><a class="text-decoration-none text-dark" href="product.php?id=<?php echo (int)$item['id']; ?>"><?php echo htmlspecialchars($item['name']); ?></a></h2>
                        <p class="text-secondary mb-0"><?php echo htmlspecialchars($item['description']); ?></p>
                    </div>
                    <div class="card-footer bg-white border-top-0 p-4 pt-0">
                        <div class="d-flex gap-2">
                            <a class="btn btn-outline-dark rounded-pill flex-grow-1" href="product.php?id=<?php echo (int)$item['id']; ?>">Details</a>
                            <form action="cart_handler.php" method="POST" class="js-cart-form flex-grow-1">
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="item_id" value="<?php echo (int)$item['id']; ?>">
                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                                <input type="hidden" name="redirect" value="menu.php">
                                <button class="btn btn-warning rounded-pill w-100 fw-semibold" type="submit">Add to cart</button>
                            </form>
                        </div>
                    </div>
                </article>
            </div>
        <?php endforeach; ?>
        <div class="col-12 text-center py-5 d-none" data-no-results>
            <p class="h5 mb-1">Nothing in this category just yet.</p>
            <p class="text-secondary mb-0">Try another filter to explore the menu.</p>
        </div>
    </div>
</section>

<script>
document.querySelectorAll('[data-category-filter]').forEach(function (button) {
    button.addEventListener('click', function () {
        var category = button.dataset.categoryFilter;
        var visibleCount = 0;
        document.querySelectorAll('.product-filter-item').forEach(function (card) {
            var visible = category === 'all' || card.dataset.category === category;
            card.classList.toggle('d-none', !visible);
            visibleCount += visible ? 1 : 0;
        });
        document.querySelectorAll('[data-category-filter]').forEach(function (filter) {
            var selected = filter === button;
            filter.classList.toggle('btn-dark', selected);
            filter.classList.toggle('btn-outline-dark', !selected);
            filter.classList.toggle('active', selected);
            filter.setAttribute('aria-pressed', selected ? 'true' : 'false');
        });
        document.querySelector('[data-no-results]').classList.toggle('d-none', visibleCount > 0);
    });
});
</script>
<?php include "../includes/footer.php"; ?>
