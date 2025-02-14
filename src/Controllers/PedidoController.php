<?php

namespace Controllers;

use Services\PedidoService;
use Lib\Pages;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use TCPDF;

class PedidoController
{
    private PedidoService $pedidoService;
    private Pages $pages;
    private array $config;

    public function __construct()
    {
        $this->pedidoService = new PedidoService();
        $this->pages = new Pages();
        $this->config = require __DIR__ . '/../../config/config.php'; // Cargar la configuración
    }

    public function crearPedido()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario_id = $_SESSION['user']['id'];
            $usuario_email = $_SESSION['user']['email']; // Asegúrate de que el correo del usuario esté en la sesión
            $provincia = $_POST['provincia'] ?? '';
            $localidad = $_POST['localidad'] ?? '';
            $direccion = $_POST['direccion'] ?? '';

            if (!empty($provincia) && !empty($localidad) && !empty($direccion)) {
                $pedidoId = $this->pedidoService->crearPedido($usuario_id, $provincia, $localidad, $direccion, $_SESSION['carrito']);
                if ($pedidoId) {
                    $this->enviarCorreoConfirmacion($usuario_email, $pedidoId, $_SESSION['carrito']); // Pasa el correo del usuario a la función
                    unset($_SESSION['carrito']);
                    $_SESSION['total_carrito'] = 0;
                    $_SESSION['pedidoId'] = $pedidoId; // Guardar el ID del pedido en la sesión
                    header('Location: ' . BASE_URL . 'confirmacion');
                    exit;
                }
            } else {
                $_SESSION['error'] = 'Todos los campos son obligatorios.';
                header('Location: ' . BASE_URL . 'carrito');
                exit;
            }
        }
    }

    private function enviarCorreoConfirmacion(string $usuario_email, int $pedidoId, array $carrito)
    {
        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor
            $mail->isSMTP();
            $mail->Host = $this->config['smtp']['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $this->config['smtp']['username'];
            $mail->Password = $this->config['smtp']['password'];
            $mail->SMTPSecure = $this->config['smtp']['encryption'];
            $mail->Port = $this->config['smtp']['port'];

            // Destinatarios
            $mail->setFrom($this->config['smtp']['from_email'], $this->config['smtp']['from_name']);
            $mail->addAddress($usuario_email); // Usa el correo del usuario

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Confirmación de Pedido';
            $mail->Body    = "Gracias por tu pedido. Tu número de pedido es: $pedidoId";

            // Generar el PDF
            $pdf = new TCPDF();
            $pdf->AddPage();
            $pdf->SetFont('helvetica', '', 12);

            // Encabezado
            $pdf->SetHeaderData('', 0, 'Tienda Eduardo', "Confirmación de Pedido\nNúmero de Pedido: $pedidoId");

            // Pie de página
            $pdf->setFooterData([0,64,0], [0,64,128]);
            $pdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

            // Contenido del PDF
            $html = '<h1>Productos Comprados</h1>';
            $html .= '<table border="1" cellpadding="4">';
            $html .= '<thead><tr><th>Producto</th><th>Cantidad</th><th>Precio</th></tr></thead>';
            $html .= '<tbody>';
            foreach ($carrito as $item) {
                $html .= '<tr>';
                $html .= '<td>' . $item['nombre'] . '</td>';
                $html .= '<td>' . $item['cantidad'] . '</td>';
                $html .= '<td>' . number_format($item['precio'], 2) . ' €</td>';
                $html .= '</tr>';
            }
            $html .= '</tbody>';
            $html .= '</table>';

            $pdf->writeHTML($html, true, false, true, false, '');
            $pdfContent = $pdf->Output('pedido.pdf', 'S');

            // Adjuntar el PDF al correo
            $mail->addStringAttachment($pdfContent, 'pedido.pdf');

            $mail->send();
        } catch (Exception $e) {
            error_log("No se pudo enviar el correo. Error: {$mail->ErrorInfo}");
        }
    }

    public function confirmacion()
    {
        $pedidoId = $_SESSION['pedidoId'] ?? null;
        if ($pedidoId) {
            $this->pages->render('Product/confirmacion', ['pedidoId' => $pedidoId]);
            unset($_SESSION['pedidoId']); // Limpiar el ID del pedido de la sesión
        } else {
            header('Location: ' . BASE_URL);
            exit;
        }
    }
}