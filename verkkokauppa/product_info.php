<?php

session_start();
require "config/db_connect.php";
include_once "lib/class.product.php";
include_once "templates/header.php";

//? get ID from database
if (isset($_GET['id'])) {
    $product_info = new Product($_SESSION['id'], $mysqli_conn, "products");
    $product_info->product_id = mysqli_real_escape_string($conn, $_GET['id']);
    $product = $product_info->getProduct();
}

?>

<!DOCTYPE html>
<html lang="en">

<div class="container center">
    <div class="row">
        <div class="col s12 m12 l12 xl12">
            <div class="card">
                <div class="product-image-gallery">
                    <?php if ($product['image']) : ?>
                        <a href="<?= $product['image'] ?>" target="_blank">
                            <img src="<?= $product['image'] ?>" alt="product image" id="product-image" />
                        </a>
                    <?php endif; ?>
                    <?php if ($product['image2']) : ?>
                        <a href="<?= $product['image2'] ?>" target="_blank">
                            <img src="<?= $product['image2'] ?>" alt="product image" id="product-image" />
                        </a>
                    <?php endif; ?>
                    <?php if ($product['image2']) : ?>
                        <a href="<?= $product['image3'] ?>" target="_blank">
                            <img src="<?= $product['image3'] ?>" alt="product image" id="product-image" />
                        </a>
                    <?php endif; ?>
                </div>

                <div class="card-content center">
                    <h5 class="theme-text"><?= $product["name"]; ?></h5>
                    <div class="product-price center theme-text">
                        <?php
                            $discount = $product['price'] * ($product['discount'] / 100);
                            $discount_price = $product['price'] - $discount;
                            if ($product['discount'] > 0) : ?>
                                <!-- check if the price has a decimal place and insert an zero if so -->
                                <span class="offer-price"><?php echo round($discount_price, 2);
                                if (!preg_match("/^(\d)*(\.)?([\d]{1})?$/", $discount_price)) {
                                        echo ' €';
                                    } else {
                                        echo '0 €';
                                    } ?>
                                </span>
                                <span class="sale-price"><?php echo round($product["price"], 2);
                                if (!preg_match("/^(\d)*(\.)?([\d]{1})?$/", $product["price"])) {
                                        echo ' €';
                                    } else {
                                        echo '0 €';
                                    } ?>
                                </span>
                                <span class="discount-percent">-<?= $product['discount'] ?>%</span>
                        <?php else : ?>
                                <span class="offer-price"><?php echo round($product["price"], 2);
                                if (!preg_match("/^(\d)*(\.)?([\d]{1})?$/", $product["price"])) {
                                        echo ' €';
                                    } elseif (!preg_match('/^\d+\.\d+$/', $product["price"])) {
                                        echo ' €';
                                    } else {
                                        echo '0 €';
                                    } ?>
                                </span>
                        <?php endif; ?>
                    </div>
                    <p class="grey-text smaller-text">Sis. ALV <?= $product["tax"]; ?>%<br /><br /></p>
                    <p class="theme-text"><?= $product["hero_description"]; ?><br /><br /></p>
                    <div class="container">
                        <p class="theme-text"><?= $product["description"]; ?><br /><br /></p>
                    </div>
                </div>
                <div class="right-align">
                    <div class="custom-button-box theme-text">
                        <a href="add_to_cart.php?id=<?= $product['id'] ?>">
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                            Lisää ostoskoriin
                        </a>
                    </div>
                    <a href="./" class="btn-small blue some-margin-right margin-bottom">Takaisin</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "templates/footer.php"; ?>

</html>
