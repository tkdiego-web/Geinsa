<?php
include 'connect.php'; // Archivo de conexión que creamos antes

// Configuración de paginación
$postsPorPagina = 3;
$paginaActual = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($paginaActual - 1) * $postsPorPagina;

// Obtener posts más recientes primero con paginación
$consultaPosts = $pdo->prepare("
    SELECT * FROM posts 
    ORDER BY created_at DESC 
    LIMIT :limit OFFSET :offset
");
$consultaPosts->bindValue(':limit', $postsPorPagina, PDO::PARAM_INT);
$consultaPosts->bindValue(':offset', $offset, PDO::PARAM_INT);
$consultaPosts->execute();
$posts = $consultaPosts->fetchAll(PDO::FETCH_ASSOC);

// Calcular total de páginas
$totalPosts = $pdo->query("SELECT COUNT(*) FROM posts")->fetchColumn();
$totalPaginas = ceil($totalPosts / $postsPorPagina);

// Obtener datos para la sidebar
$categorias = $pdo->query("SELECT * FROM categories")->fetchAll();
$tags = $pdo->query("SELECT * FROM tags")->fetchAll();
$postsRecientes = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC LIMIT 3")->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<title>Geinsa - Gestión Energética</title>
<link rel="shortcut icon" type="image/x-icon" href="img/favicon.png">

<link rel="stylesheet" href="css/fontawesome.min.css">

<link rel="stylesheet" href="css/themify-icons.css">

<link rel="stylesheet" href="css/elegant-line-icons.css">

<link rel="stylesheet" href="css/elegant-font-icons.css">

<link rel="stylesheet" href="css/flaticon.css">

<link rel="stylesheet" href="css/animate.min.css">

<link rel="stylesheet" href="css/bootstrap.min.css">

<link rel="stylesheet" href="css/slick.css">

<link rel="stylesheet" href="css/slider.css">

<link rel="stylesheet" href="css/odometer.min.css">

<link rel="stylesheet" href="css/venobox/venobox.css">

<link rel="stylesheet" href="css/owl.carousel.css">

<link rel="stylesheet" href="css/main.css">

<link rel="stylesheet" href="css/responsive.css">
<script src="js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
</head>
<body>

<!-- Sección del encabezado -->
<section class="page-header padding">
    <div class="container">
        <div class="page-content text-center">
            <h2>Blog</h2>
            <p>Últimas noticias sobre el sector energético</p>
        </div>
    </div>
</section>

<!-- Sección principal del blog -->
<section class="blog-section padding">
    <div class="container">
        <div class="blog-wrap row">
            <!-- Columna principal con posts -->
            <div class="col-lg-8 sm-padding">
                <div class="row">
                    <?php foreach ($posts as $post): ?>
                    <div class="col-lg-12 padding-15">
                        <div class="blog-item box-shadow">
                            <div class="blog-thumb">
                                <img src="<?= htmlspecialchars($post['image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>">
                                <span class="category">
                                    <a href="categoria.php?id=<?= $post['category_id'] ?>">
                                        <?= htmlspecialchars($post['category']) ?>
                                    </a>
                                </span>
                            </div>
                            <div class="blog-content">
                                <h3>
                                    <a href="post.php?id=<?= $post['id'] ?>">
                                        <?= htmlspecialchars($post['title']) ?>
                                    </a>
                                </h3>
                                <p><?= substr(htmlspecialchars($post['content']), 0, 200) ?>...</p>
                                <a href="post.php?id=<?= $post['id'] ?>" class="read-more">Leer más</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Paginación -->
                <ul class="pagination-wrap text-left mt-30">
                    <?php if ($paginaActual > 1): ?>
                    <li>
                        <a href="?page=<?= $paginaActual - 1 ?>">
                            <i class="ti-arrow-left"></i>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                    <li>
                        <a href="?page=<?= $i ?>" <?= $i == $paginaActual ? 'class="active"' : '' ?>>
                            <?= $i ?>
                        </a>
                    </li>
                    <?php endfor; ?>

                    <?php if ($paginaActual < $totalPaginas): ?>
                    <li>
                        <a href="?page=<?= $paginaActual + 1 ?>">
                            <i class="ti-arrow-right"></i>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4 padding-15">
                <div class="sidebar-wrap">
                    <!-- Búsqueda -->
                    <div class="widget-content">
                        <form action="buscar.php" method="GET" class="search-form">
                            <input type="text" name="q" class="form-control" placeholder="Buscar...">
                            <button class="search-btn" type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                        </form>
                    </div>

                    <!-- Categorías -->
                    <div class="widget-content">
                        <h4>Categorías</h4>
                        <ul class="widget-links">
                            <?php foreach ($categorias as $categoria): ?>
                            <li>
                                <a href="categoria.php?id=<?= $categoria['id'] ?>">
                                    <?= htmlspecialchars($categoria['name']) ?>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <!-- Posts Recientes -->
                    <div class="widget-content">
                        <h4>Publicaciones Recientes</h4>
                        <ul class="thumb-post">
                            <?php foreach ($postsRecientes as $post): ?>
                            <li>
                                <img src="<?= htmlspecialchars($post['image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>">
                                <a href="post.php?id=<?= $post['id'] ?>">
                                    <?= htmlspecialchars($post['title']) ?>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <!-- Etiquetas -->
                    <div class="widget-content">
                        <h4>Etiquetas</h4>
                        <ul class="tags">
                            <?php foreach ($tags as $tag): ?>
                            <li>
                                <a href="etiqueta.php?id=<?= $tag['id'] ?>">
                                    <?= htmlspecialchars($tag['name']) ?>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tus scripts JS -->
 
<script>
  function enviar_mensaje() {
      var a = document.getElementById("chat-input");
      if ("" != a.value) {
          var b = document.getElementById("get-number").innerHTML,
              c = document.getElementById("chat-input").value,
              d = "https://web.whatsapp.com/send",
              e = b,
              f = "&text=" + c;
          if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) var d = "whatsapp://send";
          var g = d + "?phone=" + e + f;
          window.open(g, "_blank");
      }
  }
  
  const whatsapp_chat = document.getElementById("whatsapp-chat");
  
  function cerrar_chat() {
      whatsapp_chat.classList.add("hide");
      whatsapp_chat.classList.remove("show");
  }
  
  function mostrar_chat() {
      whatsapp_chat.classList.add("show");
      whatsapp_chat.classList.remove("hide");
  }
  </script>
