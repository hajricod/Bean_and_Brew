<?php
require_once "../includes/config.php";

$item_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$stmt = $pdo->prepare("SELECT id, name, description, price, image, category, ingredients, calories FROM coffee_items WHERE id = ?");
$stmt->execute([$item_id ?: 0]);
$item = $stmt->fetch();

if (!$item) {
    http_response_code(404);
    $page_title = "Item not found | Bean & Brew";
    include "../includes/header.php";
    echo '<div class="container py-5"><div class="alert alert-warning">We could not find that menu item. <a href="menu.php">Back to the menu</a>.</div></div>';
    include "../includes/footer.php";
    exit;
}

$page_title = htmlspecialchars($item['name']) . " | Bean & Brew";
include "../includes/header.php";
?>

<section class="container py-5">
    <a class="text-decoration-none text-secondary d-inline-block mb-4" href="menu.php">&larr; Back to menu</a>
    <div class="row g-5 align-items-center">
        <div class="col-lg-6">
            <div class="product-art ratio ratio-4x3 rounded-5" aria-hidden="true">
                <img src="<?php echo htmlspecialchars(catalogImageUrl($item['image']), ENT_QUOTES, 'UTF-8'); ?>" alt="" class="w-100 h-100 object-fit-cover rounded-5">
            </div>
        </div>
        <div class="col-lg-6">
            <span class="badge rounded-pill text-bg-light border mb-3"><?php echo htmlspecialchars($item['category']); ?></span>
            <h1 class="display-5 fw-bold"><?php echo htmlspecialchars($item['name']); ?></h1>
            <p class="lead text-secondary"><?php echo htmlspecialchars($item['description']); ?></p>
            <p class="display-6 fw-semibold mb-4">$<?php echo number_format($item['price'], 2); ?></p>
            <form action="cart_handler.php" method="POST" class="js-cart-form mb-4">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="item_id" value="<?php echo (int)$item['id']; ?>">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="redirect" value="product.php">
                <button class="btn btn-warning btn-lg rounded-pill px-5 fw-semibold" type="submit">Add to cart</button>
            </form>
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h2 class="h5 fw-bold">What’s in it</h2>
                    <p class="text-secondary"><?php echo htmlspecialchars($item['ingredients'] ?: 'Ask our baristas for ingredient details.'); ?></p>
                    <div class="d-flex justify-content-between border-top pt-3">
                        <span class="text-secondary">Energy</span>
                        <span class="fw-semibold"><?php echo $item['calories'] === null ? 'Not listed' : (int)$item['calories'] . ' kcal'; ?></span>
                    </div>
                    <p class="small text-secondary mt-3 mb-0">Nutrition values are approximate and may vary by preparation and customizations.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include "../includes/footer.php"; ?>
