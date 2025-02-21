<?php

require __DIR__ . '/../vendor/autoload.php';




if (!defined('PAYPAL_CLIENT_ID')) {
    define('PAYPAL_CLIENT_ID', 'AcXgwZ6m3HeyRPWGkcq-YEbPLkga9-5wuNVKP4FV iu8Pi7w3ec8Rf05mRIweVjnEjgdLkLn2klRqxmgA');
}

if (!defined('PAYPAL_SECRET')) {
    define('PAYPAL_SECRET', 'EK0vl_h-mcx94tu9u4SLmo_LSA2l1f1PnzZvYtMe TvHzxyhM_pp9hCXTc138u9-BSW0v00Bg_FkzaYlA');
}

if (!defined('PAYPAL_MODE')) {
    define('PAYPAL_MODE', 'sandbox'); // o 'live'
}

if (!defined('JWT_SECRET_KEY')) {
    define('JWT_SECRET_KEY', 'Vq3p$z!J8m@XcL5dN2^wYk9T&B7oGfQh');
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!defined('BASE_URL')) {
    define("BASE_URL", "http://localhost/TiendaEduardo/");
}



return [
    'smtp' => [
        'host' => 'smtp.mailtrap.io',
        'username' => '828a04b69cf388', // Cambia esto por tu usuario de Mailtrap
        'password' => 'd5c6c03cad7d30', // Cambia esto por tu contraseña de Mailtrap
        'port' => 2525,
        'encryption' => PHPMailer::ENCRYPTION_STARTTLS,
        'from_email' => 'evaleromolina@gmail.com',
        'from_name' => 'Tienda Eduardo',
    ],
];


?>