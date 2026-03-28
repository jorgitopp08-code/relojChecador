<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reloj Laboral Pro</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;700&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-bg: #f0f2f5;
            --accent-color: #4f46e5;
            --glass-bg: rgba(255, 255, 255, 0.8);
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Inter', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .clock-card {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            padding: 3rem;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            border: 1px solid rgba(255,255,255,0.3);
            text-align: center;
        }

        h2 {
            font-weight: 700;
            color: #1a202c;
            letter-spacing: -1px;
            margin-bottom: 0.5rem;
        }

        #reloj {
            font-family: 'JetBrains Mono', monospace;
            font-size: 4rem;
            font-weight: 500;
            color: var(--accent-color);
            margin: 1.5rem 0;
            text-shadow: 0 4px 10px rgba(79, 70, 229, 0.2);
        }

        #fecha {
            color: #64748b;
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }

        .form-control-lg {
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            text-align: center;
            font-weight: 600;
            transition: all 0.3s;
        }

        .form-control-lg:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        /* Estilos de botones personalizados */
        .btn-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .btn-main {
            padding: 15px;
            border-radius: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: transform 0.2s, box-shadow 0.2s;
            border: none;
        }

        .btn-main:active {
            transform: scale(0.95);
        }

        .btn-entrada { background: #10b981; color: white; grid-column: span 2; }
        .btn-entrada:hover { background: #059669; box-shadow: 0 8px 15px rgba(16, 185, 129, 0.3); }

        .btn-refri-ini { background: #f59e0b; color: white; }
        .btn-refri-ini:hover { background: #d97706; box-shadow: 0 8px 15px rgba(245, 158, 11, 0.3); }

        .btn-refri-fin { background: #3b82f6; color: white; }
        .btn-refri-fin:hover { background: #2563eb; box-shadow: 0 8px 15px rgba(59, 130, 246, 0.3); }

        .btn-salida { background: #ef4444; color: white; grid-column: span 2; }
        .btn-salida:hover { background: #dc2626; box-shadow: 0 8px 15px rgba(239, 68, 68, 0.3); }

        .footer-link {
            margin-top: 20px;
            color: #4b5563;
            text-decoration: none;
            font-size: 0.9rem;
            display: inline-block;
        }

        .footer-link:hover { color: var(--accent-color); }
    </style>
</head>
<body>

    <div class="clock-card">
        <h2>Bienvenido</h2>
        <div id="fecha">Cargando fecha...</div>
        <div id="reloj">00:00:00</div>
        
        <form action="procesar_marcado.php" method="POST">
            <div class="mb-4">
                <input type="text" name="cedula" class="form-control form-control-lg" placeholder="Número de Cédula" required autocomplete="off">
            </div>

            <div class="btn-grid">
                <button type="submit" name="accion" value="ingreso" class="btn-main btn-entrada">
                    Entrada Laboral
                </button>
                
                <button type="submit" name="accion" value="ini_refri" class="btn-main btn-refri-ini">
                    Inicio Refri
                </button>
                
                <button type="submit" name="accion" value="fin_refri" class="btn-main btn-refri-fin">
                    Fin Refri
                </button>
                
                <button type="submit" name="accion" value="salida" class="btn-main btn-salida">
                    Salida Laboral
                </button>
            </div>
        </form>

        <a href="reporte.php" class="footer-link">Ver historial de asistencias →</a>
    </div>

    <script>
        function actualizarReloj() {
            const ahora = new Date();
            
            // Actualizar Reloj
            const opcionesHora = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
            document.getElementById('reloj').innerText = ahora.toLocaleTimeString('es-ES', opcionesHora);
            
            // Actualizar Fecha
            const opcionesFecha = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            let fechaString = ahora.toLocaleDateString('es-ES', opcionesFecha);
            document.getElementById('fecha').innerText = fechaString.charAt(0).toUpperCase() + fechaString.slice(1);
        }
        
        setInterval(actualizarReloj, 1000);
        actualizarReloj();
    </script>
</body>
</html>