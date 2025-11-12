<?php
session_start();
if (empty($_SESSION['id']) || empty($_SESSION['tipo'])) {
    header("Location: /login/login.php");
    exit();
}

$cn = mysqli_connect("localhost","root","","SISSOCIALES");
if(!$cn){ die("Error BD: " . mysqli_connect_error()); }
mysqli_set_charset($cn,"utf8mb4");

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id_leccion'])) {
    die("<!doctype html><html><head><meta charset='utf-8'><title>Acceso Denegado</title><style>body{font-family:system-ui;display:flex;align-items:center;justify-content:center;height:100vh;margin:0;background:linear-gradient(135deg,#ffd7cc,#fde2dc);color:#b42b6f}</style></head><body><div style='text-align:center'><h1>🚫 Acceso Incorrecto</h1><p>Usa el botón desde el último subtema.</p></div></body></html>");
}

$id_leccion = (int)$_POST['id_leccion'];

if (isset($_POST['enviar_evaluacion'])) {
    $id_evaluacion = (int)($_POST['id_evaluacion'] ?? 0);
    $respuestas = $_POST['respuestas'] ?? [];
    
    $puntaje_total = 0;
    
    foreach ($respuestas as $id_pregunta => $opciones_seleccionadas) {
        if (is_array($opciones_seleccionadas)) {
            foreach ($opciones_seleccionadas as $id_opcion) {
                $res = mysqli_query($cn, "SELECT puntaje FROM OPCION WHERE id_opcion = $id_opcion AND es_correcta = 1");
                if ($row = mysqli_fetch_assoc($res)) {
                    $puntaje_total += (int)$row['puntaje'];
                }
            }
        }
    }

    $preguntas = mysqli_query($cn, "SELECT puntaje FROM PREGUNTA WHERE EVALUACION_id_evaluacion = $id_evaluacion");
    $puntaje_max = 0;
    while ($p = mysqli_fetch_assoc($preguntas)) {
        $puntaje_max += (int)$p['puntaje'];
    }
    
    $aprobado = ($puntaje_max > 0 && ($puntaje_total / $puntaje_max) >= 0.6) ? 1 : 0;

    $id_usuario = (int)$_SESSION['id'];
    $inicio = date('Y-m-d H:i:s', strtotime('-' . rand(5, 20) . ' minutes'));
    $fin = date('Y-m-d H:i:s');
    
    $stmt = mysqli_prepare($cn, "INSERT INTO INTENTO (inicio, fin, puntaje_total, aprobado, USUARIO_id_usuario, EVALUACION_id_evaluacion) VALUES (?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssiiii", $inicio, $fin, $puntaje_total, $aprobado, $id_usuario, $id_evaluacion);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    

    $porcentaje = ($puntaje_max > 0) ? round(($puntaje_total / $puntaje_max) * 100, 2) : 0;
    ?>
    <!DOCTYPE html>
    <html><head><meta charset="UTF-8"></head><body>
        <form id="resultForm" method="POST" action="resultado_evaluacion.php">
            <input type="hidden" name="puntaje_total" value="<?= $puntaje_total ?>">
            <input type="hidden" name="puntaje_max" value="<?= $puntaje_max ?>">
            <input type="hidden" name="aprobado" value="<?= $aprobado ?>">
            <input type="hidden" name="porcentaje" value="<?= $porcentaje ?>">
            <input type="hidden" name="id_leccion" value="<?= $id_leccion ?>">
        </form>
        <script>document.getElementById('resultForm').submit();</script>
    </body></html>
    <?php
    exit;
}


$evaluacion = mysqli_fetch_assoc(mysqli_query($cn, "SELECT * FROM EVALUACION WHERE LECCION_id_leccion = $id_leccion AND habilitada = 1 LIMIT 1"));

if (!$evaluacion) {
    echo "<!doctype html><html><head><meta charset='utf-8'><script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script></head><body><script>
    Swal.fire({
      icon: 'info',
      title: 'Sin Evaluación',
      text: 'Esta lección no tiene evaluaciones disponibles todavía',
      confirmButtonColor: '#f6a89e'
    }).then(() => {
      window.location.href = 'lecciones_html/leccion-$id_leccion.html';
    });
    </script></body></html>";
    exit;
}

