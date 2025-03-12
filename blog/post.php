<?php
include 'connect.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$_GET['id']]);
$post = $stmt->fetch();

if (!$post) {
    header("Location: index.php");
    exit;
}
?>

<!-- Incluye el mismo header -->
<section class="blog-section padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <article class="blog-item box-shadow">
                    <img src="<?= htmlspecialchars($post['image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>">
                    <h1><?= htmlspecialchars($post['title']) ?></h1>
                    <div class="post-meta">
                        <span>Categoría: <?= htmlspecialchars($post['category']) ?></span>
                        <span>Fecha: <?= date('d/m/Y', strtotime($post['created_at'])) ?></span>
                    </div>
                    <div class="post-content">
                        <?= nl2br(htmlspecialchars($post['content'])) ?>
                    </div>
                </article>
            </div>
            <!-- Incluye el mismo sidebar -->
        </div>
    </div>
</section>