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
    <form class="form container form--invalid" action="sign-up.php" method="post" enctype="multipart/form-data" autocomplete="off">
      <h2>Регистрация нового аккаунта</h2>
      <?php $classname = isset($errors['email']) ? "form__item--invalid" : ""; ?>
      <div class="form__item <?= $classname; ?>" >
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?= get_post_value('email'); ?>">
        <?php if (isset($errors['email'])): ?>
        <span class="form__error">Введите e-mail</span>
        <?php endif; ?>
      </div>
      <?php $classname = isset($errors['password']) ? "form__item--invalid" : ""; ?>
      <div class="form__item <?= $classname; ?>" >
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль" value="<?= get_post_value('password'); ?>">
        <?php if (isset($errors['password'])): ?>
        <span class="form__error">Введите пароль</span>
        <?php endif; ?>
      </div>
      <?php $classname = isset($errors['name']) ? "form__item--invalid" : ""; ?>
      <div class="form__item <?= $classname; ?>" >
        <label for="name">Имя <sup>*</sup></label>
        <input id="name" type="text" name="name" placeholder="Введите имя" value="<?= get_post_value('name'); ?>">
        <?php if (isset($errors['name'])): ?>
        <span class="form__error">Введите имя</span>
        <?php endif; ?>
      </div>
      <?php $classname = isset($errors['message']) ? "form__item--invalid" : ""; ?>
      <div class="form__item <?= $classname; ?>" >
        <label for="message">Контактные данные <sup>*</sup></label>
        <textarea id="message" name="message" placeholder="Напишите как с вами связаться" value="<?= get_post_value('message'); ?>"></textarea>
        <?php if (isset($errors['message'])): ?>
        <span class="form__error">Напишите как с вами связаться</span>
        <?php endif; ?>
      </div>
      <?php if (isset($errors)): ?>
      <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме:
        <ul>
             <?php foreach ($errors as $value): ?>
                <li><strong><?= $value; ?>:</strong></li>
             <?php endforeach; ?>
           </ul>
      </span>
      <?php endif; ?>
      <button type="submit" class="button">Зарегистрироваться</button>
      <a class="text-link" href="/login.php">Уже есть аккаунт</a>
    </form>
  </main>
