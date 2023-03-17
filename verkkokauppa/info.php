<?php require "templates/header.php"; ?>

<!DOCTYPE html>
<html lang="en">

<section class="center margin">
    <h4>Kuinka tämä sivusto toimii?</h4>
    <div class="container margin-bottom even-smaller-width">
        <hr>
        <br />
    </div>

    <div class="container center">
        <div class="container">
            <h6 class="bold">KIRJAUTUMINEN / REKISTERÖINTI</h6>
            <p>Klikkaamalla ylhäällä navigaatiossa näkyvää <code>vieras</code>-nappia, pääset login-sivulle. Jos olet jo rekisteröitynyt, voit kirjautua sisään joko käyttäjätunnuksellasi sekä salasanalla, tai Facebook-tunnuksilla. Jos klikkaat <code>rekisteröidy</code>-nappia, pääset rekisteröitymis-sivulle (register.php) jossa voit keksiä itsellesi käyttäjätunnuksen, salasanan sekä ilmoittaa sähköpostiosoitteesi, jonka jälkeen kirjaudut automaattisesti sisälle ja käyttäjätunnuksesi näkyy nyt navigaatiossa <code>vieras</code> sanan tilalla.</p>
            <p>Jos klikkaat nyt nappia jossa käyttäjätunnuksesi näkyy, näet <code>login</code>-sivun sijaan <code>user_information</code>-sivun, jossa voit täyttää valmiiksi omat tietosi, jotka tallennetaan tietokannan <code>users</code>-pöytään. Ne näkyvät jatkossa valmiina <code>kassa</code>-sivulla (checkout.php), jotta sinun ei tarvitse täyttää niitä enää uudelleen tuotteita tilatessa. <code>user_information</code>-sivulla voit myös poistaa halutessa käyttäjätilisi. Painamalla navigaatiossa näkyvää <code>ulos</code>-painiketta, voit kirjautua ulos.</p>
            <br />
            <h6 class="bold">OSTOSKORI</h6>
            <p>Voit lisätä tuotteita ostoskoriin ja niiden lukumäärä näkyy ostoskori-ikonin päällä. Ostoskorissa (cart.php) pystyt poistamaan tuotteita tai etenemään kassalle painamalla <code>maksamaan</code>-nappia.</p>
            <p><code>Kassa</code> (checkout.php) -sivulla sinun täytyy lisätä yhteystietosi jotka tallennetaan tietokannan <code>users</code>-pöytään. Jos olet jo rekisteröitynyt ja kirjautunut sisään sekä lisännyt omat tietosi <code>user_information</code> sivulle, ne näkyvät jo valmiina kassalla. Voit myös valita toimitustavan jonka mukaan kokonaishinta kasvaa. Maksutapana on toistaiseksi vain tilisiirto, joka tulee sähköpostiisi.</p>
            <p>Jos olet tehnyt tilauksen sisäänkirjautuneena, näet kaikki aiemmat tilauksesi <code>user_information</code>-sivulla.</p>
        </div>
    </div>
    <br />
</section>

<?php require "templates/footer.php"; ?>

</html>
