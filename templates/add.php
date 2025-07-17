<?php

declare(strict_types=1);

/**
 * @var string[] $categories Список категорий
 * @var array<int,array{name: string, category: string, price: int, img: string, date: string} $lot Лот
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
    <form class="form form--add-lot container form--invalid" action="add.php" method="post"
          enctype="multipart/form-data">
        <h2>Добавление лота</h2>
        <div class="form__container-two">
            <?php $classname = isset($errors['name']) ? "form__item--invalid" : ""; ?>
            <div class="form__item <?= $classname; ?>">
                <label for="lot-name">Наименование <sup>*</sup></label>
                <input id="lot-name" type="text" name="name" placeholder="Введите наименование лота"
                       value="<?= get_post_value('name'); ?>">
                <?php if (isset($errors['name'])): ?>
                    <span class="form__error">Введите наименование лота</span>
                <?php endif; ?>
            </div>
            <?php $classname = isset($errors['category_id']) ? "form__item--invalid" : ""; ?>
            <div class="form__item <?= $classname; ?>">
                <label for="category">Категория <sup>*</sup></label>
                <select id="category" name="category_id">
                    <option>Выбрать</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>"
                                <?php if ($category['id'] === get_post_value('category_id')): ?>selected<?php endif; ?>><?= $category['name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['category_id'])): ?>
                    <span class="form__error">Выберите категорию</span>
                <?php endif; ?>
            </div>
        </div>
        <?php $classname = isset($errors['description']) ? "form__item--invalid" : ""; ?>
        <div class="form__item form__item--wide <?= $classname; ?>">
            <label for="message">Описание <sup>*</sup></label>
            <textarea id="message" name="description" placeholder="Напишите описание лота"
                      value="<?= get_post_value('description'); ?>"></textarea>
            <?php if (isset($errors['description'])): ?>
                <span class="form__error">Напишите описание лота</span>
            <?php endif; ?>
        </div>
        <?php $classname = isset($errors['img']) ? "form__item--invalid" : ""; ?>
        <div class="form__item form__item--file <?= $classname; ?>">
            <label>Изображение <sup>*</sup></label>
            <div class="form__input-file">
                <input class="visually-hidden" type="file" id="lot-img" name="img" value="">
                <label for="lot-img">
                    Добавить
                </label>
            </div>
        </div>
        <div class="form__container-three">
            <?php $classname = isset($errors['start_price']) ? "form__item--invalid" : ""; ?>
            <div class="form__item form__item--small <?= $classname; ?>">
                <label for="lot-rate">Начальная цена <sup>*</sup></label>
                <input id="lot-rate" type="text" name="start_price" placeholder="0"
                       value="<?= get_post_value('start_price'); ?>">
                <?php if (isset($errors['start_price'])): ?>
                    <span class="form__error">Введите начальную цену</span>
                <?php endif; ?>
            </div>
            <?php $classname = isset($errors['bet_step']) ? "form__item--invalid" : ""; ?>
            <div class="form__item form__item--small <?= $classname; ?>">
                <label for="lot-step">Шаг ставки <sup>*</sup></label>
                <input id="lot-step" type="text" name="bet_step" placeholder="0"
                       value="<?= get_post_value('bet_step'); ?>">
                <?php if (isset($errors['bet_step'])): ?>
                    <span class="form__error">Введите шаг ставки</span>
                <?php endif; ?>
            </div>
            <?php $classname = isset($errors['date_end']) ? "form__item--invalid" : ""; ?>
            <div class="form__item <?= $classname; ?>">
                <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
                <input class="form__input-date" id="lot-date" type="text" name="date_end"
                       placeholder="Введите дату в формате ГГГГ-ММ-ДД" value="<?= get_post_value('date_end'); ?>">
                <?php if (isset($errors['date_end'])): ?>
                    <span class="form__error">Введите дату завершения торгов</span>
                <?php endif; ?>
            </div>
        </div>
        <?php if (isset($errors)): ?>
            <span class="form__error form__error--bottom">Пожалуйста, исправьте следующие ошибки в форме:
           <ul>
             <?php foreach ($errors as $value): ?>
                 <li><strong><?= $value; ?>:</strong></li>
             <?php endforeach; ?>
           </ul>
        </span>
        <?php endif; ?>
        <button type="submit" class="button" name="submit">Добавить лот</button>
    </form>
</main>
