<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reloj Laboral</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #reloj { font-size: 3rem; font-weight: bold; color: #0d6efd; }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5 text-center">
        <div class="card shadow p-4 mx-auto" style="max-width: 500px;">
            <h2>RELOJ LABORAL</h2>
            <div id="reloj" class="my-3">00:00:00</div>
            
            <form action="procesar_marcado.php" method="POST">
                <div class="mb-3">
                    <input type="text" name="cedula" class="form-control form-control-lg text-center" placeholder="Ingrese su Cédula" required>
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
            <a href="reporte.php" class="mt-3 d-block text-decoration-none">Ver mis registros</a>
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