<?php

declare(strict_types=1);

/**
 * @var string[] $categories Список категорий
 */
?>
<main>
    <nav class="nav">
        <ul class="nav__list container">
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $category): ?>
                    <li class="nav__item">
                        <a href="all-lots.php?id=<?= $category['id'] ?? ''; ?>"><?= $category['name'] ?? ''; ?></a>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </nav>
    <section class="lot-item container">
        <h2>404 Страница не найдена</h2>
        <p>Данной страницы не существует на сайте.</p>
    </section>
</main>
