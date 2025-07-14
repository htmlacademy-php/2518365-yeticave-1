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
                        <a href="all-lots.php?id=<?=$category['id'] ?? '';?>"><?=$category['name'] ?? '';?></a>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </nav>
    <form class="form container form--invalid" action="login.php" method="post" enctype="multipart/form-data">
      <h2>Вход</h2>
      <?php $classname = isset($errors['email']) ? "form__item--invalid" : ""; ?>
      <div class="form__item <?= $classname; ?>" >
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?= get_post_value('email'); ?>">
        <?php if (isset($errors['email'])): ?>
        <span class="form__error">Введите e-mail</span>
        <?php endif; ?>
      </div>
      <?php $classname = isset($errors['password']) ? "form__item--invalid" : ""; ?>
      <div class="form__item form__item--last <?= $classname; ?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль" value="<?= get_post_value('password'); ?>">
        <?php if (isset($errors['password'])): ?>
        <span class="form__error">Введите пароль</span>
        <?php endif; ?>
      </div>
      <?php if (isset($errors)): ?>
      <span class="form__error form__error--bottom">
             <?php foreach ($errors as $value): ?>
                <p><strong><?= $value; ?></strong></p>
             <?php endforeach; ?>
      </span>
      <?php endif; ?>
      <button type="submit" class="button">Войти</button>
    </form>
</main>
