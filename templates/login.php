<?php $classname = count($errors) ? " form--invalid" : ""; ?>
<form class="form container <?=$classname;?>" action="../login.php" method="post"> <!-- form--invalid -->
    <h2>Вход</h2>
    <?php $classname = isset($errors['email']) ? 'form__item--invalid' : '';
    $error = isset($errors['email']) ? $errors['email'] : "";
    $value = isset($enter['email']) ? $enter['email'] : ""; ?>
    <div class="form__item <?=$classname;?>"> <!-- form__item--invalid -->
        <label for="email">E-mail*</label>
        <input id="email" type="text" name="enter[email]" placeholder="Введите e-mail" value="<?=htmlspecialchars($value);?>" required>
        <span class="form__error"><?=$error;?></span>
    </div>
    <?php $classname = isset($errors['password']) ? 'form__item--invalid' : '';
    $error = isset($errors['password']) ? $errors['password'] : "";
    $value = isset($enter['password']) ? $enter['password'] : ""; ?>
    <div class="form__item form__item--last <?=$classname;?>">
        <label for="password">Пароль*</label>
        <input id="password" type="password" name="enter[password]" placeholder="Введите пароль" required>
        <span class="form__error"><?=$error;?></span>
    </div>
    <?php if (isset($errors)): ?>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <ul>
            <?php foreach ($errors as $err => $val): ?>
                <li><strong><?= $dict[$err]; ?>:</strong> <?=$val;?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <button type="submit" class="button">Войти</button>
</form>
