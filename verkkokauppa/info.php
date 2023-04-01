<?php require_once "templates/header.php"; ?>

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
            <p>Voit myös halutessasi kirjautua sisään Facebook-tunnuksilla painamalla <code>kirjaudu</code>-nappia jossa on Facebook-logo. Tällöin sinut ohjataan Facebookin kirjautumiseen ja sen jälkeen takaisin pääsivulle. Jos painat nappia jossa etunimesi näkyy, pääset halutessasi täyttämään omia tietojasi jotka tallennetaan tietokantaan. Etunimi, sukunimi, sähköpostiosoite sekä kuva tulevat suoraan Facebookilta.</p>
            <br />
            <h6 class="bold">OSTOSKORI</h6>
            <p>Voit lisätä tuotteita ostoskoriin ja niiden lukumäärä näkyy ostoskori-ikonin päällä. Ostoskorissa (cart.php) pystyt poistamaan tuotteita tai etenemään kassalle painamalla <code>maksamaan</code>-nappia.</p>
            <p><code>Kassa</code> (checkout.php) -sivulla sinun täytyy lisätä yhteystietosi jotka tallennetaan tietokannan <code>users</code>-pöytään. Jos olet jo rekisteröitynyt ja kirjautunut sisään sekä lisännyt omat tietosi <code>user_information</code> sivulle, ne näkyvät jo valmiina kassalla. Voit myös valita toimitustavan jonka mukaan kokonaishinta kasvaa. Maksutapoina on tilisiirto, jolloin lasku lähetetään sähköpostiisi, tai PayPal, jolloin voit maksaa joko PayPal tunnuksillasi tai pankki/luottokortilla. Tällöin sinut ohjataan PayPalin kirjautumiseen ja maksun suoritettuasi palaat <code>order_done</code>-sivulle ja maksu näkyy suoritettuna.</p>
            <p>Tämän jälkeen ilmoittamaasi sähköpostiosoitteeseen lähetetään <code>Tilausvahvistus</code>, jossa näkyy kokonaissumma sekä tilaamasi tuotteet ja niiden kappalemäärä. Jos valitsit maksutavaksi tilisiirto, sinulle lähetetään sähköpostitse myös <code>Lasku</code>, jossa näkyy ostoksen kokonaissumma.</p>
            <p>Jos olet tehnyt tilauksen sisäänkirjautuneena, näet kaikki aiemmat tilauksesi <code>user_information</code>-sivulla.</p>
            <br />
            <h6 class="bold">TUOTTEET</h6>
            <p>Tuotteet näkyvät valitsemasi kategorian mukaan <code>index</code>-sivulla. Voit valita kategorian navigaatiossa näkyvistä painikkeista tai kuvakarusellin alapuolella näkyvistä laatikoista. Kun sivu ladataan ensimmäistä kertaa, oletuskategoriaksi asetetaan <code>pelit</code>. Kuvakarusellin sisällä näkyvästä <code>hero</code>-napista saat näkyviin myös tarjoukset. Mobiilinäkymässä tämä nappi sekä kuvakaruselli katoaa ja <code>tarjoukset</code>-nappi asetetaan laatikkojen sisälle.</p>
            <p>Jos tuote on tarjouksessa, sen alkuperäinen hinta näkyy yliviivattuna, sekä alennusprosentti vihreänä. Nämä kaikki tulevat tietokannasta ja jos muutat <code>products</code>-taulukosta <code>discount</code>-valuen suuremmaksi kuin nolla, voit lisätä tuotteeseen alennuksen jolloin se muuttuu dynaamisesti sivulla.</p>
            <br />
            <h6 class="bold">HAE TUOTTEITA</h6>
            <p>Navigaation yläreunassa olevasta hakupalkista voit etsiä tuotteita nimen, tai myös haluamasi hinnan perusteella, jolloin tuotteet tulevat näkyviin. Voit klikata tuotetta, jolloin sivun pitäisi ohjautua tuotteen lisätietosivulle, mutta valitettavasti tämä ominaisuus ei lähtenyt millään toimimaan, joten sivu ohjautuu aina tuotteen <code>id 1</code>-lisätietosivulle.</p>
            <br />
            <h6 class="bold">OTA YHTEYTTÄ</h6>
            <p>Voit ottaa yhteyttä lomakkeella, johon täytetään etunimi, sukunimi, puhelinnumero, sähköpostiosoite sekä henkilökohtainen viesti. Painamalla <code>Lähetä</code>-nappia, viesti sekä yhteystietosi lähetetään <code>niko.hoffren@gmail.com</code>-sähköpostiosoitteeseen.</p>
        </div>
        <br />
        <div class="image-container">
            <h6 class="bold">TIETOKANTARAKENNEKAAVIO</h6>
            <img src="img/verkkokauppa-tietokantarakenne.jpg" alt="Verkkokaupan tietokantarakenne-kaavio" class="card">
        </div>
    </div>
    <br />
</section>

<?php require "templates/footer.php"; ?>

</html>
