<?php
// public/admin_menu.php
require_once "../includes/config.php";
requireAdmin();

$message = "";
$error = "";

// Handle Deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    $id = filter_input(INPUT_POST, 'item_id', FILTER_VALIDATE_INT);
    if (!$id) {
        http_response_code(400);
        exit('Invalid item ID.');
    }
    try {
        // Delete associated items in order_items first if they exist (though we don't have FK constraints enforced in SQLite by default unless enabled)
        // For simplicity, we just delete the item.
        $stmt = $pdo->prepare("DELETE FROM coffee_items WHERE id = ?");
        $stmt->execute([$id]);
        $message = "Item deleted successfully.";
    } catch (PDOException $e) {
        $error = "Error deleting item: " . $e->getMessage();
    }
}

// Handle Adding New Item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $category = trim($_POST['category'] ?? '');
    $ingredients = trim($_POST['ingredients'] ?? '');
    $calories = filter_var($_POST['calories'] ?? null, FILTER_VALIDATE_INT);
    
    // Image handling
    $image_name = $_POST['existing_image'] ?? '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/assets/images/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $new_file_name = uniqid() . '.' . $file_ext;
        $target_file = $upload_dir . $new_file_name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_name = $new_file_name;
        } else {
            $error = "Failed to upload image.";
        }
    }

    if ($error === "") {
        if (empty($name) || empty($description) || empty($category) || $price <= 0 || empty($image_name)) {
            $error = "Name, description, category, a positive price, and an image are required.";
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO coffee_items (name, description, price, image, category, ingredients, calories) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$name, $description, $price, $image_name, $category, $ingredients, $calories]);
                $message = "New item added successfully!";
            } catch (PDOException $e) {
                $error = "Error adding item: " . $e->getMessage();
            }
        }
    }
}

$items = getCoffeeItems($pdo);
$page_title = "Admin - Menu Manager | Bean & Brew";
include "../includes/header.php";
?>

<section class="container py-4 py-lg-5">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
        <div>
            <p class="text-uppercase small fw-semibold text-secondary mb-2">Shop management</p>
            <h1 class="h2 mb-0">Menu Manager</h1>
        </div>
        <button type="button" class="btn btn-warning rounded-pill px-4 fw-semibold" data-bs-toggle="modal" data-bs-target="#addItemModal">
            <span aria-hidden="true">+</span> Add menu item
        </button>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-success" role="status"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-0 px-4 pt-4 pb-0">
                    <div>
                        <p class="text-uppercase small fw-semibold text-secondary mb-1">Menu management</p>
                        <h2 class="modal-title h4 fw-bold" id="addItemModalLabel">Add a menu item</h2>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="admin_menu.php" method="POST" enctype="multipart/form-data" class="vstack">
                    <div class="modal-body px-4 py-4">
                        <input type="hidden" name="action" value="add">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="item-name" class="form-label fw-semibold">Name</label>
                                <input type="text" id="item-name" name="name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="item-price" class="form-label fw-semibold">Price ($)</label>
                                <input type="number" id="item-price" step="0.01" min="0.01" name="price" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label for="item-description" class="form-label fw-semibold">Description</label>
                                <textarea id="item-description" name="description" class="form-control" rows="3" required></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="item-category" class="form-label fw-semibold">Category</label>
                                <select id="item-category" name="category" required class="form-select">
                                    <option value="Hot Coffee">Hot Coffee</option>
                                    <option value="Iced Drinks">Iced Drinks</option>
                                    <option value="Pastries">Pastries</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="item-calories" class="form-label fw-semibold">Calories (kcal)</label>
                                <input type="number" min="0" id="item-calories" name="calories" class="form-control">
                            </div>
                            <div class="col-12">
                                <label for="item-ingredients" class="form-label fw-semibold">Ingredients</label>
                                <input type="text" id="item-ingredients" name="ingredients" class="form-control" placeholder="Comma-separated ingredients">
                            </div>
                            <div class="col-12">
                                <label for="item-image" class="form-label fw-semibold">Product Image</label>
                                <input type="file" id="item-image" name="image" accept="image/*" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4 pt-0">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning rounded-pill px-4 fw-semibold">Add to Menu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Existing Items Table -->
    <h2 class="h4 mb-3">Current Menu Items</h2>
    <div class="card border-0 shadow-sm rounded-4">
        <div class="table-responsive rounded-4">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th scope="col">Image</th>
                    <th scope="col">Name</th>
                    <th scope="col">Category</th>
                    <th scope="col">Description</th>
                    <th scope="col">Price</th>
                    <th scope="col" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td>
                            <img src="<?php echo htmlspecialchars(catalogImageUrl($item['image']), ENT_QUOTES, 'UTF-8'); ?>" alt="" width="50" height="50" class="rounded object-fit-cover">
                        </td>
                        <td class="fw-semibold"><?php echo htmlspecialchars($item['name']); ?></td>
                        <td><?php echo htmlspecialchars($item['category']); ?></td>
                        <td class="text-secondary"><?php echo htmlspecialchars($item['description']); ?></td>
                        <td class="text-nowrap">$<?php echo number_format($item['price'], 2); ?></td>
                        <td class="text-center">
                            <div class="d-inline-flex gap-2">
                            <a href="admin_edit_item.php?id=<?php echo $item['id']; ?>" class="btn btn-outline-dark btn-sm">Edit</a>
                            <form action="admin_menu.php" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this item?')">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="item_id" value="<?php echo (int)$item['id']; ?>">
                                <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                            </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($items)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-secondary py-4">No items found in the menu.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        </div>
    </div>
</section>

<?php if ($error !== "" && ($_POST['action'] ?? '') === 'add'): ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    bootstrap.Modal.getOrCreateInstance(document.getElementById('addItemModal')).show();
});
</script>
<?php endif; ?>

<?php include "../includes/footer.php"; ?>
