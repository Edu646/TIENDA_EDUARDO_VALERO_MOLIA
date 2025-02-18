<?php

require __DIR__ . '/../vendor/autoload.php';

define('PAYPAL_CLIENT_ID', 'AcVXyAkPXUm-EmyHCFiRRv8yIoFScRCJuO1L-WhtSeV1-VVs2QXlqueg5nPv6pqdnAGnmXFq7G4uX4O_');
define('PAYPAL_SECRET', 'EM1sWDEBUmY28wEgdlyz4ritaz-std8aY-XYdmdfP1evpMY_opPpmO9BMD9IsT9UE4cyRtSaddcePVQY');
define('PAYPAL_MODE', 'sandbox'); 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!defined('BASE_URL')) {
    define("BASE_URL", "http://localhost/TiendaEduardo/");
}

define('JWT_SECRET_KEY', 'Vq3p$z!J8m@XcL5dN2^wYk9T&B7oGfQh');

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