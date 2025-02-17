<?php

require __DIR__ . '/../vendor/autoload.php';

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