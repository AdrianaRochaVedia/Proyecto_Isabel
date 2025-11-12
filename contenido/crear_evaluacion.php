<?php
session_start();
if (empty($_SESSION['id']) || empty($_SESSION['tipo'])) {
    header("Location: /login/login.php");
    exit();
}

if (empty($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    die("<!doctype html><html><head><meta charset='utf-8'><title>Acceso Denegado</title><style>body{font-family:system-ui;display:flex;align-items:center;justify-content:center;height:100vh;margin:0;background:linear-gradient(135deg,#ffd7cc,#fde2dc);color:#b42b6f}</style></head><body><div style='text-align:center'><h1>🚫 Acceso Denegado</h1><p>No tienes permisos para crear evaluaciones.</p></div></body></html>");
}

$cn = mysqli_connect("localhost","root","","SISSOCIALES");
if(!$cn){ die("Error BD: " . mysqli_connect_error()); }
mysqli_set_charset($cn,"utf8mb4");

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id_leccion'])) {
    die("<!doctype html><html><head><meta charset='utf-8'><title>Acceso Denegado</title><style>body{font-family:system-ui;display:flex;align-items:center;justify-content:center;height:100vh;margin:0;background:linear-gradient(135deg,#ffd7cc,#fde2dc);color:#b42b6f}</style></head><body><div style='text-align:center'><h1>🚫 Acceso Incorrecto</h1><p>Usa el botón de crear evaluación desde la lección.</p></div></body></html>");
}

$id_leccion = (int)$_POST['id_leccion'];

if ($id_leccion <= 0) {
    die("ID de lección inválido");
}

$leccion = mysqli_fetch_assoc(mysqli_query($cn, "SELECT * FROM LECCION WHERE id_leccion = $id_leccion"));

if (!$leccion) {
    die("Lección no encontrada");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'guardar_evaluacion') {
    try {
        mysqli_begin_transaction($cn);
        
        $titulo = trim($_POST['titulo'] ?? '');
        $tiempo_limite = trim($_POST['tiempo_limite'] ?? '00:20') . ':00';
        $intentos_max = (int)($_POST['intentos_max'] ?? 2);
        $habilitada = (int)($_POST['habilitada'] ?? 1);
        
        if (empty($titulo) || $intentos_max <= 0) {
            throw new Exception('Datos de evaluación inválidos');
        }

        $stmt = mysqli_prepare($cn, "INSERT INTO EVALUACION (titulo, tiempo_limite, intentos_max, habilitada, LECCION_id_leccion) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssiii", $titulo, $tiempo_limite, $intentos_max, $habilitada, $id_leccion);
        mysqli_stmt_execute($stmt);
        $id_evaluacion = mysqli_insert_id($cn);
        mysqli_stmt_close($stmt);
        
        if (!$id_evaluacion) {
            throw new Exception('Error al crear la evaluación');
        }

        if (isset($_POST['preguntas']) && is_array($_POST['preguntas'])) {
            foreach ($_POST['preguntas'] as $pregunta_data) {
                $enunciado = trim($pregunta_data['enunciado'] ?? '');
                $puntaje = (int)($pregunta_data['puntaje'] ?? 5);
                $tipo = trim($pregunta_data['tipo'] ?? 'simple');
                
                if (empty($enunciado) || $puntaje <= 0) {
                    continue;
                }

                $stmt_preg = mysqli_prepare($cn, "INSERT INTO PREGUNTA (enunciado, puntaje, tipo_enum, EVALUACION_id_evaluacion) VALUES (?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt_preg, "sisi", $enunciado, $puntaje, $tipo, $id_evaluacion);
                mysqli_stmt_execute($stmt_preg);
                $id_pregunta = mysqli_insert_id($cn);
                mysqli_stmt_close($stmt_preg);
                
                if (!$id_pregunta) {
                    throw new Exception('Error al crear pregunta');
                }
                

if (isset($pregunta_data['opciones']) && is_array($pregunta_data['opciones'])) {
    foreach ($pregunta_data['opciones'] as $opcion_data) {
        $texto_opcion = isset($opcion_data['texto']) ? trim($opcion_data['texto']) : '';
        $puntaje_opcion = isset($opcion_data['puntaje']) ? (int)$opcion_data['puntaje'] : 5;
        $es_correcta = isset($opcion_data['correcta']) ? 1 : 0;

        if ($texto_opcion === '' || $texto_opcion === null) {
            continue;
        }

        $stmt_opc = mysqli_prepare(
            $cn,
            "INSERT INTO OPCION (texto, es_correcta, puntaje, PREGUNTA_id_pregunta) VALUES (?, ?, ?, ?)"
        );

        if (!$stmt_opc) {
            throw new Exception("Error al preparar OPCION: " . mysqli_error($cn));
        }

        mysqli_stmt_bind_param($stmt_opc, "siii", $texto_opcion, $es_correcta, $puntaje_opcion, $id_pregunta);

        if (!mysqli_stmt_execute($stmt_opc)) {
            throw new Exception("Error al insertar opción: " . mysqli_stmt_error($stmt_opc));
        }

        mysqli_stmt_close($stmt_opc);
    }
}


            }
        }

        mysqli_commit($cn);
        
        echo "<!doctype html><html><head><meta charset='utf-8'><script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script></head><body><script>
        Swal.fire({
          icon: 'success',
          title: '✓ ¡Evaluación Creada!',
          text: 'La evaluación se ha guardado correctamente',
          confirmButtonColor: '#f6a89e',
          timer: 2000,
          showConfirmButton: false
        }).then(() => {
          window.location.href = 'lecciones_html/leccion-$id_leccion.html';
        });
        </script></body></html>";
        exit;
        
    } catch (Exception $e) {
        mysqli_rollback($cn);
        echo "<!doctype html><html><head><meta charset='utf-8'><script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script></head><body><script>
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: '" . addslashes($e->getMessage()) . "',
          confirmButtonColor: '#f6a89e'
        }).then(() => {
          window.history.back();
        });
        </script></body></html>";
        exit;
    }
}