$id_evaluacion = (int)$evaluacion['id_evaluacion'];
$preguntas = mysqli_query($cn, "SELECT * FROM PREGUNTA WHERE EVALUACION_id_evaluacion = $id_evaluacion ORDER BY id_pregunta");
$titulo_evaluacion = htmlspecialchars($evaluacion['titulo']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo_evaluacion ?></title>
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
    padding: 20px;
}
.container {
    max-width: 900px;
    margin: 0 auto;
}
h1 {
    color: #b42b6f;
    font-size: 32px;
    font-weight: 900;
    margin-bottom: 20px;
    text-align: center;
    text-shadow: 2px 2px 4px rgba(180, 43, 111, 0.1);
}
.card {
    background: #fff;
    border: 2px solid #f5cfd0;
    border-radius: 24px;
    padding: 32px;
    margin-bottom: 20px;
    box-shadow: 0 20px 60px rgba(242, 139, 107, 0.2);
}
.info-box {
    background: linear-gradient(135deg, #e6d9ff, #f0e8ff);
    border: 2px solid #9370DB;
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 20px;
}
.info-box p {
    margin: 8px 0;
    color: #5a3d9a;
    font-weight: 600;
}
.question-card {
    background: linear-gradient(135deg, #fff5f0, #fff);
    border: 2px solid #f5cfd0;
    border-radius: 16px;
    padding: 20px;
    margin: 20px 0;
}
.question-number {
    background: #f6a89e;
    color: #fff;
    padding: 6px 14px;
    border-radius: 20px;
    font-weight: 700;
    font-size: 14px;
    display: inline-block;
    margin-bottom: 12px;
}
h3 {
    color: #b42b6f;
    margin: 10px 0;
    font-size: 20px;
}
.puntaje-badge {
    background: #fff3e0;
    color: #f57c00;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 700;
    display: inline-block;
    margin: 10px 0;
}
.option-item {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 12px 0;
    padding: 14px;
    background: #fff;
    border: 2px solid #f5cfd0;
    border-radius: 12px;
    transition: all 0.3s;
    cursor: pointer;
}
.option-item:hover {
    border-color: #f6a89e;
    box-shadow: 0 4px 12px rgba(246, 168, 158, 0.2);
    transform: translateY(-2px);
}
.option-item input[type="radio"],
.option-item input[type="checkbox"] {
    width: 20px;
    height: 20px;
    cursor: pointer;
}
.option-item label {
    flex: 1;
    cursor: pointer;
    color: #333;
    font-size: 16px;
}
.btn-group {
    display: flex;
    gap: 12px;
    margin-top: 30px;
}
button {
    padding: 16px 32px;
    border: none;
    border-radius: 14px;
    font-weight: 800;
    font-size: 17px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-family: inherit;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.btn-primary {
    background: linear-gradient(135deg, #f6a89e 0%, #f28b6b 100%);
    color: #fff;
    box-shadow: 0 8px 20px rgba(242, 139, 107, 0.3);
    flex: 1;
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
        <h1> <?= $titulo_evaluacion ?></h1>
        
        <div class="card">
            <div class="info-box">
                <p><strong>⏱️Tiempo límite:</strong> <?= htmlspecialchars($evaluacion['tiempo_limite']) ?></p>
                <p><strong>Total de preguntas:</strong> <?= mysqli_num_rows($preguntas) ?></p>
                <p><strong>Puntaje para aprobar:</strong> 60%</p>
            </div>
            
            <form method="POST" id="formEvaluacion">
                <input type="hidden" name="enviar_evaluacion" value="1">
                <input type="hidden" name="id_evaluacion" value="<?= $id_evaluacion ?>">
                <input type="hidden" name="id_leccion" value="<?= $id_leccion ?>">
                
                <?php 
                mysqli_data_seek($preguntas, 0);
                $index = 0;
                while ($pregunta = mysqli_fetch_assoc($preguntas)): 
                    $index++;
                    $id_pregunta = (int)$pregunta['id_pregunta'];
                    $opciones = mysqli_query($cn, "SELECT * FROM OPCION WHERE PREGUNTA_id_pregunta = $id_pregunta ORDER BY id_opcion");
                    $input_type = ($pregunta['tipo_enum'] === 'multiple') ? 'checkbox' : 'radio';
                ?>
                    <div class="question-card">
                        <span class="question-number">Pregunta <?= $index ?></span>
                        
                        <h3><?= htmlspecialchars($pregunta['enunciado']) ?></h3>
                        
                        <span class="puntaje-badge">⭐ <?= $pregunta['puntaje'] ?> puntos</span>
                        
                        <?php while ($opcion = mysqli_fetch_assoc($opciones)): ?>
                            <div class="option-item" onclick="toggleOption(this)">
                                <input type="<?= $input_type ?>" 
                                       name="respuestas[<?= $id_pregunta ?>][]" 
                                       value="<?= $opcion['id_opcion'] ?>"
                                       id="opcion-<?= $opcion['id_opcion'] ?>">
                                <label for="opcion-<?= $opcion['id_opcion'] ?>">
                                    <?= htmlspecialchars($opcion['texto']) ?>
                                </label>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php endwhile; ?>

                <div class="btn-group">
                    <button type="button" class="btn-primary" onclick="confirmarEnvio()">
                        ✓ Enviar Evaluación
                    </button>
                    <button type="button" class="btn-secondary" onclick="confirmarCancelar()">
                        x Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleOption(element) {
            const input = element.querySelector('input[type="radio"], input[type="checkbox"]');
            if (input.type === 'radio') {
                input.checked = true;
            } else {
                input.checked = !input.checked;
            }
        }

        function confirmarEnvio() {
            Swal.fire({
                title: ' Enviar Evaluación',
                text: '¿Estás seguro? No podrás modificar tus respuestas',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#f6a89e',
                cancelButtonColor: '#ddd',
                confirmButtonText: 'Sí, enviar',
                cancelButtonText: 'Revisar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Enviando...',
                        text: 'Procesando tu evaluación',
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    document.getElementById('formEvaluacion').submit();
                }
            });
        }

        function confirmarCancelar() {
            Swal.fire({
                title: '¿Cancelar?',
                text: 'Se perderá tu progreso en esta evaluación',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f6a89e',
                cancelButtonColor: '#ddd',
                confirmButtonText: 'Sí, salir',
                cancelButtonText: 'Continuar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'lecciones_html/leccion-<?= $id_leccion ?>.html';
                }
            });
        }
    </script>
</body>
</html>