<script>
    function cambiarIdioma(idioma) {
      if (idioma === 'es') {
        document.getElementById('titulo').innerText = 'Bienvenido';
        document.getElementById('descripcion').innerText = 'Esta es una página en español.';
      } else if (idioma === 'en') {
        document.getElementById('titulo').innerText = 'Welcome';
        document.getElementById('descripcion').innerText = 'This is a page in English.';
      }
    }

    /*carousel*/
$(document).ready(function(){
        $('.customer-logos').slick({
            slidesToShow: 6,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 1500,
            arrows: false,
            dots: false,
            pauseOnHover:false,
            responsive: [{
                breakpoint: 768,
                setting: {
                    slidesToShow:4
                }
            }, {
                breakpoint: 520,
                setting: {
                    slidesToShow: 3
                }
            }]
        });
    });

</script>
  </script>

<script data-cfasync="false" src="../../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script src="js/vendor/jquery-1.12.4.min.js"></script>

<script src="js/vendor/bootstrap.min.js"></script>

<script src="js/vendor/tether.min.js"></script>

<script src="js/vendor/headroom.min.js"></script>

<script src="js/vendor/owl.carousel.min.js"></script>

<script src="js/vendor/smooth-scroll.min.js"></script>

<script src="js/vendor/venobox.min.js"></script>

<script src="js/vendor/jquery.ajaxchimp.min.js"></script>

<script src="js/vendor/slick.min.js"></script>

<script src="js/vendor/waypoints.min.js"></script>

<script src="js/vendor/odometer.min.js"></script>

<script src="js/vendor/wow.min.js"></script>

<script src="js/main.js"></script>
</body>
</html>

