<?php
// Incluye el encabezado
require_once __DIR__ . '/layout/header.php';
?>

<!-- Hero Section -->
<div class="hero bg-dark text-white text-center py-5" style="background: url('ruta_a_tu_banner.jpg') no-repeat center center/cover;">
    <div class="container">
        <h1 class="display-4 fw-bold">Bienvenido a Tiendilla</h1>
        <p class="lead">Los mejores productos al mejor precio, solo para ti</p>
        <a href="productos.php" class="btn btn-primary btn-lg mt-3">Explorar Productos</a>
    </div>
</div>


<!-- Beneficios de comprar en Tiendilla -->
<div class="container mt-5 text-center">
    <h2 class="mb-4">¿Por qué comprar con nosotros?</h2>
    <div class="row">
        <div class="col-md-4">
            <img src="icono_envio.png" width="80" alt="Envío rápido">
            <h5 class="mt-3">Envío rápido</h5>
            <p>Recibe tu pedido en tiempo récord.</p>
        </div>
        <div class="col-md-4">
            <img src="icono_seguro.png" width="80" alt="Pago Seguro">
            <h5 class="mt-3">Pago Seguro</h5>
            <p>100% protegido con nuestra pasarela de pago.</p>
        </div>
        <div class="col-md-4">
            <img src="icono_calidad.png" width="80" alt="Calidad garantizada">
            <h5 class="mt-3">Calidad Garantizada</h5>
            <p>Solo los mejores productos para ti.</p>
        </div>
    </div>
</div>

<!-- Pie de página -->
<?php require_once __DIR__ . '/layout/footer.php'; ?>
