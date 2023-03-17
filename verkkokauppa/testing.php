<?php

// ini_set('display errors', 1);
// error_reporting(E_ALL);
// $to = 'niko.hoffren@gmail.com';
// $subject = 'Retro Games -lasku';
// $message = '<h1>Hei!</h1><p>Kiitos tilauksestasi!</p>';
// $headers = "From: Lähettäjä <retro@retrogames.com>\r\n";
// $headers .= "Reply-To: reply@retrogames.com\r\n";
// $headers .= "Content-type: text/html\r\n";
// if (mail($to, $subject, $message, $headers)) {
//     header("Location: testing.php?messagesent=1");
// } else {
//     header("Location: index.php");
// }

?>

<head>
    <base href="http://sakky.luowa.fi/niko_hoffren/verkkokauppa/" />
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="og:title" content="Niko Hoffren verkkokauppa">
    <meta property="og:description" content="Niko Hoffren verkkokauppa">
    <meta property="og:image" content="https://www.48hourslogo.com/48hourslogo_data/2018/02/16/70095_1518794276.jpg">
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

<nav class="sidenav-trigger">
    <div class="nav-wrapper">
      <a href="#!" class="brand-logo">Logo</a>
      <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>
      <ul class="right hide-on-med-and-down">
        <li><a href="sass.html">Sass</a></li>
        <li><a href="badges.html">Components</a></li>
        <li><a href="collapsible.html">Javascript</a></li>
        <li><a href="mobile.html">Mobile</a></li>
      </ul>
    </div>
</nav>

  <ul class="sidenav" id="mobile-demo">
    <li><a href="sass.html">Sass</a></li>
    <li><a href="badges.html">Components</a></li>
    <li><a href="collapsible.html">Javascript</a></li>
    <li><a href="mobile.html">Mobile</a></li>
  </ul>

<script>
    document.addEventListener('DOMContentLoaded', () => {
    let elems = document.querySelectorAll('.sidenav')
    let instances = M.Sidenav.init(elems, options)
  })
</script>