$tituloLeccion = htmlspecialchars($leccion['titulo']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Evaluación</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}
body {
    font-family: 'Segoe UI', Arial, sans-serif;
    background: linear-gradient(135deg, #ffd7cc 0%, #fde2dc 50%, #ffe7e2 100%);
    min-height: 100vh;
    padding: 20px;
}
.container {
    max-width: 900px;
    margin: 0 auto;
    animation: fadeIn 0.6s ease-out;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
.header-badge {
    background: linear-gradient(135deg, #f6a89e, #f28b6b);
    color: #fff;
    padding: 12px 24px;
    border-radius: 20px;
    font-size: 18px;
    font-weight: 700;
    display: inline-block;
    margin-bottom: 20px;
    box-shadow: 0 4px 12px rgba(242, 139, 107, 0.3);
}
.card {
    background: #fff;
    border: 2px solid #f5cfd0;
    border-radius: 24px;
    padding: 32px;
    margin-bottom: 20px;
    box-shadow: 0 20px 60px rgba(242, 139, 107, 0.2);
}
h1 {
    color: #b42b6f;
    font-size: 32px;
    font-weight: 900;
    margin-bottom: 12px;
    text-shadow: 2px 2px 4px rgba(180, 43, 111, 0.1);
}
h2 {
    color: #b42b6f;
    font-size: 24px;
    margin-bottom: 16px;
}
h3 {
    color: #b42b6f;
    font-size: 18px;
    margin-top: 20px;
}
.form-group {
    margin-bottom: 20px;
}
label {
    display: block;
    font-weight: 700;
    color: #b42b6f;
    margin-bottom: 8px;
    font-size: 15px;
}
input[type="text"],
input[type="time"],
input[type="number"],
select,
textarea {
    width: 100%;
    padding: 14px;
    border: 2px solid #f5cfd0;
    border-radius: 12px;
    font-size: 15px;
    font-family: inherit;
    transition: all 0.3s ease;
    background: #fafafa;
}
input:focus,
select:focus,
textarea:focus {
    outline: none;
    border-color: #f6a89e;
    background: #fff;
    box-shadow: 0 0 0 4px rgba(246, 168, 158, 0.1);
}
textarea {
    resize: vertical;
    min-height: 100px;
}
.grid-2 {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}
.question-card {
    background: linear-gradient(135deg, #fff5f0, #fff);
    border: 2px solid #f5cfd0;
    border-radius: 16px;
    padding: 20px;
    margin: 20px 0;
    position: relative;
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
.option-item {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 12px;
    padding: 10px;
    background: #fff;
    border: 2px solid #f5cfd0;
    border-radius: 10px;
    transition: all 0.3s;
}
.option-item:hover {
    border-color: #f6a89e;
    box-shadow: 0 4px 12px rgba(246, 168, 158, 0.2);
}
.btn-group {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-top: 20px;
}
button,
.btn {
    padding: 14px 24px;
    border: none;
    border-radius: 14px;
    font-weight: 800;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-family: inherit;
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
    color: #666666ff;
}
.btn-secondary:hover {
    background: #d0d0d0;
    transform: translateY(-2px);
}
.btn-danger {
    background: #f28b6b;
    color: #fff;
    font-size: 14px;
    padding: 8px 16px;
}
.btn-small {
    padding: 8px 16px;
    font-size: 14px;
}
.divider {
    height: 1px;
    background: linear-gradient(to right, transparent, #f5cfd0, transparent);
    margin: 24px 0;
}
.alert {
    padding: 16px 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    font-weight: 600;
}
.alert-info {
    background: #ffd9e9ff;
    color: #e483a8ff;
    border: 2px solid #a84264ff;
}
    </style>
</head>
<body>
    <div class="container">
        <div class="header-badge"> <?= $tituloLeccion ?></div>
        
        <h1> Crear Nueva Evaluación</h1>
        
        <form method="POST" id="formEvaluacion">
            <input type="hidden" name="accion" value="guardar_evaluacion">
            <input type="hidden" name="id_leccion" value="<?= $id_leccion ?>">
            
            <div class="card">
                <h2>Datos de la Evaluación</h2>
                
                <div class="form-group">
                    <label>Título de la Evaluación *</label>
                    <input type="text" name="titulo" placeholder="Ej: Evaluación de Historia de Bolivia" required>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label>Tiempo Límite (HH:MM) *</label>
                        <input type="time" name="tiempo_limite" value="00:20" required>
                    </div>

                    <div class="form-group">
                        <label>Intentos Máximos *</label>
                        <input type="number" name="intentos_max" min="1" max="10" value="2" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Estado</label>
                    <select name="habilitada">
                        <option value="1">Habilitada</option>
                        <option value="0">Deshabilitada</option>
                    </select>
                </div>
            </div>

            <div class="card">
                <h2>Preguntas de la Evaluación</h2>
                <div class="alert alert-info">
                     Agrega las preguntas y sus opciones. Marca las correctas con el checkbox.
                </div>
                
                <div id="preguntas-container"></div>

                <button type="button" class="btn btn-secondary" onclick="agregarPregunta()">
                    + Agregar Pregunta
                </button>

                <div class="btn-group">
                    <button type="button" class="btn btn-primary" onclick="confirmarGuardar()">
                         Guardar Evaluación
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="confirmarCancelar()">
                        x Cancelar
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        let numeroPregunta = 0;

        function agregarPregunta() {
            numeroPregunta++;
            const container = document.getElementById('preguntas-container');
            
            const preguntaDiv = document.createElement('div');
            preguntaDiv.className = 'question-card';
            preguntaDiv.id = `pregunta-${numeroPregunta}`;
            
            preguntaDiv.innerHTML = `
                <span class="question-number">Pregunta ${numeroPregunta}</span>
                
                <div class="form-group">
                    <label>Enunciado de la Pregunta *</label>
                    <textarea name="preguntas[${numeroPregunta}][enunciado]" required 
                              placeholder="¿Cuándo ocurrió la Guerra Federal?"></textarea>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label>Puntaje *</label>
                        <input type="number" name="preguntas[${numeroPregunta}][puntaje]" 
                               min="1" max="100" value="5" required>
                    </div>

                    <div class="form-group">
                        <label>Tipo</label>
                        <select name="preguntas[${numeroPregunta}][tipo]">
                            <option value="simple">Selección Simple</option>
                            <option value="multiple">Selección Múltiple</option>
                        </select>
                    </div>
                </div>

                <h3>Opciones de Respuesta</h3>
                <div id="opciones-${numeroPregunta}">
                    ${generarOpcion(numeroPregunta, 1)}
                    ${generarOpcion(numeroPregunta, 2)}
                    ${generarOpcion(numeroPregunta, 3)}
                    ${generarOpcion(numeroPregunta, 4)}
                </div>

                <div class="btn-group">
                    <button type="button" class="btn btn-small btn-secondary" 
                            onclick="agregarOpcion(${numeroPregunta})">
                        + Opción
                    </button>
                    
                    <button type="button" class="btn btn-small btn-danger" 
                            onclick="eliminarPregunta(${numeroPregunta})">
                         Eliminar Pregunta
                    </button>
                </div>
            `;
            
            container.appendChild(preguntaDiv);
        }

        function generarOpcion(numPregunta, numOpcion) {
            return `
                <div class="option-item" id="opcion-${numPregunta}-${numOpcion}">
                    <input type="checkbox" 
                           name="preguntas[${numPregunta}][opciones][${numOpcion}][correcta]" 
                           value="1">
                    
                    <input type="text" 
                           name="preguntas[${numPregunta}][opciones][${numOpcion}][texto]" 
                           placeholder="Opción ${numOpcion}" 
                           style="flex: 1;" required>
                    
                    <input type="number" 
                           name="preguntas[${numPregunta}][opciones][${numOpcion}][puntaje]" 
                           value="5" min="0" max="100" 
                           style="width: 80px;">
                    
                    ${numOpcion > 2 ? `
                    <button type="button" class="btn btn-small btn-danger" 
                            onclick="eliminarOpcion(${numPregunta}, ${numOpcion})">
                        X
                    </button>
                    ` : '<span style="width: 60px;"></span>'}
                </div>
            `;
        }

        let contadorOpciones = {};

        function agregarOpcion(numPregunta) {
            if (!contadorOpciones[numPregunta]) {
                contadorOpciones[numPregunta] = 4;
            }
            contadorOpciones[numPregunta]++;
            
            const container = document.getElementById(`opciones-${numPregunta}`);
            const div = document.createElement('div');
            div.innerHTML = generarOpcion(numPregunta, contadorOpciones[numPregunta]);
            container.appendChild(div.firstElementChild);
        }

        function eliminarOpcion(numPregunta, numOpcion) {
            const opcion = document.getElementById(`opcion-${numPregunta}-${numOpcion}`);
            if (opcion) {
                opcion.remove();
            }
        }

        function eliminarPregunta(numPregunta) {
            Swal.fire({
                title: '¿Eliminar pregunta?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f6a89e',
                cancelButtonColor: '#ddd',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    const pregunta = document.getElementById(`pregunta-${numPregunta}`);
                    if (pregunta) {
                        pregunta.remove();
                    }
                }
            });
        }

        function confirmarGuardar() {
            const form = document.getElementById('formEvaluacion');
            
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            const preguntas = document.querySelectorAll('.question-card');
            
            if (preguntas.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Falta información',
                    text: 'Debes agregar al menos una pregunta',
                    confirmButtonColor: '#f6a89e'
                });
                return;
            }

            let valido = true;
            preguntas.forEach((pregunta, index) => {
                const checkboxes = pregunta.querySelectorAll('input[type="checkbox"]:checked');
                if (checkboxes.length === 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Falta información',
                        text: `La pregunta ${index + 1} debe tener al menos una opción correcta`,
                        confirmButtonColor: '#f6a89e'
                    });
                    valido = false;
                    return false;
                }
            });

            if (!valido) return;

            Swal.fire({
                title: '💾 Guardar Evaluación',
                text: '¿Deseas guardar esta evaluación?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#f6a89e',
                cancelButtonColor: '#ddd',
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Guardando...',
                        text: 'Creando la evaluación',
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    form.submit();
                }
            });
        }

        function confirmarCancelar() {
            Swal.fire({
                title: '¿Cancelar?',
                text: 'Se perderán todos los cambios',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f6a89e',
                cancelButtonColor: '#ddd',
                confirmButtonText: 'Sí, cancelar',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'lecciones_html/leccion-<?= $id_leccion ?>.html';
                }
            });
        }

        window.addEventListener('load', function() {
            agregarPregunta();
        });
    </script>
</body>
</html>