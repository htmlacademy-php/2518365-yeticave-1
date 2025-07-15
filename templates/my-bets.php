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
    <section class="rates container">
      <h2>Мои ставки</h2>
      <?php if (!empty($bets)): ?>
      <table class="rates__list">
        <?php foreach($bets as $bet):
        if($user_id === $bet['winner_id']) {
          $bet_status = 'win';
          $rates_item_status ='rates__item--win';
          $rates_timer_status = 'timer--win';
        }elseif((get_dt_range($bet['date_end']))[0] === '00' && (get_dt_range($bet['date_end']))[1] === '00') {
          $bet_status = 'end';
          $rates_item_status ='rates__item--end';
          $rates_timer_status = 'timer--end';
        }elseif((get_dt_range($bet['date_end']))[0] === '00') {
          $rates_timer_status = 'timer--finishing';
        }else {
          $bet_status = 'default';
          $rates_timer_status = '';
          $rates_item_status ='';
        }
        ?>
        <tr class="rates__item <?=$rates_item_status;?>">
          <td class="rates__info">
            <div class="rates__img">
              <img src="<?=$bet['img'] ?? '';?>" width="54" height="40" alt="<?=htmlspecialchars($bet['lot_name'] ?? '');?>">
            </div>
            <div>
            <h3 class="rates__title"><a href="/lot.php?id=<?=$bet['lot_id'] ?? 0;?>"><?=htmlspecialchars($bet['lot_name'] ?? '');?></a></h3>
            <?php if($bet_status === 'win'): ?>
              <p><?=htmlspecialchars($bet['message']);?></p>
            <?php endif; ?>
            </div>
          </td>
          <td class="rates__category">
            <?=$bet['category_name'] ?? '';?>
          </td>
          <td class="rates__timer">
            <div class="timer <?=$rates_timer_status?>">
            <?php if($bet_status === 'win'): ?>
              Ставка выиграла
            <?php elseif($bet_status === 'end'): ?>
              Торги окончены
            <?php else: ?>
            <?=(get_dt_range($bet['date_end']))[0].':'.(get_dt_range($bet['date_end']))[1];?>
            <?php endif; ?>
            </div>
          </td>
          <td class="rates__price">
            <?=get_price((int)$bet['start_price'] ?? 0);?>
          </td>
          <td class="rates__time">
            <?=time_ago($bet['created_at'] ?? ''); ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </table>
      <?php endif; ?>
    </section>
</main>
