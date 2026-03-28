<?php
declare(strict_types=1);

require_once __DIR__ . '/helpers.php';

$flashMessage = get_flash_message();
$csrfToken = csrf_token();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reloj Laboral</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #reloj { font-size: 3rem; font-weight: bold; color: #0d6efd; }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5 text-center">
        <div class="card shadow p-4 mx-auto" style="max-width: 500px;">
            <h1 class="h3 mb-2">RELOJ LABORAL</h1>
            <p class="text-muted mb-4">Registra tu jornada usando tu cedula.</p>

            <?php if ($flashMessage): ?>
                <div class="alert alert-<?= e($flashMessage['type']) ?>" role="alert">
                    <?= e($flashMessage['text']) ?>
                </div>
            <?php endif; ?>

            <div id="reloj" class="my-3">00:00:00</div>

            <form action="procesar_marcado.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                <div class="mb-3">
                    <input
                        type="text"
                        name="cedula"
                        class="form-control form-control-lg text-center"
                        placeholder="Ingrese su Cedula"
                        inputmode="numeric"
                        pattern="\d{5,20}"
                        maxlength="20"
                        autocomplete="off"
                        required
                    >
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" name="accion" value="ingreso" class="btn btn-success">Entrada</button>
                    <div class="row g-2">
                        <div class="col-6">
                            <button type="submit" name="accion" value="ini_refri" class="btn btn-warning w-100">Inic. Refrigerio</button>
                        </div>
                        <div class="col-6">
                            <button type="submit" name="accion" value="fin_refri" class="btn btn-info w-100">Fin Refrigerio</button>
                        </div>
                    </div>
                    <button type="submit" name="accion" value="salida" class="btn btn-danger">Salida</button>
                </div>
            </form>
            <a href="reporte.php" class="mt-3 d-block text-decoration-none">Consultar mis registros</a>
        </div>
    </div>

    <script>
        function actualizarReloj() {
            const ahora = new Date();
            document.getElementById('reloj').innerText = ahora.toLocaleTimeString();
        }

        setInterval(actualizarReloj, 1000);
        actualizarReloj();
    </script>
</body>
</html>
