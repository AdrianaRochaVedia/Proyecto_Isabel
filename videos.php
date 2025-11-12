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
    <title>Videos Educativos - Historia de Bolivia</title>
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
            background: linear-gradient(135deg, #fff5f5 0%, #fffafa 50%, #fff8f8 100%);
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

        .videos-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 40px;
            margin-bottom: 60px;
            padding: 0 20px;
        }

        .video-card {
            background: #ffffff;
            border-radius: 25px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(255, 200, 200, 0.12);
            transition: all 0.4s ease;
            overflow: hidden;
            border: 2px solid rgba(255, 230, 230, 0.5);
            position: relative;
        }

        .video-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #ffe0e0 0%, #ffd5d5 50%, #ffe0e0 100%);
            border-radius: 25px 25px 0 0;
        }

        .video-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 50px rgba(255, 200, 200, 0.2);
            border-color: rgba(255, 210, 210, 0.7);
        }

        .video-header {
            background: linear-gradient(135deg, #fff0f0 0%, #ffe8e8 100%);
            padding: 20px;
            margin: -30px -30px 25px -30px;
            border-radius: 25px 25px 0 0;
            border-bottom: 2px solid rgba(255, 230, 230, 0.5);
            padding-top: 26px;
        }

        .video-title {
            color: #e88888;
            font-size: 1.4em;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }

        .video-type {
            color: #f0a0a0;
            font-size: 1em;
            font-weight: 500;
        }

        .iframe-container {
            position: relative;
            width: 100%;
            padding-bottom: 56.25%; /* 16:9 aspect ratio */
            border-radius: 15px;
            overflow: hidden;
            background: #fffafa;
            box-shadow: inset 0 2px 15px rgba(255, 200, 200, 0.08);
            border: 1px solid rgba(255, 230, 230, 0.5);
        }

        iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
            border-radius: 15px;
        }

        .video-description {
            margin-top: 20px;
            color: #b08888;
            line-height: 1.8;
            font-size: 1em;
        }

        .btn-open {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 30px;
            background: linear-gradient(135deg, #ffd5d5 0%, #ffe0e0 100%);
            color: #d88888;
            text-decoration: none;
            border-radius: 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(255, 200, 200, 0.2);
            border: 2px solid rgba(255, 220, 220, 0.4);
        }

        .btn-open:hover {
            background: linear-gradient(135deg, #ffc8c8 0%, #ffd5d5 100%);
            box-shadow: 0 8px 25px rgba(255, 200, 200, 0.3);
            transform: translateY(-2px);
            border-color: rgba(255, 200, 200, 0.6);
        }


        @media (max-width: 1024px) {
            .videos-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .video-card {
                padding: 25px;
            }

            .video-header {
                margin: -25px -25px 20px -25px;
            }
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 2.2em;
            }

            .videos-grid {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1> Videos </h1>
        </header>

        <div class="videos-grid">
            <div class="video-card">
                <div class="video-header">
                    <h2 class="video-title">Guerra Federal Boliviana</h2>
                    <p class="video-type"> Video Histórico</p>
                </div>
                <div class="iframe-container">
                    <iframe src="https://www.youtube.com/embed/4uOGqtWVbVI" title="Batallas de la Guerra Federal boliviana" allowfullscreen></iframe>
                </div>
                <p class="video-description">
                    Descubre las batallas más importantes de la Guerra Federal boliviana (1898-1899), conflicto que definió el traslado de la sede de gobierno a La Paz.
                </p>
                <a href="https://www.youtube.com/watch?v=4uOGqtWVbVI" target="_blank" class="btn-open">Ver en YouTube →</a>
            </div>

            <div class="video-card">
                <div class="video-header">
                    <h2 class="video-title">Pérdidas Territoriales</h2>
                    <p class="video-type"> Video Histórico</p>
                </div>
                <div class="iframe-container">
                    <iframe src="https://www.youtube.com/embed/AVS8hwYDYJI" title="Pérdidas Territoriales en Bolivia" allowfullscreen></iframe>
                </div>
                <p class="video-description">
                    Conoce la historia de las pérdidas territoriales de Bolivia a lo largo de su historia, desde la independencia hasta el siglo XX.
                </p>
                <a href="https://www.youtube.com/watch?v=AVS8hwYDYJI" target="_blank" class="btn-open">Ver en YouTube →</a>
            </div>

            <div class="video-card">
                <div class="video-header">
                    <h2 class="video-title">Usurpación del Litoral</h2>
                    <p class="video-type"> Video Educativo</p>
                </div>
                <div class="iframe-container">
                    <iframe src="https://www.facebook.com/plugins/video.php?height=314&href=https%3A%2F%2Fwww.facebook.com%2FClases.Virtuales.Creativas%2Fvideos%2F1366510967145905%2F&show_text=false&width=560&t=0" title="Día del Mar" allowfullscreen></iframe>
                </div>
                <p class="video-description">
                    Conmemoración del Día del Mar, recordando la pérdida del litoral boliviano tras la Guerra del Pacífico (1879-1884).
                </p>
                <a href="https://www.facebook.com/Clases.Virtuales.Creativas/videos/1366510967145905/" target="_blank" class="btn-open">Ver en Facebook →</a>
            </div>

            <div class="video-card">
                <div class="video-header">
                    <h2 class="video-title">Guerra del Chaco</h2>
                    <p class="video-type"> Video Histórico</p>
                </div>
                <div class="iframe-container">
                    <iframe src="https://www.youtube.com/embed/9ud1eXOC9_M" title="Usurpación del Chaco en Bolivia" allowfullscreen></iframe>
                </div>
                <p class="video-description">
                    La Guerra del Chaco (1932-1935) entre Bolivia y Paraguay, uno de los conflictos más devastadores de la historia sudamericana.
                </p>
                <a href="https://www.youtube.com/watch?v=9ud1eXOC9_M" target="_blank" class="btn-open">Ver en YouTube →</a>
            </div>

            <div class="video-card">
                <div class="video-header">
                    <h2 class="video-title">Culturas Precolombinas</h2>
                    <p class="video-type"> Video Cultural</p>
                </div>
                <div class="iframe-container">
                    <iframe src="https://www.youtube.com/embed/e4aP7n3_D10" title="Culturas antes de la Colonia en Bolivia" allowfullscreen></iframe>
                </div>
                <p class="video-description">
                    Explora las grandes culturas que habitaron el territorio boliviano antes de la llegada de los españoles: Tiwanaku, Incas y otras civilizaciones.
                </p>
                <a href="https://www.youtube.com/watch?v=e4aP7n3_D10" target="_blank" class="btn-open">Ver en YouTube →</a>
            </div>
        </div>

    </div>
</body>
</html>