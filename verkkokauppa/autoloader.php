<?php
    session_start();

    //* facebook defines
    define('FB_GRAPH_VERSION', 'v6.0');
    define('FB_GRAPH_DOMAIN', 'https://graph.facebook.com');
    define('FB_APP_STATE', 'eciphp');

    //* facebook credentials
    define('FB_APP_ID', 'xxx');
    define('FB_APP_SECRET', 'xxx');
    define('FB_REDIRECT_URI', 'https://sakky.luowa.fi/niko_hoffren/verkkokauppa/login');

    //* define db creds
	define( 'DB_HOST', 'xxx' ); // database host
	define( 'DB_NAME', 'xxx' ); // database name
	define( 'DB_USER', 'xxx' ); // database username
	define( 'DB_PASS', 'xxx' ); // database password

    include_once __DIR__ . '/fb.api.php';
