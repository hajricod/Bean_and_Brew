<?php
// public/admin_edit_item.php
require_once "../includes/config.php";
requireAdmin();

if (!isset($_GET['id'])) {
    header("Location: admin_menu.php");
    exit;
}

$item_id = (int)$_GET['id'];

// Fetch current item details
$stmt = $pdo->prepare("SELECT * FROM coffee_items WHERE id = ?");
$stmt->execute([$item_id]);
$item = $stmt->fetch();

if (!$item) {
    die("Item not found.");
}

$message = "";
$error = "";

// Handle Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $category = trim($_POST['category'] ?? $item['category']);
    $ingredients = trim($_POST['ingredients'] ?? '');
    $calories = filter_var($_POST['calories'] ?? null, FILTER_VALIDATE_INT);
    $image_name = $_POST['existing_image'] ?? $item['image'];

    // Handle new image upload if provided
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
            $error = "Failed to upload new image.";
        }
    }

    if ($error === "") {
        if (empty($name) || empty($description) || empty($category) || $price <= 0 || empty($image_name)) {
            $error = "Name, description, category, and a positive price are required.";
        } else {
            try {
                $stmt = $pdo->prepare("UPDATE coffee_items SET name = ?, description = ?, price = ?, image = ?, category = ?, ingredients = ?, calories = ? WHERE id = ?");
                $stmt->execute([$name, $description, $price, $image_name, $category, $ingredients, $calories, $item_id]);
                $message = "Item updated successfully!";
                
                // Refresh current item data
                $stmt = $pdo->prepare("SELECT * FROM coffee_items WHERE id = ?");
                $stmt->execute([$item_id]);
                $item = $stmt->fetch();
            } catch (PDOException $e) {
                $error = "Error updating item: " . $e->getMessage();
            }
        }
    }
}

$page_title = "Edit Item | Admin";
include "../includes/header.php";
?>

<section class="container py-4 py-lg-5">
    <div class="row justify-content-center">
        <div class="col-md-9 col-lg-7 col-xl-6">
        <a href="admin_menu.php" class="d-inline-block mb-3 text-secondary text-decoration-none fw-semibold">&larr; Back to Menu Manager</a>
        
        <h1 class="h2 mb-4">Edit Coffee Item</h1>

        <?php if ($message): ?>
            <div class="alert alert-success" role="status"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
            <form action="admin_edit_item.php?id=<?php echo $item_id; ?>" method="POST" enctype="multipart/form-data" class="vstack gap-3">
                <div>
                    <label for="item-name" class="form-label fw-semibold">Name</label>
                    <input type="text" id="item-name" name="name" value="<?php echo htmlspecialchars($item['name']); ?>" class="form-control" required>
                </div>
                <div>
                    <label for="item-description" class="form-label fw-semibold">Description</label>
                    <textarea id="item-description" name="description" class="form-control" rows="3" required><?php echo htmlspecialchars($item['description']); ?></textarea>
                </div>
                <div>
                    <label for="item-price" class="form-label fw-semibold">Price ($)</label>
                    <input type="number" id="item-price" step="0.01" min="0.01" name="price" value="<?php echo $item['price']; ?>" class="form-control" required>
                </div>
                <div>
                    <label for="item-category" class="form-label fw-semibold">Category</label>
                    <select id="item-category" name="category" class="form-select" required>
                        <?php foreach (['Hot Coffee', 'Iced Drinks', 'Pastries'] as $category_option): ?>
                            <option value="<?php echo htmlspecialchars($category_option); ?>" <?php echo $item['category'] === $category_option ? 'selected' : ''; ?>><?php echo htmlspecialchars($category_option); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="item-ingredients" class="form-label fw-semibold">Ingredients</label>
                    <input type="text" id="item-ingredients" name="ingredients" value="<?php echo htmlspecialchars($item['ingredients'] ?? ''); ?>" class="form-control">
                </div>
                <div>
                    <label for="item-calories" class="form-label fw-semibold">Calories (kcal)</label>
                    <input type="number" min="0" id="item-calories" name="calories" value="<?php echo htmlspecialchars((string)($item['calories'] ?? '')); ?>" class="form-control">
                </div>
                <div>
                    <label class="form-label fw-semibold">Current Image</label>
                    <div class="mb-2">
                        <img src="<?php echo htmlspecialchars(catalogImageUrl($item['image']), ENT_QUOTES, 'UTF-8'); ?>" alt="Current product image" height="80" class="rounded border object-fit-cover">
                    </div>
                    <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($item['image']); ?>">
                    <label for="item-image" class="form-label fw-semibold">Upload New Image (optional)</label>
                    <input type="file" id="item-image" name="image" accept="image/*" class="form-control">
                </div>
                <button type="submit" class="btn btn-warning rounded-pill fw-semibold w-100">Update Item</button>
            </form>
            </div>
        </div>
        </div>
    </div>
</section>

<?php include "../includes/footer.php"; ?>
