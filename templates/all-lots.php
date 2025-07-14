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
                        <a href="all-lots.php?id=<?=$category['id'] ?? '';?>"><?=$category['name'] ?? '';?></a>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
        </nav>
        <div class="container">
           <section class="lots">
             <h2>Все лоты в категории <span>«<?=$category_name;?>»</span></h2>
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
           <?php if($pages_count > 1): ?>
             <ul class="pagination-list">
               <li class="pagination-item pagination-item-prev"><a <?php if ($cur_page != 1): ?> href="/all-lots.php?id=<?=$id?>&page=<?=$cur_page-1?>" <?php endif; ?>>Назад</a></li>
                   <?php foreach ($pages as $page): ?>
                       <li class="pagination-item <?php if ($page == $cur_page): ?>pagination-item-active<?php endif; ?>">
                           <a href="/all-lots.php?id=<?=$id?>&page=<?=$page;?>"><?=$page;?></a>
                       </li>
                   <?php endforeach; ?>
               <li class="pagination-item pagination-item-next"><a <?php if ($cur_page != $pages_count): ?> href="/all-lots.php?id=<?=$id?>&page=<?=$cur_page+1?>" <?php endif; ?>>Вперед</a></li>
             </ul>
           <?php endif; ?>
           </div>
  </main>
