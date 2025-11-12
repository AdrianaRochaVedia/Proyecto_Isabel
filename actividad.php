           <link rel="stylesheet" href="stylesof.css">

    <div class="navbar">
    <a href="inicio.php">Inicio</a>
        <div class="dropdown">
            <a href="contenido/lecciones.php">Lecciones</a>
            <div class="dropdown-content">
              <a href="contenido/lecciones_html/leccion-1.html">Guerra Federal</a>
                <a href="contenido/lecciones_html/leccion-2.html">Mapa de pérdidas territoriales de Bolivia</a>
                <a href="contenido/lecciones_html/leccion-3.html">Usurpación del Litoral </a>
                <a href="contenido/lecciones_html/leccion-4.html">La Guerra del Chaco</a>
                <a href="contenido/lecciones_html/leccion-5.html">Culturas antes de la colonia en el territorio nacional </a>
            </div>
        </div>
            <a href="actividad.php">Actividades</a>
            <a href="videos.php">Videos</a>
<a href="registro/registro.php">Registrarse</a>


    </div>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actividades</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .navbar{ 
            background:#f0a0a0;

        }
       

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #ffdadaff 0%, #fffafa 50%, #fff8f8 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        header {
            text-align: center;
            padding: 50px 20px;
            margin-bottom: 60px;
            background: linear-gradient(135deg, #ffe8e8 0%, #fff0f0 100%);
            border-radius: 30px;
            box-shadow: 0 15px 40px rgba(255, 200, 200, 0.15);
            border: 2px solid rgba(255, 220, 220, 0.3);
        }

        h1 {
            color: #e88888;
            font-size: 3em;
            margin-bottom: 15px;
            text-shadow: 2px 2px 6px rgba(255, 255, 255, 0.8);
            font-weight: 700;
            letter-spacing: 1px;
        }

        .subtitle {
            color: #f0a0a0;
            font-size: 1.3em;
            font-weight: 300;
            letter-spacing: 0.5px;
        }

        .activities-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 40px;
            margin-bottom: 60px;
            padding: 0 20px;
        }

        .activity-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 8px 25px rgba(255, 150, 150, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
        }

        .activity-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 35px rgba(255, 150, 150, 0.3);
        }

        .activity-header {
            background: linear-gradient(135deg, #ffb3b3 0%, #ffc4c4 100%);
            padding: 15px;
            margin: -20px -20px 20px -20px;
            border-radius: 15px 15px 0 0;
        }

        .activity-title {
            color: #da5a5aff;
            font-size: 1.3em;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .activity-type {
            color: #d36969ff;
            font-size: 0.9em;
            font-weight: 400;
        }

        .iframe-container {
            position: relative;
            width: 100%;
            padding-bottom: 75%;
            border-radius: 10px;
            overflow: hidden;
            background: #ffe4e4;
            box-shadow: inset 0 2px 10px rgba(255, 150, 150, 0.2);
        }

        iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
            border-radius: 10px;
        }

        .activity-description {
            margin-top: 15px;
            color: #666;
            line-height: 1.6;
            font-size: 0.95em;
        }

        .btn-open {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 25px;
            background: linear-gradient(135deg, #ff8a8a 0%, #ffb3b3 100%);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 138, 138, 0.3);
        }

        .btn-open:hover {
            background: linear-gradient(135deg, #f38989ff 0%, #ff9999 100%);
            box-shadow: 0 6px 20px rgba(255, 138, 138, 0.4);
            transform: translateY(-2px);
        }


        @media (max-width: 768px) {
            h1 {
                font-size: 2em;
            }
            
            .activities-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Actividades </h1>
        </header>

        <div class="activities-grid">
            <div class="activity-card">
                <div class="activity-header">
                    <h2 class="activity-title">Guerra Federal</h2>
                    <p class="activity-type"> Sopa de Letras</p>
                </div>
                <div class="iframe-container">
                    <iframe src="https://buscapalabras.com.ar/sopa-de-letras-de-guerra-federal.html" title="Sopa de Letras Guerra Federal"></iframe>
                </div>
                <p class="activity-description">
                    Encuentra palabras clave relacionadas con la Guerra Federal (1898-1899), conflicto que definió el traslado de la capital a La Paz.
                </p>
                <a href="https://buscapalabras.com.ar/sopa-de-letras-de-guerra-federal.html" target="_blank" class="btn-open">Abrir en pantalla completa →</a>
            </div>

            <div class="activity-card">
                <div class="activity-header">
                    <h2 class="activity-title">Pérdidas Territoriales</h2>
                    <p class="activity-type"> Crucigrama</p>
                </div>
                <div class="iframe-container">
                    <iframe src="http://www.educima.com/crosswords/perdida_territoriales_bolivia-8e8cc921c371d4e5345c02c3c2af9e34" title="Crucigrama Pérdidas Territoriales"></iframe>
                </div>
                <p class="activity-description">
                    Aprende sobre las diferentes pérdidas territoriales que Bolivia experimentó a lo largo de su historia mediante este crucigrama educativo.
                </p>
                <a href="http://www.educima.com/crosswords/perdida_territoriales_bolivia-8e8cc921c371d4e5345c02c3c2af9e34" target="_blank" class="btn-open">Abrir en pantalla completa →</a>
            </div>
                        <div class="activity-card">
                <div class="activity-header">
                    <h2 class="activity-title">Guerra del Pacífico</h2>
                    <p class="activity-type">Crucigrama</p>
                </div>
                <div class="iframe-container">
                    <iframe src="https://www.educima.com/crosswords/guerra_del_pacifico-9e46b311f043f0487f67e700f0a66f13" title="Crucigrama Guerra del Pacífico"></iframe>
                </div>
                <p class="activity-description">
                    Descubre los eventos de la Guerra del Pacífico (1879-1884) y la pérdida del litoral boliviano a través de este crucigrama.
                </p>
                <a href="https://www.educima.com/crosswords/guerra_del_pacifico-9e46b311f043f0487f67e700f0a66f13" target="_blank" class="btn-open">Abrir en pantalla completa →</a>
            </div>
            <div class="activity-card">
                <div class="activity-header">
                    <h2 class="activity-title">La Guerra del Chaco</h2>
                    <p class="activity-type"> Crucigrama</p>
                </div>
                <div class="iframe-container">
                    <iframe src="https://www.educima.com/crosswords/la_guerra_del_chaco-5bf4e20ae6fc8148bf33dee5d89b62bc" title="Crucigrama Guerra del Chaco"></iframe>
                </div>
                <p class="activity-description">
                    Completa el crucigrama sobre la Guerra del Chaco (1932-1935), uno de los conflictos más importantes de la historia boliviana.
                </p>
                <a href="https://www.educima.com/crosswords/la_guerra_del_chaco-5bf4e20ae6fc8148bf33dee5d89b62bc" target="_blank" class="btn-open">Abrir en pantalla completa →</a>
            </div>

        </div>

</body>
</html>