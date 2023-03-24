<?php
    session_start();

    //* facebook defines
    define('FB_GRAPH_VERSION', 'v6.0');
    define('FB_GRAPH_DOMAIN', 'https://graph.facebook.com');
    define('FB_APP_STATE', 'eciphp');

    //* facebook credentials
    define('FB_APP_ID', '893632918515305');
    define('FB_APP_SECRET', '0db68d23cb2d516fd77e582f1433c512');
    define('FB_REDIRECT_URI', 'https://sakky.luowa.fi/niko_hoffren/verkkokauppa/login');

    //* define db creds
	define( 'DB_HOST', 'sakky.luowa.fi' ); // database host
	define( 'DB_NAME', 'sakky_niko_hoffren' ); // database name
	define( 'DB_USER', 'niko_hoffren' ); // database username
	define( 'DB_PASS', 'Xyp!u378' ); // database password

    include_once __DIR__ . '/fb.api.php';
