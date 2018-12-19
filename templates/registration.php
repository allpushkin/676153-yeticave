<?php $classname = count($errors) ? ' form--invalid' : ''; ?>
<form class="form container <?=$classname;?>" action="../registration.php" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
    <h2>Регистрация нового аккаунта</h2>
    <?php $classname = isset($errors['email']) ? 'form__item--invalid' : '';
    $error = isset($errors['email']) ? $errors['email'] : '';
    $value = isset($user['email']) ? $user['email'] : ''; ?>
    <div class="form__item  <?=$classname;?>"> <!-- form__item--invalid -->
        <label for="email">E-mail*</label>
        <input id="email" type="text" name="user[email]" placeholder="Введите e-mail" value="<?=htmlspecialchars($value);?>" required>
        <span class="form__error"><?=$error;?></span>
    </div>
    <?php $classname = isset($errors['password']) ? 'form__item--invalid' : '';
    $error = isset($errors['password']) ? $errors['password'] : '';
    $value = isset($user['password']) ? $user['password'] : ''; ?>
    <div class="form__item <?=$classname;?>">
        <label for="password">Пароль*</label>
        <input id="password" type="password" name="user[password]" placeholder="Введите пароль" required>
        <span class="form__error"><?=$error;?></span>
    </div>
    <?php $classname = isset($errors['name']) ? 'form__item--invalid' : '';
    $error = isset($errors['name']) ? $errors['name'] : '';
    $value = isset($user['name']) ? $user['name'] : ''; ?>
    <div class="form__item <?=$classname;?>">
        <label for="name">Имя*</label>
        <input id="name" type="text" name="user[name]" placeholder="Введите имя" value="<?=htmlspecialchars($value);?>" required>
    </div>
    <?php $classname = isset($errors['contacts']) ? 'form__item--invalid' : '';
    $error = isset($errors['contacts']) ? $errors['contacts'] : '';
    $value = isset($user['contacts']) ? $user['contacts'] : ''; ?>
    <div class="form__item <?=$classname;?>">
        <label for="message">Контактные данные*</label>
        <textarea id="message" name="user[contacts]" placeholder="Напишите как с вами связаться" required><?=htmlspecialchars($value);?></textarea>
        <span class="form__error"><?=$error;?></span>
    </div>
    <?php $classname = isset($user['avatar']) ? 'form__item--uploaded' : ''; ?>
    <div class="form__item form__item--file form__item--last <?=$classname;?>">
        <label>Аватар</label>
        <div class="preview">
            <button class="preview__remove" type="button">x</button>
            <div class="preview__img">
                <img src="" width="113" height="113" alt="Ваш аватар">
            </div>
        </div>
        <div class="form__input-file">
            <input class="visually-hidden" type="file" name="avatar" id="photo2" value="">
            <label for="photo2">
                <span>+ Добавить</span>
            </label>
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
    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="login.php">Уже есть аккаунт</a>
</form>
