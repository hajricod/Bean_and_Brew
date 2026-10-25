<?php
require_once "../includes/config.php";
$page_title = "Menu | Bean & Brew";
include "../includes/header.php";
?>

<div class="container">
    <section class="menu-section">
        <h2 style="text-align: center;">Our Full Menu</h2>
        <p style="text-align: center;">Explore our range of freshly roasted coffee and delights.</p>
        <div class="menu-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px; margin-top: 30px;">
            <?php foreach ($coffee_items as $item): ?>
                <div class="menu-item" style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); text-align: center; transition: transform 0.3s ease;">
                    <div class="item-icon" style="font-size: 3rem; margin-bottom: 15px;"><?php echo $item['image']; ?></div>
                    <h3 style="margin: 10px 0; color: #4e342e;"><?php echo htmlspecialchars($item['name']); ?></h3>
                    <p style="font-size: 0.9rem; color: #666;"><?php echo htmlspecialchars($item['description']); ?></p>
                    <span class="price" style="font-weight: bold; font-size: 1.2rem; color: #795548; display: block; margin-top: 15px;">$<?php echo number_format($item['price'], 2); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>

<?php include "../includes/footer.php"; ?>
