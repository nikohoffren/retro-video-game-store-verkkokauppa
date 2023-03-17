<?php

session_start();
require "config/db_connect.php";
require "templates/header.php";
include_once "lib/class.cart.php";

$cart = new Cart($_SESSION['id'], $mysqli_conn, "cart");
$cart->session_id = session_id();
$cart->customer_id = $_SESSION['id'];
 if ($cart->getAllProductsFromCart()) {
    $cart_result = $cart->getAllProductsFromCart();
}

?>

<script>
    document.cookie = "payment_type = " + 'check'
    document.cookie = "delivery_cost = " + 0
</script>

<!DOCTYPE html>
<html lang="en">

<!-- create cart table from JSON -->
<div class="container center margin-bottom">
    <h4>OSTOSKORI</h4>
    <div class="center">
        <hr>
    </div>
    <div class="container">
        <?php if ($cart_result) : ?>
            <table id="table" class="responsive-table centered theme-text">
                <thead>
                    <tr>
                        <th>kpl</th>
                        <th>nimi</th>
                        <th>hinta</th>
                        <th><!--kuva--></th>
                        <th>poista</th>
                    </tr>
                </thead>
                <tbody id="table_body"></tbody>
            </table>
            </div>
            <br />
            <h5 id="totalPrice"></h5>
            <br />
            <a href="checkout" class="btn-large custom-linear-gradient-2">MAKSAMAAN</a>
        <?php else : ?>
            <h5>Ostoskori on tyhjä!</h5>
        <?php endif; ?>
    </div>
</div>

<script>
    const tableBody = document.querySelector("#table_body")
    let cartTotal = 0

    //* create fetch function
    const getData = async () => {
        // try {
        //     const response = 'ajax.product.php'
        //     const response2 = 'ajax.product2.php'
        //     const results = await Promise.all([
        //         fetch(response),
        //         fetch(response2)
        //     ])
        //     const dataPromises = results.map(result => result.json())
        //     const data = await Promise.all(dataPromises)
        //     return data
        // } catch (error) {
        //     console.error(error);
        // }

        try {
            const response = await fetch('ajax.product.php')
            const data = await response.json()
            console.log(data)
            return data
        } catch (error) {
            console.error(error);
        }
    }

    //* fetch data to the table
    getData().then(data => {
        let tableData = ""
        let productids = []
        let price = 0
        let discount = 0
        let discountPrice = 0

        //* map through joined products and cart data
        data.forEach((val) => {
            price = val.price * val.quantity
            discount = price * (val.discount / 100)
            discountPrice = price - discount
            cartTotal += discountPrice
            tableData += `<tr>
                <td>${val.quantity}</td>
                <td>${val.name}</td>
                <td>${discountPrice.toFixed(2)} €</td>
                <td><img class="cart-image" src="${val.image}" /></td>
                <td><a href="do_delete_from_cart.php?id=${val.product_id}" class="btn-small red"><i class="material-icons">delete</i></a></td>
                </tr>`
                tableBody.innerHTML = tableData
                totalPrice.innerHTML = `Yhteensä: ${cartTotal.toFixed(2)} €`
                productids.push(val.product_id)
                document.cookie = "productids = " + productids
                document.cookie = "cart_total = " + cartTotal.toFixed(2)
        })

    }).catch(error => console.error(error.message))
</script>

<?php require "templates/footer.php"; ?>

</html>
