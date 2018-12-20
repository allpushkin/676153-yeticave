<?php $classname = count($errors) ? ' form--invalid' : ''; ?>
<form class="form form--add-lot container <?=$classname;?>" action="../add.php" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
    <h2>Добавление лота</h2>
    <div class="form__container-two">
        <?php $classname = isset($errors['title']) ? 'form__item--invalid' : '';
        $error = isset($errors['title']) ? $errors['title'] : '';
        $value = isset($lot['title']) ? $lot['title'] : ''; ?>
        <div class="form__item <?=$classname;?>"> <!-- form__item--invalid -->
            <label for="lot-name">Наименование</label>
            <input id="lot-name" type="text" name="lot[title]" placeholder="Введите наименование лота" value="<?=$value;?>" required>
            <span class="form__error"><?=$error;?></span>
        </div>
        <?php $classname = isset($errors['category']) ? 'form__item--invalid' : '';
        $error = isset($errors['category']) ? $errors['category'] : '';
        $value = isset($lot['category']) ? $lot['category'] : ''; ?>
        <div class="form__item <?=$classname;?>">
            <label for="category">Категория</label>
            <select id="category" name="lot[category]" required>
                <option>Выберите категорию</option>
                <?php foreach ($categories as $key => $cat): ?>
                    <option value="<?=$cat['id']?>"<?php if($cat['id'] === $value): echo ' selected'; endif;?>><?=$cat['title']?></option>
                <?php endforeach; ?>
            </select>
            <span class="form__error">Выберите категорию</span>
        </div>
    </div>
    <?php $classname = isset($errors['desc']) ? 'form__item--invalid' : '';
    $error = isset($errors['desc']) ? $errors['desc'] : '';
    $value = isset($lot['desc']) ? $lot['desc'] : ''; ?>
    <div class="form__item form__item--wide <?=$classname;?>">
        <label for="message">Описание</label>
        <textarea id="message" name="lot[desc]" placeholder="Напишите описание лота" required><?=$value;?></textarea>
        <span class="form__error"><?=$error;?></span>
    </div>
    <?php $classname = isset($lot['lot_picture']) ? 'form__item--uploaded' : ''; ?>
    <div class="form__item form__item--file <?=$classname;?>"> <!-- form__item--uploaded -->
        <label>Изображение</label>
        <div class="preview">
            <button class="preview__remove" type="button">x</button>
            <div class="preview__img">
                <img src="" width="113" height="113" alt="Изображение лота">
            </div>
        </div>
        <div class="form__input-file">
            <input class="visually-hidden" type="file" name="lot_picture" id="photo2" value="" required>
            <label for="photo2">
                <span>+ Добавить</span>
            </label>
        </div>
    </div>
    <div class="form__container-three">
        <?php $classname = isset($errors['start_price']) ? 'form__item--invalid' : '';
        $error = isset($errors['start_price']) ? $errors['start_price'] : '';
        $value = isset($lot['start_price']) ? $lot['start_price'] : ''; ?>
        <div class="form__item form__item--small <?=$classname;?>">
            <label for="lot-rate">Начальная цена</label>
            <input id="lot-rate" type="number" name="lot[start_price]" placeholder="0" value="<?=$value;?>" required>
            <span class="form__error"><?=$error;?></span>
        </div>
        <?php $classname = isset($errors['step']) ? 'form__item--invalid' : '';
        $error = isset($errors['step']) ? $errors['step'] : '';
        $value = isset($lot['step']) ? $lot['step'] : ''; ?>
        <div class="form__item form__item--small <?=$classname;?>">
            <label for="lot-step">Шаг ставки</label>
            <input id="lot-step" type="number" name="lot[step]" placeholder="0" value="<?=$value;?>" required>
            <span class="form__error"><?=$error;?></span>
        </div>
        <?php $classname = isset($errors['completion_date']) ? 'form__item--invalid' : '';
        $error = isset($errors['completion_date']) ? $errors['completion_date'] : '';
        $value = isset($lot['completion_date']) ? $lot['completion_date'] : ''; ?>
        <div class="form__item <?=$classname;?>">
            <label for="lot-date">Дата окончания торгов</label>
            <input class="form__input-date" id="lot-date" type="date" name="lot[completion_date]" value="<?=$value;?>" required>
            <span class="form__error"><?=$error;?></span>
        </div>
    </div>
    <?php if (isset($errors)): ?>
      <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
      <ul>
        <?php foreach ($errors as $err => $val): ?>
            <li><strong><?= $dict[$err]; ?>:</strong> <?=$val;?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
    <button type="submit" class="button">Добавить лот</button>
</form>
