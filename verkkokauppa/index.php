<?php
session_start();
require "config/db_connect.php";
include_once "lib/class.page.php";
include_once "lib/class.user.php";
include_once "lib/class.product.php";

$products = new Product($_SESSION['id'], $mysqli_conn, "products");

if (isset($_GET['category'])) {
    $products->category = $_GET['category'];
    $products_by_category = $products->getAllProductsFromCategory();
} else {
    //* set Games to default category
    $products->category = "games";
    $products_by_category = $products->getAllProductsFromCategory();
}

require "templates/header.php";

?>

<!DOCTYPE html>
<html lang="en">

<main>

<!-- image carousel for desktop view -->
<div class="hero-image">
    <div class="center">
        <div class="slides">
            <input type="radio" name="radio-btn" id="radio1">
            <input type="radio" name="radio-btn" id="radio2">
            <input type="radio" name="radio-btn" id="radio3">
            <input type="radio" name="radio-btn" id="radio4">

            <div class="slide first">
                <img src="img/Nintendo.svg.bmp" alt="Nintendo-logo">
            </div>
            <div class="slide">
                <img src="img/sega.jpg" alt="Sega-logo">
            </div>
            <div class="slide">
                <img src="img/playstation.png" alt="PlayStation-logo">
            </div>
            <div class="slide">
                <img src="img/xbox.bmp" alt="XBox-logo">
            </div>
        </div>

        <h2 class="white-text border-black hero-text">MEILTÄ PARHAIMMAT RETROPELIT</h2>
        <h6 class="white-text small-margin-bottom border-black hero-text">JO VUODESTA 1986</h6>
        <a href="category/onsale" class="btn-large custom-hero-color big-margin-bottom">KATSO TÄSTÄ TARJOUKSEMME</a>
    </div>
</div>

<!-- logo for mobile view -->
<div class="img-container-mobile margin-top">
    <a href="./">
        <img src="img/retro-games-logo.jpg" alt="logo" />
    </a>
</div>

<br />
<div class="container">
    <div class="categories-container">
      <div class="categories-list">
        <a href="category/consoles" class="single-category">
          <img src="img/nes-console.jpg" alt="pelikonsolit" />
          <div class="category-title">Konsolit</div>
        </a>

        <a href="category/games" class="single-category">
          <img src="img/mario3.png" alt="pelit" />
          <div class="category-title">Pelit</div>
        </a>

        <a href="category/accessories" class="single-category full-width">
          <img src="img/accessories.jpg" alt="pelitarvikkeet" />
          <div class="category-title">Tarvikkeet</div>
        </a>

        <!-- hidden onsale category for mobile view -->
        <a href="category/onsale" class="single-category full-width visible-on-mobile">
          <img src="img/snes-controller2.jpg" alt="tarjoukset" />
          <div class="category-title">Tarjoukset</div>
        </a>
      </div>
    </div>

    <div class="row">
        <?php foreach ($products_by_category as $product) : ?>
            <div class="col s12 m6 l6 xl6">
                <div class="card">
                    <img class="product-image" src="<?= $product['image'] ?>" alt="product image" />
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
                    </div>
                    <div class="right-align">
                        <div class="card-action">
                            <a href="products/id/<?= $product['id'] ?>" class="brand-text blue-text">
                                Katso lisää
                            </a>
                        </div>

                        <div class="custom-button-box">
                            <a href="add_to_cart.php?id=<?= $product['id'] ?>">
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                                Lisää ostoskoriin
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script type="text/javascript">
        //? image carousel setup
        let counter = 1
        setInterval(() => {
        document.querySelector('#radio' + counter).checked = true
        counter++
        if (counter > 4) {
            counter = 1
        }
        }, 5000)
    </script>

</div>

<div class="map-div">
    <div class="center">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1208.953377374706!2d27.624708840240814!3d62.88766538372691!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4684b08010f7e47d%3A0x37a38fb5d711b613!2sSavon%20ammattiopisto!5e0!3m2!1sfi!2sfi!4v1678525937642!5m2!1sfi!2sfi" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
</div>

</main>

</html>

<?php require "templates/footer.php"; ?>
