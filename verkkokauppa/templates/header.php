<?php
session_start();
require "config/db_connect.php";
include_once "autoloader.php";
include_once "lib/class.base.php";
include_once "lib/class.page.php";
include_once "lib/class.user.php";
include_once "lib/class.cart.php";
include_once "lib/class.product.php";

if (!empty($_SESSION['id'])) {
    $id = $_SESSION['id'];
    $query = "SELECT * FROM users WHERE id = '".$id."'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
}

$cart = new Cart($id, $mysqli_conn, "cart");
$cart->session_id = session_id();
$cart->customer_id = $id;
if ($cart->getAllProductsFromCart()) {
    $cart_result = $cart->getCartProductsQuantity();
}

if ($_SESSION['show_modal']) : ?>
    <a class="btn-small" href="cart">Tuote lisätty ostoskoriin!</a>
    <?php $_SESSION['show_modal'] = false;
endif; ?>

<head>
    <base href="http://sakky.luowa.fi/niko_hoffren/verkkokauppa/" />
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="og:title" content="Niko Hoffren verkkokauppa">
    <meta property="og:description" content="Niko Hoffren verkkokauppa">
    <meta property="og:image" content="img/retro-games-logo.jpg">
    <title>Verkkokauppa</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Amatic+SC&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cuprum:ital@0;1&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Barlow+Semi+Condensed:wght@200&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Libre+Baskerville&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>

<body class="black">

  <nav class="custom-linear-gradient nav-extended">
        <div class="small-container nav-links" id="navLinks">
            <?php ob_start(); ?>

            <!-- Mobile close icon and function call -->
            <i class="fa fa-times" onclick="hideMenu()"></i>

                <ul>
                    <li>
                        <div class="img-container">
                            <a href="./" class="left down header-logo margin-small margin-right border-radius-header-image">
                                <img src="img/retro-games-logo.jpg" alt="retro-games logo" />
                            </a>
                        </div>
                    </li>

                    <li id="nav-button" class="medium-margin-right margin-left">
                        <div class="search-wrapper">
                            <input type="search" id="search" class="white-text" data-search>
                            <label class="label-icon" for="search"><i class="material-icons">search</i></label>
                        </div>
                        <div class="product-search-cards hide" data-product-search-cards-container></div>
                        <template data-product-search-template>
                            <a href="products/id/1" class="data-card">
                            <!-- <a href="products/id/${product.id}" class="data-card"> -->
                                <div class="data-header" data-header></div>
                                <div class="data-body" data-body></div>
                                <!-- <span data-href></span> -->
                            </a>
                        </template>
                    </li>
                    <li id="cart-button" class="">
                        <a id="shoppingCartButton" href="cart" class="btn-small z-depth-1 custom-linear-gradient-2 white-text">
                            <i class="material-icons">shopping_cart</i>
                            <div class="cart-badge-container">
                                <span class="cart-badge"><?= $cart_result; ?></span>
                            </div>
                        </a>
                    </li>
                    <li id="nav-button" class="">
                        <a id="loginButton" href="login" class="btn-small z-depth-1 custom-linear-gradient-2 white-text username-chars">
                            <i class="material-icons left">person_outline</i>
                            <?php
                                if ($_SESSION['fb_logged_in']) {
                                    $show_name = $_SESSION['fb_user_info']['first_name'];
                                } else {
                                    $show_name = ucfirst($row['username'] ?? "vieras");
                                }
                             ?>
                            <?= $show_name; ?>
                        </a>
                    </li>
                    <?php if ($_SESSION['logged_in']): ?>
                        <li id="nav-button" class="margin-top-mobile">
                            <a id="logoutButton" href="do_logout.php" class="btn-small z-depth-1 blue username-chars">
                                <i class="material-icons left">exit_to_app</i>
                                <span>ULOS</span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <li id="nav-button" class="small-margin-right">
                        <img src="img/sun.png" alt="light/dark theme toggle" id="colorThemeIcon">
                    </li>
                </ul>

                <script>
                    //* light/dark theme toggle logic
                    const colorThemeIcon = document.querySelector("#colorThemeIcon")

                    //* Retrieve the user's preference for the theme from local storage
                    const theme = localStorage.getItem("theme")
                    if (theme) {
                        document.body.classList.add(theme)
                        if (theme === "dark-theme") {
                            colorThemeIcon.src = "img/moon.png"
                        }
                    }

                    colorThemeIcon.addEventListener("click", () => {
                        document.body.classList.toggle("dark-theme")
                        if (document.body.classList.contains("dark-theme")) {
                            colorThemeIcon.src = "img/moon.png"
                            localStorage.setItem("theme", "dark-theme")
                        } else {
                            colorThemeIcon.src = "img/sun.png"
                            localStorage.setItem("theme", "light-theme")
                        }
                    })

                    //* adjust the size of the buttons depending on the screen width
                    const shoppingCartButton = document.querySelector("#shoppingCartButton")
                    const loginButton = document.querySelector("#loginButton")
                    const logoutButton = document.querySelector("#logoutButton")

                    if (window.innerWidth < 800) {
                        shoppingCartButton.classList.remove("btn-small")
                        loginButton.classList.remove("btn-small")
                        logoutButton.classList.remove("btn-small")
                    }

                    //* search bar logic
                    const productSearchCardTemplate = document.querySelector("[data-product-search-template]")
                    const productSearchCardContainer = document.querySelector("[data-product-search-cards-container]")
                    const searchInput = document.querySelector("[data-search]")
                    let products = []

                    searchInput.addEventListener("input", e => {
                        //? remove initally hidden class
                        productSearchCardContainer.classList.remove("hide")

                        const value = e.target.value.trim().toLowerCase()
                        if (value === "") {
                            products.forEach(product => {
                            product.element.classList.add("hide")
                            })
                        } else {
                            products.forEach(product => {
                            const isVisible =
                                product.name.toLowerCase().includes(value) ||
                                product.price.toLowerCase().includes(value)
                            product.element.classList.toggle("hide", !isVisible)
                            })
                        }
                    })

                    fetch("ajax.all_products.php")
                    .then(res => res.json())
                    .then(data => {
                        products = data.map(product => {
                            const card = productSearchCardTemplate.content.cloneNode(true).children[0]
                            const header = card.querySelector("[data-header]")
                            const body = card.querySelector("[data-body]")
                            // const href = card.querySelector("[data-href]")
                            header.textContent = product.name
                            body.textContent = `Hinta: ${product.price} €`
                            // href.textContent = `products/id/${product.id}`
                            productSearchCardContainer.append(card)

                            return { name: product.name, price: product.price, element: card }
                        })
                    })

                </script>

            <ul id="nav-mobile" class="right center">
                <?php
                    ob_start();
                    $page = new Page($_SESSION['id'], $mysqli_conn, "pages");
                    $result = $page->getAllPages();
                    foreach ($result as $pages) {
                        echo '<li><a href="'.$pages['alias'].'" id="pageButton" class="btn-small z-depth-1 custom-linear-gradient-button white-text">'.$pages['name'].'</a></li>';
                    }
                ?>
            </ul>

            <script>
                //* adjust the size of the page buttons on mobile
                const pageHref = document.querySelector("#pageButton")

                if (window.innerWidth < 1200) {
                    pageHref.classList.add("username-chars")
                }
            </script>
        </div>

        <!-- Mobile open icon and function call -->
        <i class="fa fa-bars" onclick="showMenu()"></i>
    </nav>
