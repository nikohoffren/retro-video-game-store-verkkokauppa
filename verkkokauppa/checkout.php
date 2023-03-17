<?php
session_start();
require "config/db_connect.php";
include_once "lib/class.user.php";
include_once "lib/class.customer.php";
include_once "lib/class.cart.php";
require('templates/header.php');

$cart = new Cart($_SESSION['id'], $mysqli_conn, "cart");
$cart_total = $_COOKIE['cart_total'];
$user = new User($_SESSION['id'], $mysqli_conn, "users");
$userdata = $user->getUserData();
$errors = array('firstname'=>"", 'lastname'=>"", 'address'=>"", 'zip'=>"", 'city'=>"", 'email'=>"", 'phone'=>"");

if (isset($_POST['submit'])) {
    $customer = new Customer($_SESSION['id'], $mysqli_conn, "customer");
    //? check firstname
    if (empty($_POST['firstname'])) {
        $errors['firstname'] = "Lisää etunimesi <br /><br />";
    } else {
        $customer->first_name = $_POST['firstname'];
    }

    //? check lastname
    if (empty($_POST['lastname'])) {
        $errors['lastname'] = "Lisää sukunimesi <br /><br />";
    } else {
        $customer->last_name = $_POST['lastname'];
    }

    //? check address
    if (empty($_POST['address'])) {
        $errors['address'] = "Lisää osoitteesi <br /><br />";
    } else {
        $customer->address = $_POST['address'];
    }

    //? check zip
    if (empty($_POST['zip'])) {
        $errors['zip'] = "Lisää postinumerosi <br /><br />";
    } else {
        $customer->zip = $_POST['zip'];
    }

    //? check city
    if (empty($_POST['city'])) {
        $errors['city'] = "Lisää kaupunkisi <br /><br />";
    } else {
        $customer->city = $_POST['city'];
    }

    //? check email
    if (empty($_POST['email'])) {
        $errors['email'] = "Lisää sähköpostiosoitteesi <br /><br />";
    } else {
        $customer->email = $_POST['email'];
        if (!filter_var($customer->email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Sähköpostiosoite täytyy olla olemassa oleva <br /><br />";
        }
    }

    //? check phone
    if (empty($_POST['phone'])) {
        $errors['phone'] = "Lisää puhelinnumero <br /><br />";
    } else {
        $customer->phone = $_POST['phone'];
    }

    //? if no errors in form
    if (!array_filter($errors)) {
        $customer->session_id = session_id();
        if ($cart->customer_id > 0) {
            if ($cart->customer_id) {
                header("Location: do_order.php");
            } else {
                echo 'query error: ' . mysqli_error($conn);
            }
        } else {
            if ($customer->addCustomer()) {
                header("Location: do_order.php");
            } else {
                echo 'query error: ' . mysqli_error($conn);
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<div class="container center">
    <h4>KASSA</h4>
</div>
<div class="container margin-bottom even-smaller-width">
    <hr>
</div>
<br />

<div class="container center">
    <div class="container">
        <ul class="collapsible">

            <!-- Customer information form -->
            <li class="active">
                <div class="collapsible-header"><i class="blue-text material-icons">assignment</i>
                    <span>Täytä yhteystietosi</span>
                </div>

                <div class="collapsible-body white">
                    <span>
                        <form id="userInformationForm" class="form" action="checkout" method="POST">

                            <div class="center input-field">
                                <label class="big-text-1 grey-text text-darken-2" for="firstname">Etunimi:</label>
                                <input autofocus autocomplete="off" type="text" class="center" name="firstname"
                                value="<?php if ($_SESSION['logged_in']) {
                                            echo $userdata['first_name'];
                                        } else {
                                            echo $customer->first_name;
                                        }  ?>">
                                <div class="red-text small-text-3"><?= $errors['firstname']; ?></div>
                            </div>

                            <div class="center input-field">
                                <label class="big-text-1 grey-text text-darken-2" for="lastname">Sukunimi:</label>
                                <input autocomplete="off" type="text" class="center" name="lastname"
                                value="<?php if ($_SESSION['logged_in']) {
                                            echo $userdata['last_name'];
                                        } else {
                                            echo $customer->last_name;
                                        }  ?>">
                                <div class="red-text small-text-3"><?= $errors['lastname']; ?></div>
                            </div>

                            <div class="center input-field">
                                <label class="big-text-1 grey-text text-darken-2" for="address">Osoite:</label>
                                <input autocomplete="off" type="text" class="center" name="address"
                                value="<?php if ($_SESSION['logged_in']) {
                                            echo $userdata['address'];
                                        } else {
                                            echo $customer->address;
                                        }  ?>">
                                <div class="red-text small-text-3"><?= $errors['address']; ?></div>
                            </div>

                            <div class="center input-field">
                                <label class="big-text-1 grey-text text-darken-2" for="zip">Postinumero:</label>
                                <input autocomplete="off" type="number" class="center" name="zip"
                                value="<?php if ($_SESSION['logged_in']) {
                                            echo $userdata['zip'];
                                        } else {
                                            echo $customer->zip;
                                        }  ?>">
                                <div class="red-text small-text-3"><?= $errors['zip']; ?></div>
                            </div>

                            <div class="center input-field">
                                <label class="big-text-1 grey-text text-darken-2" for="city">Kaupunki:</label>
                                <input autocomplete="off" type="text" class="center" name="city"
                                value="<?php if ($_SESSION['logged_in']) {
                                            echo $userdata['city'];
                                        } else {
                                            echo $customer->city;
                                        }  ?>">
                                <div class="red-text small-text-3"><?= $errors['city']; ?></div>
                            </div>

                            <div class="center input-field">
                                <label class="big-text-1 grey-text text-darken-2" for="email">Sähköpostiosoite:</label>
                                <input autocomplete="off" type="text" class="center" name="email"
                                value="<?php if ($_SESSION['logged_in']) {
                                            echo $userdata['email'];
                                        } else {
                                            echo $customer->email;
                                        }  ?>">
                                <div class="red-text small-text-3"><?= $errors['email']; ?></div>
                            </div>

                            <div class="center input-field">
                                <label class="big-text-1 grey-text text-darken-2" for="phone">Puhelinnumero:</label>
                                <input autocomplete="off" type="number" class="center" name="phone"
                                value="<?php if ($_SESSION['logged_in']) {
                                            echo $userdata['phone'];
                                        } else {
                                            echo $customer->phone;
                                        }  ?>">
                                <div class="red-text small-text-3"><?= $errors['phone']; ?></div>
                            </div>

                            <!-- hidden submit id for the button outside of the form -->
                            <input type="submit" name="submit" id="submit-form" class="hidden" />
                            <br />
                        </form>
                    </span>
                </div>
            </li>

            <!-- Delivery method -->
            <li>
                <div class="collapsible-header"><i class="blue-text material-icons">local_shipping</i>
                    <span>Valitse toimitustapa</span>
                </div>

                <div class="collapsible-body white">
                    <span>
                        <form id="deliveryMethodForm" action="checkout" method="POST">
                            <p>
                            <label>
                                <input id="free" name="delivery_method[]" type="radio" value="free" checked />
                                <span>Ilmainen kuljetus</span>
                                (Toimitus lähimpään postiin 3-5:ssä arkipäivässä)
                            </label>
                            </p>
                            <p>
                            <label>
                                <input id="fast" name="delivery_method[]" type="radio" value="fast" />
                                <span>Pika: 5,50 €</span>
                                (Toimitus lähimpään postiin kahden arkipäivän sisällä)
                            </label>
                            </p>
                            <p>
                            <label>
                                <input id="home" name="delivery_method[]" type="radio" value="home" />
                                <span>Kotiinkuljetus: 6,90 €</span>
                                (Toimitus kotiosoitteeseen kahden arkipäivän sisällä)
                            </label>
                            </p>

                            <!-- hidden submit id for the button outside of the form -->
                            <input type="submit" name="submit" id="submit-form" class="hidden" />
                        </form>
                    </span>
                </div>
            </li>

            <!-- Payment method -->
            <li>
                <div class="collapsible-header"><i class="blue-text material-icons">payment</i>
                    <span>Valitse maksutapa</span>
                </div>

                <div class="collapsible-body white">
                <span>
                        <form id="paymentTypeForm" action="checkout" method="POST">
                            <p>
                            <label>
                                <input id="check" name="payment_type" type="radio" checked />
                                <span>Tilisiirto</span>
                                (Lasku lähetetään sähköpostiisi)
                            </label>
                            <br />
                            <label>
                                <input id="paypal" name="payment_type" type="radio" />
                                <span>PayPal tai pankki/luottokortti</span>
                            </label>
                            </p>
                        </form>
                    </span>
                </div>
            </li>
        </ul>
    </div>
    <h5 id="totalPrice">Yhteensä: <?= $cart_total ?> €</h5>
    <script>
        const paymentTypeForm = document.querySelector('#paymentTypeForm')
        paymentTypeForm.addEventListener('click', () => {
            if (document.querySelector('#check').checked) {
                    document.cookie = "payment_type = " + 'check'
            } else if (document.querySelector('#paypal').checked) {
                    document.cookie = "payment_type = " + 'paypal'
            }
        })

        const deliveryMethodForm = document.querySelector('#deliveryMethodForm')
        deliveryMethodForm.addEventListener('click', () => {
            let totalPrice = document.querySelector("#totalPrice")
            let cartTotal = 0

            //* create fetch function
            const getData = async () => {
                try {
                    const response = await fetch('ajax.product.php')
                    const data = await response.json()
                    return data
                } catch (error) {
                    console.error(error);
                }
            }

            //* fetch total price inside the tag
            getData().then(data => {
                let price = 0
                let discount = 0
                let discountPrice = 0

                //* map through joined products and cart data
                data.map((val) => {
                    price = val.price * val.quantity
                    discount = price * (val.discount / 100)
                    discountPrice = price - discount
                    cartTotal += discountPrice
                    let fastDelivery = cartTotal + 5.50
                    let homeDelivery = cartTotal + 6.90
                    if (document.querySelector('#free').checked) {
                        document.cookie = "delivery_cost = " + 0
                        totalPrice.innerHTML = `Yhteensä: ${cartTotal.toFixed(2)} €`
                    } else if (document.querySelector('#fast').checked) {
                        document.cookie = "delivery_cost = " + 5.50
                        totalPrice.innerHTML = `Yhteensä: ${fastDelivery.toFixed(2)} €`
                    } else if (document.querySelector('#home').checked) {
                        document.cookie = "delivery_cost = " + 6.90
                        totalPrice.innerHTML = `Yhteensä: ${homeDelivery.toFixed(2)} €`
                    }
                })

            }).catch(error => console.error(error.message))
        })
    </script>

    <!-- create submit button outside of form -->
    <div class="center margin-top">
        <label class="btn-large custom-linear-gradient-2" for="submit-form" tabindex="0">TILAA</label>
    </div>

</div>

<br /><br />

<?php require('templates/footer.php'); ?>

</html>
