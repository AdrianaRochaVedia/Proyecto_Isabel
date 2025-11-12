<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /');
    exit;
}

$puntaje_total = isset($_POST['puntaje_total']) ? (int)$_POST['puntaje_total'] : 0;
$puntaje_max = isset($_POST['puntaje_max']) ? (int)$_POST['puntaje_max'] : 1;
$aprobado = isset($_POST['aprobado']) ? (int)$_POST['aprobado'] : 0;
$porcentaje = isset($_POST['porcentaje']) ? (float)$_POST['porcentaje'] : 0;
$id_leccion = isset($_POST['id_leccion']) ? (int)$_POST['id_leccion'] : 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado de Evaluación</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}
body {
    font-family:  'Segoe UI', Arial, sans-serif;
    background: linear-gradient(135deg, #ffd7cc 0%, #fde2dc 50%, #ffe7e2 100%);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}
.container {
    max-width: 700px;
    width: 100%;
}
.card {
    background: #fff;
    border: 2px solid #f5cfd0;
    border-radius: 24px;
    padding: 40px;
    box-shadow: 0 20px 60px rgba(242, 139, 107, 0.2);
    text-align: center;
    animation: fadeInUp 0.6s ease-out;
}
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
h1 {
    color: #b42b6f;
    font-size: 36px;
    font-weight: 900;
    margin-bottom: 20px;
    text-shadow: 2px 2px 4px rgba(180, 43, 111, 0.1);
}
.result-icon {
    font-size: 80px;
    margin: 20px 0;
    animation: bounce 1s ease-in-out;
}
@keyframes bounce {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.2); }
}
.score-circle {
    width: 200px;
    height: 200px;
    border-radius: 50%;
    background: linear-gradient(135deg, #fff5f0, #fff);
    border: 10px solid;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    margin: 30px auto;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}
.score-circle.approved {
    border-color: #4CAF50;
}
.score-circle.failed {
    border-color: #f28b6b;
}
.score-number {
    font-size: 48px;
    font-weight: 900;
    color: #b42b6f;
}
.score-label {
    font-size: 18px;
    color: #666;
    margin-top: 8px;
}
.details-box {
    background: linear-gradient(135deg, #fff5f0, #fff);
    border: 2px solid #f5cfd0;
    border-radius: 16px;
    padding: 24px;
    margin: 30px 0;
}
.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #f5cfd0;
}
.detail-row:last-child {
    border-bottom: none;
}
.detail-label {
    font-weight: 700;
    color: #b42b6f;
    font-size: 16px;
}
.detail-value {
    font-size: 20px;
    font-weight: 900;
    color: #f28b6b;
}
.status-badge {
    display: inline-block;
    padding: 12px 24px;
    border-radius: 20px;
    font-weight: 800;
    font-size: 18px;
    margin: 20px 0;
}
.status-approved {
    background: #4CAF50;
    color: #fff;
}
.status-failed {
    background: #f28b6b;
    color: #fff;
}
.message-box {
    background: linear-gradient(135deg, #e6d9ff, #f0e8ff);
    border: 2px solid #9370DB;
    border-radius: 16px;
    padding: 20px;
    margin: 20px 0;
}
.message-box p {
    color: #5a3d9a;
    font-size: 16px;
    line-height: 1.6;
    margin: 0;
}
.btn-group {
    display: flex;
    gap: 12px;
    margin-top: 30px;
}
button {
    flex: 1;
    padding: 16px 24px;
    border: none;
    border-radius: 14px;
    font-weight: 800;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-family: inherit;
    text-transform: uppercase;
}
.btn-primary {
    background: linear-gradient(135deg, #f6a89e 0%, #f28b6b 100%);
    color: #fff;
    box-shadow: 0 8px 20px rgba(242, 139, 107, 0.3);
}
.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 28px rgba(242, 139, 107, 0.4);
}
.btn-secondary {
    background: #e0e0e0;
    color: #666;
}
.btn-secondary:hover {
    background: #d0d0d0;
    transform: translateY(-2px);
}
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <?php if ($aprobado): ?>
                <div class="result-icon">🎉</div>
                <h1>¡Felicitaciones!</h1>
                <p style="font-size: 20px; color: #666; margin-bottom: 20px;">
                    Has aprobado la evaluación
                </p>
                <span class="status-badge status-approved">✓ APROBADO</span>
            <?php else: ?>
                <div class="result-icon"> 😞</div>
                <h1>Sigue Intentando</h1>
                <p style="font-size: 20px; color: #666; margin-bottom: 20px;">
                    No alcanzaste el puntaje mínimo
                </p>
                <span class="status-badge status-failed">x NO APROBADO</span>
            <?php endif; ?>

            <div class="score-circle <?= $aprobado ? 'approved' : 'failed' ?>">
                <div class="score-number"><?= number_format($porcentaje, 1) ?>%</div>
                <div class="score-label">Calificación</div>
            </div>

            <div class="details-box">
                <div class="detail-row">
                    <span class="detail-label">Puntaje Obtenido:</span>
                    <span class="detail-value"><?= $puntaje_total ?> pts</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Puntaje Máximo:</span>
                    <span class="detail-value"><?= $puntaje_max ?> pts</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Porcentaje:</span>
                    <span class="detail-value"><?= number_format($porcentaje, 1) ?>%</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Mínimo para Aprobar:</span>
                    <span class="detail-value">60%</span>
                </div>
            </div>

            <div class="message-box">
                <?php if ($aprobado): ?>
                    <p>
                        <strong>¡Excelente trabajo!</strong> Has demostrado un buen dominio del tema.
                        Sigue así y continúa aprendiendo. Tu esfuerzo y dedicación han dado frutos.
                    </p>
                <?php else: ?>
                    <p>
                        <strong>No te desanimes.</strong> El aprendizaje requiere práctica constante.
                        Revisa los temas nuevamente, estudia con más detalle y vuelve a intentarlo.
                        ¡Puedes lograrlo!
                    </p>
                <?php endif; ?>
            </div>

            <div class="btn-group">
                <?php if ($id_leccion > 0): ?>
                    <form method="POST" action="lecciones_html/leccion-<?= $id_leccion ?>.html" style="flex:1">
                        <button type="button" class="btn-primary" onclick="window.location.href='lecciones_html/leccion-<?= $id_leccion ?>.html'">
                             Volver a la Lección
                        </button>
                    </form>
                <?php endif; ?>
                <button type="button" class="btn-secondary" onclick="window.print()">
                     Imprimir
                </button>
            </div>
        </div>
    </div>

    <script>
        <?php if ($aprobado): ?>
        setTimeout(() => {
            Swal.fire({
                icon: 'success',
                title: '¡Felicitaciones!',
                text: 'Has aprobado con éxito',
                confirmButtonColor: '#f6a89e',
                timer: 3000,
                showConfirmButton: false
            });
        }, 500);
        <?php endif; ?>
    </script>
</body>
</html>