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
            <form class="lot-item__form" action="https://echo.htmlacademy.ru" method="post" autocomplete="off">
              <p class="lot-item__form-item form__item form__item--invalid">
                <label for="cost">Ваша ставка</label>
                <input id="cost" type="text" name="cost" placeholder="12 000">
                <span class="form__error">Введите наименование лота</span>
              </p>
              <button type="submit" class="button">Сделать ставку</button>
            </form>
          </div>
          <div class="history">
            <h3>История ставок (<span>10</span>)</h3>
            <table class="history__list">
              <tr class="history__item">
                <td class="history__name">Иван</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">5 минут назад</td>
              </tr>
              <tr class="history__item">
                <td class="history__name">Константин</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">20 минут назад</td>
              </tr>
              <tr class="history__item">
                <td class="history__name">Евгений</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">Час назад</td>
              </tr>
              <tr class="history__item">
                <td class="history__name">Игорь</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">19.03.17 в 08:21</td>
              </tr>
              <tr class="history__item">
                <td class="history__name">Енакентий</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">19.03.17 в 13:20</td>
              </tr>
              <tr class="history__item">
                <td class="history__name">Семён</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">19.03.17 в 12:20</td>
              </tr>
              <tr class="history__item">
                <td class="history__name">Илья</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">19.03.17 в 10:20</td>
              </tr>
              <tr class="history__item">
                <td class="history__name">Енакентий</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">19.03.17 в 13:20</td>
              </tr>
              <tr class="history__item">
                <td class="history__name">Семён</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">19.03.17 в 12:20</td>
              </tr>
              <tr class="history__item">
                <td class="history__name">Илья</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">19.03.17 в 10:20</td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </section>
    <?php endforeach; ?>
    <?php endif; ?>
  </main>
