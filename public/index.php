<?php
require_once "../includes/config.php";
$page_title = "Home | Bean & Brew";
include "../includes/header.php";
?>

<section class="hero" style="height: 400px; background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('assets/images/hero.jpg'); background-size: cover; background-position: center; display: flex; flex-direction: column; justify-content: center; align-items: center; color: white; text-align: center;">
    <h1 style="font-size: 3rem; margin-bottom: 10px;">Wake Up with Perfection</h1>
    <p style="font-size: 1.5rem;">Handcrafted coffee, roasted with love.</p>
</section>

<div class="container">
    <section id="menu" class="menu-section" style="text-align: center; margin-bottom: 50px;">
        <h2 style="margin-bottom: 30px;">Our Signature Menu</h2>
        <div class="menu-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px;">
            <?php foreach ($coffee_items as $item): ?>
                <div class="menu-item" style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); text-align: center; transition: transform 0.3s ease;">
                    <div class="item-icon" style="font-size: 3rem; margin-bottom: 15px;"><?php echo $item['image']; ?></div>
                    <h3 style="margin: 10px 0; color: #4e342e;"><?php echo htmlspecialchars($item['name']); ?></h3>
                    <p style="font-size: 0.9rem; color: #666;"><?php echo htmlspecialchars($item['description']); ?></p>
                    <span class="price" style="font-weight: bold; font-size: 1.2rem; color: #795548; display: block; margin-top: 15px;">$<?php echo number_format($item['price'], 2); ?></span>
                    
                    <form action="cart_handler.php" method="POST" style="margin-top: 15px;">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                        <input type="hidden" name="redirect" value="menu.php">
                        <button type="submit" style="background-color: #795548; color: white; padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer; width: 100%;">Add to Cart</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>

<?php include "../includes/footer.php"; ?>
