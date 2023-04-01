<?php include_once "templates/header.php"; ?>

<!DOCTYPE html>
<html lang="en">

<div class="container center">
    <div class="container center">
        <h4>POISTA KÄYTTÄJÄTILISI</h4>
    </div>
    <div class="container margin-bottom even-smaller-width">
        <hr>
    </div>

    <h6>Haluatko varmasti poistaa käyttäjätilisi?</h6><br />

    <a href="do_delete_user.php" class="btn red">
            <i class="material-icons left">delete</i>POISTA
    </a>
    <a href="user_information" class="btn">takaisin</a>

    <br /><br />
</div>

<?php include "templates/footer.php"; ?>

</html>
