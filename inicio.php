
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ciencias Sociales</title>
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
            background: linear-gradient(135deg, #fff5f5 0%, #fffafa 100%);
            overflow-x: hidden;
        }

        .hero {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(255, 240, 240, 0.95), rgba(255, 235, 235, 0.9)), 
                        url('h.png') center/cover;
            position: relative;
            text-align: center;
        }

        .hero-content {
            z-index: 2;
            animation: fadeInUp 1s ease;
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

        .hero-content h1 {
            font-size: 4.5em;
            color: #e88888;
            margin-bottom: 20px;
            font-weight: 800;
            letter-spacing: 2px;
            text-shadow: 3px 3px 6px rgba(255, 255, 255, 0.8);
        }

        .hero-content p {
            font-size: 1.4em;
            color: #b08888;
            margin-bottom: 40px;
        }

        .btn {
            display: inline-block;
            padding: 18px 50px;
            background: linear-gradient(135deg, #ffd5d5 0%, #ffe0e0 100%);
            color: #d88888;
            text-decoration: none;
            border-radius: 35px;
            font-weight: 700;
            font-size: 1.2em;
            box-shadow: 0 10px 30px rgba(255, 200, 200, 0.3);
            transition: all 0.3s ease;
            border: 2px solid rgba(255, 220, 220, 0.5);
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(255, 200, 200, 0.4);
            background: linear-gradient(135deg, #ffc8c8 0%, #ffd5d5 100%);
        }

        /* Info Section */
        .info {
            padding: 100px 50px;
            background: linear-gradient(135deg, #fffafa 0%, #fff5f5 100%);
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 50px;
            margin-top: 60px;
        }

        .card {
            background: #ffffff;
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 15px 50px rgba(255, 200, 200, 0.15);
            border: 2px solid rgba(255, 230, 230, 0.5);
            transition: all 0.4s ease;
            opacity: 0;
            transform: translateY(50px);
        }

        .card.reveal {
            animation: revealCard 0.6s ease forwards;
        }

        @keyframes revealCard {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 60px rgba(255, 200, 200, 0.25);
        }

        .card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-bottom: 3px solid rgba(255, 230, 230, 0.5);
        }

        .card h3 {
            color: #e88888;
            font-size: 1.6em;
            font-weight: 700;
            margin: 25px 25px 15px;
        }

        .card p {
            color: #b08888;
            font-size: 1.1em;
            line-height: 1.8;
            padding: 0 25px 30px;
        }

        /* Parallax Section */
        .parallax {
            height: 400px;
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .parallax::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255, 235, 235, 0.8), rgba(255, 240, 240, 0.7));
        }

        /* Carousel Section */
        .carousel-section {
            padding: 100px 50px;
            background: linear-gradient(135deg, #fff5f5 0%, #fffafa 100%);
        }

        .carousel-section h2 {
            text-align: center;
            color: #e88888;
            font-size: 3em;
            font-weight: 700;
            margin-bottom: 60px;
        }

        .carousel-container {
            position: relative;
            max-width: 1200px;
            margin: 0 auto;
            overflow: hidden;
            border-radius: 30px;
            box-shadow: 0 20px 60px rgba(255, 200, 200, 0.2);
        }

        .carousel {
            display: flex;
            transition: transform 0.5s ease;
        }

        .carousel-item {
            min-width: 100%;
            position: relative;
        }

        .carousel-item img {
            width: 100%;
            height: 500px;
            object-fit: cover;
        }

        .carousel-caption {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(232, 136, 136, 0.95), transparent);
            color: white;
            padding: 40px;
            text-align: center;
        }

        .carousel-caption h3 {
            font-size: 2em;
            margin-bottom: 10px;
        }

        .carousel-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 100%;
            display: flex;
            justify-content: space-between;
            padding: 0 20px;
            pointer-events: none;
        }

        .carousel-btn {
            pointer-events: all;
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(255, 220, 220, 0.5);
            color: #e88888;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 1.5em;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .carousel-btn:hover {
            background: #ffe0e0;
            transform: scale(1.1);
        }

        .carousel-dots {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 30px;
        }

        .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(232, 136, 136, 0.3);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .dot.active {
            background: #e88888;
            transform: scale(1.3);
        }

        /* Footer */
        :root {
            --bg: #fff0f0;
            --bg2: #ffe8e8;
            --txt: #d88888;
            --mut: #b08888;
            --line: rgba(255, 200, 200, 0.3);
            --pink: #e88888;
            --pink2: #ffc8c8;
        }

        footer.min-footer {
            color: var(--txt);
            background: var(--bg);
            margin-top: 80px;
        }

        .min-top {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            border-bottom: 1px solid var(--line);
        }

        .min-box {
            display: flex;
            gap: 14px;
            align-items: center;
            padding: 30px 20px;
            background: var(--bg2);
        }

        .min-box + .min-box {
            border-left: 1px solid var(--line);
        }

        .min-ico {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            color: #e88888;
            background: #fff;
            border: 2px solid rgba(255, 220, 220, 0.5);
        }

        .min-box small {
            color: var(--pink);
            font-weight: 700;
            display: block;
            margin-bottom: 6px;
        }

        .min-box p {
            margin: 0;
            font-weight: 700;
            line-height: 1.3;
            font-size: clamp(16px, 2vw, 20px);
            color: var(--mut);
        }

        .min-mid {
            display: grid;
            grid-template-columns: 1fr;
            justify-items: center;
            gap: 20px;
            padding: 40px;
        }

        .min-logo {
            width: 78px;
            height: 78px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: var(--bg2);
            color: var(--pink);
            font: 900 28px/1 system-ui;
            border: 2px solid rgba(255, 220, 220, 0.5);
        }

        .min-social {
            display: flex;
            gap: 12px;
        }

        .min-social a {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            text-decoration: none;
            color: #e88888;
            background: #fff;
            border: 2px solid rgba(255, 220, 220, 0.5);
            transition: all 0.3s ease;
        }

        .min-social a:hover {
            background: var(--pink2);
            transform: translateY(-3px);
        }

        .min-bottom {
            border-top: 1px solid var(--line);
            text-align: center;
            color: var(--mut);
            padding: 20px;
        }

        .min-bottom b {
            color: var(--pink);
        }

        @media (max-width: 1024px) {
            .cards {
                grid-template-columns: 1fr;
            }

            .min-top {
                grid-template-columns: 1fr;
            }

            .min-box + .min-box {
                border-left: none;
                border-top: 1px solid var(--line);
            }
        }

        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 2.5em;
            }

            .carousel-item img {
                height: 350px;
            }
        }
    </style>
