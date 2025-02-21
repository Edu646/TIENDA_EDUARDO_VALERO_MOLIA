<?php

namespace Controllers;

use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use Lib\Pages;


class PaymentController {
    private $apiContext;
    private $pages;

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Asegúrate de definir estas constantes en un archivo de configuración
        $this->apiContext = new ApiContext(
            new OAuthTokenCredential(PAYPAL_CLIENT_ID, PAYPAL_SECRET)
        );
        $this->apiContext->setConfig(['mode' => PAYPAL_MODE]);
        $this->pages = new Pages();
    }

    public function checkout() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito']) || !isset($_SESSION['total_carrito'])) {
                die("El carrito está vacío o no tiene un total definido.");
            }

            $total = number_format($_SESSION['total_carrito'], 2, '.', '');
            $payer = new Payer();
            $payer->setPaymentMethod('paypal');
            
            $items = [];
            foreach ($_SESSION['carrito'] as $producto) {
                if (!isset($producto['nombre'], $producto['cantidad'], $producto['precio'])) {
                    die("Error en la estructura del carrito.");
                }

                $item = new Item();
                $item->setName($producto['nombre'])
                     ->setCurrency('EUR')
                     ->setQuantity($producto['cantidad'])
                     ->setPrice(number_format($producto['precio'], 2, '.', ''));
                $items[] = $item;
            }

            $itemList = new ItemList();
            $itemList->setItems($items);

            $monto = new Amount();
            $monto->setCurrency('EUR')->setTotal($total);

            $transaction = new Transaction();
            $transaction->setAmount($monto)
                        ->setItemList($itemList)
                        ->setDescription("Compra en nuestra tienda");

            // Configurar URLs de redirección
            $redirectUrls = new RedirectUrls();
            $redirectUrls->setReturnUrl(BASE_URL . '/payment/success')
                         ->setCancelUrl(BASE_URL . '/payment/cancel');

            $payment = new Payment();
            $payment->setIntent("sale")
                    ->setPayer($payer)
                    ->setRedirectUrls($redirectUrls)
                    ->setTransactions([$transaction]);

            try {
                $payment->create($this->apiContext);
                $approvalUrl = $payment->getApprovalLink();
                header("Location: " . $approvalUrl);
                exit;
            } catch (\Exception $e) {
                error_log("Error al procesar el pago: " . $e->getMessage());
                die("Error al procesar el pago. Consulte los logs para más detalles.");
            }
        } else {
            die("Método no permitido.");
        }
    }

    public function success() {
        if (!isset($_GET['paymentId'], $_GET['PayerID'])) {
            die("Pago fallido: Parámetros inválidos.");
        }

        $paymentId = $_GET['paymentId'];
        $payerId = $_GET['PayerID'];

        try {
            $payment = Payment::get($paymentId, $this->apiContext);
            $execution = new PaymentExecution();
            $execution->setPayerId($payerId);

            $result = $payment->execute($execution, $this->apiContext);

            if ($result->getState() !== 'approved') {
                die("El pago no fue aprobado.");
            }

            // Log para verificar respuesta de PayPal
            error_log("Pago exitoso: " . print_r($result, true));

            // Vaciar carrito tras pago exitoso
            unset($_SESSION['carrito']);
            $_SESSION['total_carrito'] = 0;

            // Redirigir al usuario a la página de completado
            header("Location: " . BASE_URL . "/payment/completed");
            exit;
        } catch (\Exception $e) {
            error_log("Error al completar el pago: " . $e->getMessage());
            die("Error al completar el pago: " . $e->getMessage());
        }
    }

    public function cancel() {
        // Opcionalmente puedes mostrar un mensaje o redirigir a otra página
        $this->pages->render('Pago/Cancelado', [
            'mensaje' => "Pago cancelado. No se ha procesado ningún cobro."
        ]);
    }

    public function completed() {
        $this->pages->render('Pago/Completado');
    }
}