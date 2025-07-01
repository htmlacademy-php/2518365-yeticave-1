<?php

declare(strict_types=1);

/**
 * @var string[] $categories Список категорий
 * @var array<int,array{name: string, category: string, price: int, img: string, date: string} $lots Список лотов
 */
?>

<main>
    <nav class="nav">
        <ul class="nav__list container">
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $category): ?>
                    <li class="nav__item">
                        <a href="all-lots.html"><?=$category['name'] ?? '';?></a>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
        </nav>
    <section class="lot-item container">
        <?php if (!empty($lots)): ?>
        <?php foreach ($lots as $lot): ?>
      <h2><?=$lot['name'] ?? '';?></h2>
      <div class="lot-item__content">
        <div class="lot-item__left">
          <div class="lot-item__image">
            <img src="<?=$lot['img'] ?? '';?>" width="730" height="548" alt="<?=htmlspecialchars($lot['name'] ?? '');?>">
          </div>
          <p class="lot-item__category">Категория: <span><?=$lot['category_name'] ?? '';?></span></p>
          <p class="lot-item__description"><?=htmlspecialchars($lot['description'] ?? '');?></p>
        </div>
        <div class="lot-item__right">
          <div class="lot-item__state">
            <?php if (isset($lot['date_end'])):
                get_dt_range($lot['date_end'])
            ?>
            <div class="lot__timer timer <?=(get_dt_range($lot['date_end']))[0] === '00' ? 'timer--finishing' :'';?>">
                <?=(get_dt_range($lot['date_end']))[0].':'.(get_dt_range($lot['date_end']))[1];?>
            </div>
            <?php endif; ?>
            <div class="lot-item__cost-state">
              <div class="lot-item__rate">
                <span class="lot-item__amount">Текущая цена</span>
                <span class="lot-item__cost"><?=get_price((int)$lot['start_price'] ?? 0);?></span>
              </div>
              <div class="lot-item__min-cost">
                Мин. ставка <span><?=get_price((int)$lot['bet_step'] ?? 0);?></span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <?php endforeach; ?>
    <?php endif; ?>
  </main>