</head>
<body>
    <section class="hero">
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
        <div class="hero-content">
            <br>
            <br>
            <h1>BIENVENIDO</h1>
        </div>
    </section>
    <section class="info">
        <div class="container">
            <div class="cards">
                <div class="card">
                    <img src="pac2.jpg" alt="Contenido" />
                    <h3>Contenido Claro</h3>
                    <p>Podrás observar lecciones claras y dinámicas sobre la historia de Bolivia.</p>
                </div>

                <div class="card">
                    <img src="6d41c541-7834-4b84-9471-1894b7caaad6.jpg" alt="Evaluaciones" />
                    <h3>Evaluaciones</h3>
                    <p>Las evaluaciones permitirán observar tu comprensión de los temas.</p>
                </div>

                <div class="card">
                    <img src="fot3.jpeg" alt="Actividades" />
                    <h3>Actividades</h3>
                    <p>Podrás realizar actividades interactivas en línea.</p>
                </div>
            </div>
        </div>
    </section>
    <section class="parallax" style="background-image:url('h2.jpg')">
        <div class="container reveal"></div>
    </section>

    <section class="carousel-section">
        <div class="container">

            <div class="carousel-container">
                <div class="carousel" id="carousel">
                    <div class="carousel-item">
                        <img src="g.jpeg" />
                        <div class="carousel-caption">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="g2.jpeg"  />
                        <div class="carousel-caption">

                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="g3.jpeg"  />
                        <div class="carousel-caption">

                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="g4.jpeg"  />
                        <div class="carousel-caption">
                        
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="g5.jpg"  />
                        <div class="carousel-caption">
    
                        </div>
                    </div>
                </div>
                <div class="carousel-nav">
                    <button class="carousel-btn" id="prevBtn">‹</button>
                    <button class="carousel-btn" id="nextBtn">›</button>
                </div>
            </div>
            <div class="carousel-dots" id="dots"></div>
        </div>
    </section>

    <footer class="min-footer">
        <div class="min-top">
            <div class="min-box">
                <div class="min-ico" aria-hidden="true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2a7 7 0 0 0-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 0 0-7-7Zm0 9.5a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5Z"/>
                    </svg>
                </div>
                <div><small>Dirección:</small><p>AV. 16 de julio</p></div>
            </div>
            <div class="min-box">
                <div class="min-ico" aria-hidden="true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M6.6 10.8a15.3 15.3 0 0 0 6.6 6.6l2.2-2.2c.3-.3.8-.4 1.1-.3 1.2.4 2.6.6 3.9.6.6 0 1 .4 1 .9V20c0 .6-.4 1-1 1C11.2 21 3 12.8 3 3c0-.6.4-1 1-1h3.6c.6 0 .9.4.9 1 0 1.3.2 2.7.6 3.9.1.4 0 .8-.3 1.1L6.6 10.8Z"/>
                    </svg>
                </div>
                <div><small>Teléfono:</small><p>2742396</p></div>
            </div>
            <div class="min-box">
                <div class="min-ico" aria-hidden="true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3 6c0-1.1.9-2 2-2h14c1.1 0 2 .9 2 2v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6Zm2 0 7 5 7-5H5Zm14 12V9l-7 5-7-5v9h14Z"/>
                    </svg>
                </div>
                <div><small>Correo electrónico:</small><p>isabelrocha@gmail.com</p></div>
            </div>
        </div>

        <div class="min-mid">
            
            <nav class="min-social" aria-label="Redes sociales">
                <a href="#" title="Facebook" aria-label="Facebook">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M22 12.06C22 6.49 17.52 2 11.94 2S2 6.49 2 12.06c0 5.03 3.68 9.2 8.49 9.95v-7.04H7.9v-2.9h2.59V9.86c0-2.56 1.52-3.98 3.85-3.98 1.11 0 2.27.2 2.27.2v2.5h-1.28c-1.26 0-1.65.78-1.65 1.58v1.9h2.81l-.45 2.9h-2.36V22c4.81-.76 8.49-4.93 8.49-9.94Z"/>
                    </svg>
                </a>
                <a href="#" title="Instagram" aria-label="Instagram">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5Zm5 5.5A5.5 5.5 0 1 0 17.5 13 5.5 5.5 0 0 0 12 7.5Zm5.75-1.25a1.25 1.25 0 1 1-1.25 1.25 1.25 1.25 0 0 1 1.25-1.25Z"/>
                    </svg>
                </a>
                <a href="#" title="Pinterest" aria-label="Pinterest">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2a10 10 0 0 0-3.6 19.3c-.1-.8-.2-2 .1-2.8.3-1 .9-3.2.9-3.2s-.2-.5-.2-1c0-.9.5-1.6 1.1-1.6.5 0 .7.4.7.9 0 .5-.3 1.3-.5 2-.1.6.3 1.1 1 1.1 1.2 0 2.1-1.3 2.1-3.1 0-1.6-1.1-2.7-2.6-2.7-1.8 0-2.9 1.3-2.9 2.7 0 .5.2 1.1.5 1.4.1.1.1.2.1.4-.1.2-.2.6-.3.7-.1.2-.3.2-.5.1-1.4-.6-2.1-2.1-2.1-3.8 0-2.8 2-5.4 5.8-5.4 3 0 5.3 2.1 5.3 4.9 0 3-1.9 5.4-4.4 5.4-.9 0-1.7-.5-2-.9l-.6 2.2c-.2.8-.8 1.9-1.2 2.5.9.3 1.9.5 2.9.5a10 10 0 0 0 0-20Z"/>
                    </svg>
                </a>
                <a href="#" title="X" aria-label="X">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M18.9 2H22l-7.01 8.02L23 22h-6.57l-5.14-6.67L5.4 22H2.29l7.49-8.57L1 2h6.7l4.64 6.08L18.9 2Zm-1.16 18h1.94L8.35 4H6.33l11.41 16Z"/>
                    </svg>
                </a>
            </nav>
        </div>
        <div class="min-bottom">Copyright © 2025  || Todos los derechos reservados</div>
    </footer>

    <script>
        const carousel = document.getElementById('carousel');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const dotsContainer = document.getElementById('dots');
        const items = document.querySelectorAll('.carousel-item');
        let currentIndex = 0;

        items.forEach((_, index) => {
            const dot = document.createElement('div');
            dot.classList.add('dot');
            if (index === 0) dot.classList.add('active');
            dot.addEventListener('click', () => goToSlide(index));
            dotsContainer.appendChild(dot);
        });

        const dots = document.querySelectorAll('.dot');

        function updateCarousel() {
            carousel.style.transform = `translateX(-${currentIndex * 100}%)`;
            dots.forEach((dot, index) => {
                dot.classList.toggle('active', index === currentIndex);
            });
        }

        function goToSlide(index) {
            currentIndex = index;
            updateCarousel();
        }

        function nextSlide() {
            currentIndex = (currentIndex + 1) % items.length;
            updateCarousel();
        }

        function prevSlide() {
            currentIndex = (currentIndex - 1 + items.length) % items.length;
            updateCarousel();
        }

        nextBtn.addEventListener('click', nextSlide);
        prevBtn.addEventListener('click', prevSlide);

        setInterval(nextSlide, 5000);

        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('reveal');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.card').forEach(card => {
            observer.observe(card);
        });
    </script>
</body>
</html>