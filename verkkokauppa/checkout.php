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
        <?php if ($_SESSION['terms_of_service'] == "not_accepted") : ?>
            <h5 class="red-text">Sinun täytyy hyväksyä toimitusehdot jatkaaksesi!</h5>
        <?php endif; ?>
        <br />
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

            <!-- Terms of service -->
            <li>
                <div class="collapsible-header"><i class="blue-text material-icons">check</i>
                    <span>Hyväksy toimitusehdot</span>
                </div>

                <div class="collapsible-body white">
                <span>
                        <form id="termsOfServiceForm" action="checkout" method="POST">
                            <p>
                                <div class="container">
                                    <p class="black-text">Asiointi Retro Games verkkokaupassa on turvallista ja helppoa. Noudatamme toiminnassamme kuluttajasuojalakia ja verkkokauppamme takaa turvallisen ympäristön maksutapahtumien suorittamiseen. Retro Games Oy ei luovuta asiakastietoja eteenpäin, eikä niitä käytetä markkinointitarkoituksiin, ellei asiakas ole sitä erikseen pyytänyt.</p>
                                    <br />
                                    <p class="bold black-text">Toimitustavat</p>

                                    <p class="black-text">Postipaketti: Toimitus lähimpään postitoimistoon Paketti saapuu valitsemaasi toimipisteeseen tai automaattiin yleensä 3-5 arkipäivän kuluessa. Posti säilyttää lähetyksiä noutopisteissä 7 päivää, minkä jälkeen ne palautuvat lähettäjälle. Paketin noutoaikaa voit pidentää kirjautumalla Postin palveluun. Muistathan noutaa pakettisi heti tilauksen saavuttua! Kulut 0,00 euroa.</p>

                                    <p class="black-text">Postipaketti (Express): Toimitus lähimpään postitoimistoon Paketti saapuu valitsemaasi toimipisteeseen tai automaattiin yleensä 1-2 arkipäivän kuluessa. Posti säilyttää lähetyksiä noutopisteissä 7 päivää, minkä jälkeen ne palautuvat lähettäjälle. Paketin noutoaikaa voit pidentää kirjautumalla Postin palveluun. Muistathan noutaa pakettisi heti tilauksen saavuttua! Kulut 5,50 euroa.</p>

                                    <p class="black-text">Kotiinkuljetus (Postin Kotipaketti): paketti toimitetaan perille yleensä 1-2 arkipäivän kuluessa. Posti sopii kanssasi toimitusajankohdan. Kulut 5,50 euroa.</p>

                                    <p class="black-text">Tilaamasi tuotteen toimituskulut näet ennen tilauksen hyväksymistä nettikaupan kassalla kohdassa toimitustapa. Tilaukset toimitetaan kun tilaus on tehty, maksu on suoritettu ja tilattu tuote on saatavilla. Postipaketit ja kirjeet lähtevät matkaan arkisin klo 16:30 ja jos tilaus on toimitusvalmiina klo 16:00 mennessä, se yleensä ehtii matkaan vielä samana päivänä.</p>
                                    <br />
                                    <p class="bold black-text">Maksutavat</p>

                                    <p class="black-text">Ennakkomaksu tilisiirtona: Saat maksutiedot sähköpostiin ja tilaus postitetaan, kun maksu näkyy OP-pankin tilillämme. Jos maksat arkena aamupäivällä, niin maksu näkyy perillä yleensä joskus iltapäivällä. Iltapäivällä ja tai viikonloppuna suoritettu maksu näkyy yleensä seuraavana arkipäivänä. Suosittelemme tätä maksutapaa ennakkotilauksiin. Voit maksaa tilauksen haluamanasi hetkenä, esimerkiksi voit ennakkotilata tuotteen kuukausia ennen julkaisua ja maksaa pelin viikko ennen julkaisua. Kulut 0,00 euroa.</p>
                                    <br />
                                    <p class="bold black-text">Tilauksen palauttaminen</p>
                                    <p class="black-text">Jos haluat palauttaa tuotteen, ota aina ensin yhteyttä puhelimitse (050-666 6666) tai sähköpostin (tilaukset(at)retrogames.fi) kautta. Kaikilla avaamattomilla peleillä on 14 päivän palautusoikeus. Jos palautuksen syy ei ole johtunut myyjän virheestä, laskutamme palautetusta tilauksesta toimituskulut, jotka ovat kirjelähetyksissä 3 euroa ja pakettilähetyksissä paketin koosta riippuen 7-20 euroa. Avattuasi pelin pakkauksen palautusoikeus poistuu. Jos haluat käytettyyn peliin palautusoikeuden, pyydä pelin sinetöimistä tilauksen yhteydessä! Tarvikkeita voi tutkia palatusoikeutta menettämättä, mutta tuotteen käyttöön ottaminen poistaa palautusoikeuden. Tarkemmat palautusohjeet saat asiakaspalvelustamme. Jos tilauksen käsittelyyn ei liity virhettä, asiakas vastaa peruutetun tilauksen toimituskuluista, ja mahdollisista maksunvälityskuluista siltä osalta, kun maksunvälittäjä ei palauta niitä liikeelle. Nämä kulut ovat yleensä korkeintaan 1-2 euroa.</p>
                                    <br />
                                    <p class="bold black-text">Uutuuspelien ennakkotilaus</p>
                                    <p class="black-text">Uutuuspelit pyritään toimittamaan asiakkaille julkaisupäiväksi. Postin hidastuneen toiminnan takia kaikki Priority kirje-toimitustavalla tilatut pelit eivät välttämättä ehdi perille julkaisupäiväksi, vaan saapuvat julkaisupäivän aikoihin, 1-3 päivän sisällä julkaisupäivästä. Economy kirje-toimitustavalla peli saapuu todennäköisesti muutamia päiviä julkaisun jälkeen. Suomen Posti toimittaa Priority-kirjeistä n. 60% perille päivässä, 30% 2. päivässä ja loput 10% muutamaan päivää hitaammin. Varmin tapa saada tilaus perille julkaisupäiväksi, on käyttää postipakettia tai Matkahuollon pakettia. Retro Games ei voi antaa täyttä takuuta pelien ehtimisestä perille julkaisupäiväksi, vaikkakin teemme kaikkemme sen eteen. Ennakkotilauksen palauttamisesta hyvitetään tuotteen hinta, vähennettynä palautuskustannuksella.</p>
                                    <br />
                                    <p class="bold black-text">Usean eri aikaan lähetettävän tuotteen tilaaminen</p>
                                    <p class="black-text">Jos haluat, että tilauksesi lähetetään useassa eri pakettilähetyksessä, suosittelemme tekemään tuotteista erilliset tilaukset. Näin voit maksaa helposti postikulut erillisille lähetyksille. Yksillä paketin postikuluilla lähetämme yhden lähetyksen, joka voi tarkoitttaa, että lähetys odottaa yhtä puuttuvaa tuotetta. Jos tilauksella on erillisiä kirjekokoisia lähetyksiä, lähetämme ne ilman lisäkuluja erikseen; uutuusjulkaisut priority-kirjeenä ja muut economy-kirjeenä.</p>
                                    <br />
                                    <p class="bold black-text">Tarjoustuotteet</p>
                                    <p class="black-text">Tarjoustuotteet saapuvat meille maahantuojien tarjouserissä. Ne ovat käyttämättömiä, mutta niiden pakkaukset voivat olla päältä kuluneita. Ellei toisin mainita, tarjoukset ovat voimassa niin kauan kuin tavaraa riittää. Jos tarjoustuote on loppunut, emme pysty takaamaan että tuotetta pystytään toimittamaan lisää tarjoushinnalla.</p>
                                    <br />
                                    <p class="bold black-text">Käytetyt pelit</p>
                                    <p class="black-text">Käytetyillä peleillä on sama toimivuustakuu, kuin uusillakin peleillä. Pyrimme kunnostamaan kaikki pelit hyvän kuntoisiksi ennen myyntiä. Pelin kansipaperit saattavat poiketa nettisivuilla kuvatun uuden version kansista. Joissain harvoissa tapauksissa käytetyn pelin kansipaperit puuttuvat kokonaan, jolloin tilalla on geneerinen kansi tuotenimellä ja asia on huomioitu tuotteen hinnassa. Käytetyt pelit ovat sinetöimättömiä digitaalisia tallenteita, joilla ei ole normaalia palautusoikeutta. Jos haluat pelille palautusoikeuden, voit pyytää tuotteelle sinetöintiä. Varastossamme on toisinaan useampia erilaisia versioita samasta käytetystä pelistä eivätkä kaikki ole välttämättä tuotekuvaa vastaavia. Voit halutessasi esittää tilauksen lisätiedoissa toiveita minkä version pelistä ensisijaisesti haluat. Käytettyjen pelien ALV on 0%</p>
                                    <br />
                                    <p class="bold black-text">Uudet pelit</p>
                                    <p class="black-text">Uusilla avaamattomilla peleillä on 14 päivän palautusoikeus. Pieni osa uusista peleistä on myymälämme hyllyssä, jolloin pelin levy ja manuaali on poistettu kotelosta ja pelin sinetti on tämän johdosta avattu. Jos haluat ehdottomasti alkuperäisessä sinetissä olevan pelin, mainitse asiasta tilauksen lisätiedoissa, jolloin tilaamme lisäerän, jos jäljellä on vain yksi tuote avatussa kotelossa.</p>
                                    <br />
                                    <p class="bold black-text">Tuotekuvat</p>
                                    <p class="black-text">Tuotekuvat saattavat joissain tapauksissa erota hieman tuotteesta. Kun peleistä tulee edullisia uusintapainoksia, kuten Playstation Hits tai Nintendo Selects-painoksia, saattaa edullisemalla hinnalla saada jonkun aikaa myös alkuperäispainosta. Tuotekuva voi tässä vaiheessa olla alkuperäisestä tuotteesta. Kun tuotteen hinta on laskenut alas, ja haluat ehdottomasti saada alkuperäispainoksen, kannattaa erikseen varmistaa että kyseessä on alkuperäispainos, sillä tuote saattaa vaihtua uudelleenjulkaisuun, vaikka kuvaa ei olisi vielä huomattu vaihtaa. Tuotekuva saattaa erota lopullisesta tuotteesta myös tarvikkeiden kohdalla, joissa on usein pieniä variaatioita tuote-erästä riippuen. Esimerkiksi  tarvikeohjaimet usein vaihtelevat hieman, lisäksi edullisten tarvikeohjaimien värit vaihtelevat paljon.</p>
                                    <br />
                                    <p class="bold black-text">Muita ehtoja</p>
                                    <p class="black-text">Pidätämme oikeuden hinnanmuutoksiin ja hinnoitteluvirheiden korjaamiseen, erityisesti ennakkotilausten kohdalla. Ilmoitetut julkaisupäivät ja ennakkohinnat ovat arvioita ja saattavat helposti muuttua ennen tavaran toimitusta. Tarjoamme mahdollisuuden tilata suuren valikoiman varastostamme loppuneita tuotteita, joiden saatavuus tavarantoimittajilamme vaihtelee. Varastosta loppuneiden tuotteiden toimitusajat riippuvat tavaran toimittajista, eikä Konsolinet pysty niihin vaikuttamaan. Suosittelemme käyttämään varastosta loppuneiden tuotteiden kohdalla maksutapaa, jossa maksu suoritetaan vasta toimituksen yhteydessä, kuten esim. Klarna laskua. Uusista peleistä osa on esillä myymälässämme. Tästä johtuen pelin muovit on avattu ja sisältö on suojassa tiskin takana myymälävarkauksien ehkäisemiseksi. n. 2% nettikaupasta tilatuista uusista peleistä on näitä hyllykappaleita, jotka ovat siis uusia, käyttämättömiä pelejä, ja niitä koskevat samat toimitusehdot kuin muita uusia tuotteita. Retro Games ei vastaa viivästyneiden toimitusaikojen tai kuljetuksen aiheuttamista välillisistä haitoista (force majeure). Petostapauksissa pidätämme oikeuden periä petoksesta aiheutuneet kulut. Pidätämme oikeuden peruuttaa tilaus tuotteen saatavuusongelmien vuoksi.</p>
                                </div>
                                <br />
                            <label>
                                <input id="termsOfService" name="terms_of_service" type="radio" />
                                <span>Hyväksy</span>
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

        const termsOfServiceForm = document.querySelector('#termsOfServiceForm')
        termsOfServiceForm.addEventListener('click', () => {
            if (document.querySelector('#termsOfService').checked) {
                    document.cookie = "terms_of_service = " + 'accepted'
            }
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
