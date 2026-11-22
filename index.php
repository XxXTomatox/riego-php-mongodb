<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Control de Riego</title>
    <style>
        body { font-family: 'Segoe UI', system-ui, sans-serif; background: #0f172a; color: #f8fafc; margin: 0; padding: 40px; display: flex; flex-direction: column; align-items: center; }
        .header { width: 100%; max-width: 1000px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .logout { color: #ef4444; text-decoration: none; font-weight: bold; }
        .dashboard { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; width: 100%; max-width: 1000px; }
        .card { background: #1e293b; border-radius: 16px; padding: 25px; text-align: center; border: 1px solid #334155; }
        .card h2 { font-size: 1rem; color: #94a3b8; margin: 0 0 15px 0; text-transform: uppercase; }
        .value { font-size: 2.5rem; font-weight: 700; margin: 10px 0; }
        .status-badge { padding: 6px 16px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; display: inline-block; }
        .status-ok { background: rgba(16, 185, 129, 0.2); color: #10b981; }
        .status-alert { background: rgba(239, 68, 68, 0.2); color: #ef4444; }
        .status-off { background: rgba(148, 163, 184, 0.1); color: #94a3b8; }
        #btn-bomba { background: #3b82f6; color: white; padding: 12px 25px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; margin-top: 30px; font-size: 1rem; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Sistema de Riego</h1>
        <a href="logout.php" class="logout">Cerrar Sesión</a>
    </div>

    <div class="dashboard">
        <div class="card">
            <h2>Temperatura</h2>
            <div class="value" id="temperatura">-- °C</div>
            <div id="temp-status" class="status-badge status-off">Normal</div>
        </div>
        <div class="card">
            <h2>Humedad Tierra</h2>
            <div class="value" id="humedad">-- %</div>
        </div>
        <div class="card">
            <h2>Peso Tanque</h2>
            <div class="value" id="peso">-- g</div>
            <div id="peso-status" class="status-badge status-ok">Suficiente</div>
        </div>
        <div class="card">
            <h2>Bomba</h2>
            <div class="value" id="bomba">--</div>
            <div id="bomba-status" class="status-badge status-off">Apagada</div>
        </div>
    </div>

    <button id="btn-bomba" onclick="toggleBomba()">Cargando...</button>

    <script>
        function actualizar() {
            fetch('api_latest.php')
                .then(res => res.json())
                .then(data => {
                    if (!data) return;
                    document.getElementById('temperatura').textContent = (data.temperatura ? data.temperatura.toFixed(1) : '--') + ' °C';
                    document.getElementById('humedad').textContent = (data.humedad ? data.humedad.toFixed(0) : '--') + ' %';
                    document.getElementById('peso').textContent = (data.peso ? data.peso.toFixed(1) : '--') + ' g';

                    const estaEncendida = (data.bomba_encendida == 1);
                    const bombaVal = document.getElementById('bomba');
                    const bombaBadge = document.getElementById('bomba-status');
                    if (estaEncendida) {
                        bombaVal.textContent = 'ON'; bombaVal.style.color = '#10b981';
                        bombaBadge.textContent = 'Encendida'; bombaBadge.className = 'status-badge status-ok';
                    } else {
                        bombaVal.textContent = 'OFF'; bombaVal.style.color = '#ef4444';
                        bombaBadge.textContent = 'Apagada'; bombaBadge.className = 'status-badge status-off';
                    }
                });
        }

        function toggleBomba() {
            fetch('api_toggle.php', { method: 'POST' })
                .then(res => res.json())
                .then(data => actualizarBoton(data.estado));
        }

        function actualizarBoton(encendido) {
            const btn = document.getElementById('btn-bomba');
            if (encendido) {
                btn.textContent = 'Apagar Bomba (Manual)';
                btn.style.background = '#ef4444';
            } else {
                btn.textContent = 'Encender Bomba (Manual)';
                btn.style.background = '#3b82f6';
            }
        }

        actualizar();
        setInterval(actualizar, 3000);
        
        fetch('api_status.php')
            .then(res => res.json())
            .then(data => actualizarBoton(data.bomba_manual == 1));
    </script>
</body>
</html>