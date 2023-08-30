<?php
    
    require_once 'vendor/autoload.php';
    require_once 'class-db.php';
      
    define('GOOGLE_CLIENT_ID', '313322943941-5sase14i7tmm4tnm7022upe6vl52bg2i.apps.googleusercontent.com');
    define('GOOGLE_CLIENT_SECRET', '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');
      
    $config = [
        'callback' => 'http://hoisst.com/google-sheets/callback.php',
        'keys'     => ['id' => GOOGLE_CLIENT_ID, 'secret' => GOOGLE_CLIENT_SECRET],
        'scope'    => 'https://www.googleapis.com/auth/spreadsheets',
        'authorize_url_parameters' => ['approval_prompt' => 'force', 'access_type' => 'offline']
    ];
      
    $adapter = new Hybridauth\Provider\Google( $config );
?>