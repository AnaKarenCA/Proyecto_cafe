<?php include 'layout/header.php'; ?>

    <h1 style="padding: 2rem; color: var(--color-dark-green);">Categorías</h1>
    <div style="display: flex; gap: 2rem; flex-wrap: wrap; justify-content: center; padding: 2rem;">
        <?php foreach ($categorias as $cat): ?>
        <a href="/productos?id=<?= $cat['id'] ?>" style="text-decoration: none; width: 250px;">
            <div style="background-color: var(--color-card); border-radius: 15px; padding: 2rem; text-align: center; box-shadow: var(--shadow); transition: transform 0.3s;">
                <i class="fas fa-<?= $cat['icono'] ?? 'coffee' ?>" style="font-size: 3rem; color: var(--color-dark-orange);"></i>
                <h3 style="color: var(--color-dark-green); margin-top: 1rem;"><?= htmlspecialchars($cat['nombre']) ?></h3>
            </div>
        </a>
        <?php endforeach; ?>
    </div>

<?php include 'layout/footer.php'; ?>