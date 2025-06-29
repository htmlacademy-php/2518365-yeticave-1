<?php

declare(strict_types=1);

/**
 * @var string[] $categories Список категорий
 * @var array<int,array{name: string, category: string, price: int, img: string, date: string} $lots Список лотов
 */
?>
<main class="container">
    <section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
        <ul class="promo__list">
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $category): ?>
                <li class="promo__item promo__item--<?=isset($category['symbol_code']) ? $category['symbol_code'] : '';?>">
                    <a class="promo__link" href="pages/all-lots.html"><?=$category['name'] ?? '';?></a>
                </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </section>
    <section class="lots">
        <div class="lots__header">
            <h2>Открытые лоты</h2>
        </div>
        <ul class="lots__list">
            <?php if (!empty($lots)): ?>
                <?php foreach ($lots as $lot): ?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?=$lot['img'] ?? '';?>" width="350" height="260" alt="<?=htmlspecialchars($lot['name'] ?? '');?>">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?=$lot['category'] ?? '';?></span>
                    <h3 class="lot__title"><a class="text-link" href="/lot.php?id=<?=$lot['id'] ?? '';?>"><?=htmlspecialchars($lot['name'] ?? '');?></a></h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена</span>
                            <span class="lot__cost"><?=get_price((int)$lot['start_price'] ?? 0);?></span>
                        </div>
                        <?php if (isset($lot['date_end'])):
                            get_dt_range($lot['date_end'])
                        ?>
                        <div class="lot__timer timer <?=(get_dt_range($lot['date_end']))[0] === '00' ? 'timer--finishing' :'';?>">
                            <?=(get_dt_range($lot['date_end']))[0].':'.(get_dt_range($lot['date_end']))[1];?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </li>
            <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </section>
</main>
