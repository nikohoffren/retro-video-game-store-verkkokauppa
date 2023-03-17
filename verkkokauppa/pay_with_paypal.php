<?php

session_start();
require "config/db_connect.php";
require "templates/header.php";

?>

<!DOCTYPE html>
<html lang="en">

<div class="container center">
    <h4>VALITSE MAKSUTAPA</h4>
</div>
<div class="container margin-bottom even-smaller-width">
    <hr>
</div>
<br />

<div class="container center">

    <script src="xxx"></script>

    <div id="paypal"></div>

    <script>
        //* paypal setup
        paypal.Buttons({
            createOrder: function (data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: '0.01'
                        }
                    }]
                })
            },
            onApprove: function (data, actions) {
                return actions.order.capture().then(function (details) {
                    alert('Kiitos ' + details.payer.name.given_name + '! Maksu on suoritettu.');
                    location.replace("http://sakky.luowa.fi/niko_hoffren/verkkokauppa/do_paypal_order");
                })
            }
        }).render('#paypal')
    </script>

</div>

</html>
