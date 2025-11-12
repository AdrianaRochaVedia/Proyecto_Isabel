<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="styles.css">
<title>Página Informativa</title>
</head>
<body>
  <section class="hero">
    <div class="hero-content reveal">
      
      <h1>BIENVENIDO</h1>
      
      <a class="btn" href="login/login.php">Iniciar Sesión</a>
    </div>
  </section>

  <section class="info">
    <div class="container">
    

      <div class="cards">
        <div class="card reveal">
          <img src="cul3.jpg" alt="Guía" />
          <h3>Contenido Claro</h3>
          <p>Podrás observar lecciones claras y dinámicas.</p>
        </div>

        <div class="card reveal">
          <img src="FOT4.jpeg" alt="Salud" />
          <h3>Evaluaciones </h3>
          <p>Las evaluaciones permitirán observar tu comprensión.</p>
        </div>

        <div class="card reveal">
          <img src="FOT5.jpeg" alt="Comunidad" />
          <h3>Actividades</h3>
          <p>Podrás realizar actividades en línea.</p>
        </div>
      </div>
    </div>
  </section>
  <section class="parallax" style="background-image:url('h2.jpg')">
    <div class="container reveal">

    </div>
  </section>

  <section id="galeria" class="galeria-wrap">
    <div class="container">
      <h2 class="title reveal">Galería</h2>
      <div class="galeria-grid">
        <div class="item reveal"><img src="fot1.jpeg" alt="Imagen 1" /></div>
        <div class="item reveal"><img src="fot2.jpeg" alt="Imagen 2" /></div>
        <div class="item reveal"><img src="fot3.jpeg" alt="Imagen 3" /></div>
      </div>
    </div>
  </section>
<style>
:root{--bg:#151219;--bg2:#1d1821;--txt:#e9e9ea;--mut:#bdb8c2;--line:rgba(255,255,255,.12);--pink:#e36d8d;--pink2:#ffc1dd}
footer.min-footer{color:var(--txt);background:var(--bg)}
.min-top{display:grid;grid-template-columns:1fr 1fr 1fr;border-bottom:1px solid var(--line)}
.min-box{display:flex;gap:14px;align-items:center;padding:24px 20px;background:var(--bg2)}
.min-box+.min-box{border-left:1px solid var(--line)}
.min-ico{width:52px;height:52px;border-radius:50%;display:grid;place-items:center;color:#fff;background:#231b25;border:1px solid rgba(255,255,255,.15)}
.min-box small{color:var(--pink2);font-weight:700;display:block;margin-bottom:6px}
.min-box p{margin:0;font-weight:800;line-height:1.3;font-size:clamp(16px,2vw,22px)}
.min-mid{display:grid;grid-template-columns:1fr;justify-items:center;gap:14px;padding:22px}
.min-logo{width:78px;height:78px;border-radius:50%;display:grid;place-items:center;background:#231b25;color:var(--pink2);font:900 28px/1 system-ui}
.min-social{display:flex;gap:12px}
.min-social a{width:40px;height:40px;border-radius:50%;display:grid;place-items:center;text-decoration:none;color:#8d2146;background:radial-gradient(120% 120% at 30% 20%,#fff,#ffd1e6 60%,var(--pink2))}
.min-bottom{border-top:1px solid var(--line);text-align:center;color:var(--mut);padding:14px}
.min-bottom b{color:#fff}
@media (max-width:960px){.min-top{grid-template-columns:1fr}.min-box+.min-box{border-left:none;border-top:1px solid var(--line)}}
</style>

<footer class="min-footer">

  <div class="min-top">
    <div class="min-box">
      <div class="min-ico" aria-hidden="true">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a7 7 0 0 0-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 0 0-7-7Zm0 9.5a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5Z"/></svg>
      </div>
      <div><small>Dirección:</small><p>AV. 16 de julio</p></div>
    </div>
    <div class="min-box">
      <div class="min-ico" aria-hidden="true">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M6.6 10.8a15.3 15.3 0 0 0 6.6 6.6l2.2-2.2c.3-.3.8-.4 1.1-.3 1.2.4 2.6.6 3.9.6.6 0 1 .4 1 .9V20c0 .6-.4 1-1 1C11.2 21 3 12.8 3 3c0-.6.4-1 1-1h3.6c.6 0 .9.4.9 1 0 1.3.2 2.7.6 3.9.1.4 0 .8-.3 1.1L6.6 10.8Z"/></svg>
      </div>
      <div><small>Teléfono:</small><p>2742396</p></div>
    </div>
    <div class="min-box">
      <div class="min-ico" aria-hidden="true">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M3 6c0-1.1.9-2 2-2h14c1.1 0 2 .9 2 2v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6Zm2 0 7 5 7-5H5Zm14 12V9l-7 5-7-5v9h14Z"/></svg>
      </div>
      <div><small>Correo electrónico:</small><p>isabelrocha@gmail.com</p></div>
    </div>
  </div>

  <div class="min-mid">
    <div class="min-logo" aria-label="Logo">❤</div>
    <nav class="min-social" aria-label="Redes sociales">
      <a href="#" title="Facebook" aria-label="Facebook">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M22 12.06C22 6.49 17.52 2 11.94 2S2 6.49 2 12.06c0 5.03 3.68 9.2 8.49 9.95v-7.04H7.9v-2.9h2.59V9.86c0-2.56 1.52-3.98 3.85-3.98 1.11 0 2.27.2 2.27.2v2.5h-1.28c-1.26 0-1.65.78-1.65 1.58v1.9h2.81l-.45 2.9h-2.36V22c4.81-.76 8.49-4.93 8.49-9.94Z"/></svg>
      </a>
      <a href="#" title="Instagram" aria-label="Instagram">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5Zm5 5.5A5.5 5.5 0 1 0 17.5 13 5.5 5.5 0 0 0 12 7.5Zm5.75-1.25a1.25 1.25 0 1 1-1.25 1.25 1.25 1.25 0 0 1 1.25-1.25Z"/></svg>
      </a>
      <a href="#" title="Pinterest" aria-label="Pinterest">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 0 0-3.6 19.3c-.1-.8-.2-2 .1-2.8.3-1 .9-3.2.9-3.2s-.2-.5-.2-1c0-.9.5-1.6 1.1-1.6.5 0 .7.4.7.9 0 .5-.3 1.3-.5 2-.1.6.3 1.1 1 1.1 1.2 0 2.1-1.3 2.1-3.1 0-1.6-1.1-2.7-2.6-2.7-1.8 0-2.9 1.3-2.9 2.7 0 .5.2 1.1.5 1.4.1.1.1.2.1.4-.1.2-.2.6-.3.7-.1.2-.3.2-.5.1-1.4-.6-2.1-2.1-2.1-3.8 0-2.8 2-5.4 5.8-5.4 3 0 5.3 2.1 5.3 4.9 0 3-1.9 5.4-4.4 5.4-.9 0-1.7-.5-2-.9l-.6 2.2c-.2.8-.8 1.9-1.2 2.5.9.3 1.9.5 2.9.5a10 10 0 0 0 0-20Z"/></svg>
      </a>
      <a href="#" title="X" aria-label="X">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18.9 2H22l-7.01 8.02L23 22h-6.57l-5.14-6.67L5.4 22H2.29l7.49-8.57L1 2h6.7l4.64 6.08L18.9 2Zm-1.16 18h1.94L8.35 4H6.33l11.41 16Z"/></svg>
      </a>
    </nav>
  </div>
  <div class="min-bottom">Copyright © 2025 <b>GIS</b> || Todos los derechos reservados</div>
</footer>

 
</body>
</html>


</body>
</html>