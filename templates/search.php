<div class="container">
    <?php if (!empty($lots)): ?>
    <section class="lots">
        <h2>Результаты поиска по запросу «<span><?=$search;?></span>»</h2>
        <ul class="lots__list">
            <?php foreach ($lots as $lot): ?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?=$lot['picture'];?>" width="350" height="260" alt="<?=$lot['lot_title'];?>">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?=$lot['category_title'];?></span>
                    <h3 class="lot__title"><a class="text-link" href="lot.php?id=<?=$lot['id'];?>"><?=$lot['lot_title'];?></h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <?php if ($lot['current_bet']): ?>
                               <span class="lot__amount"><?=$lot['lot_bets'];?> ставок</span>
                               <span class="lot__cost"><?=cost_formatting($lot['current_bet']);?></span>
                            <?php else: ?>
                               <span class="lot__amount">Стартовая цена</span>
                               <span class="lot__cost"><?=cost_formatting($lot['start_price']);?></span>
                            <?php endif; ?>
                        </div>
                        <?php if (strtotime($lot['completion_date']) > strtotime('now')): ?>
                            <div class="lot__timer timer">
                                <?=lottime_left($lot['completion_date'])?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>

    </section>
        <?=$pagination;?>
    <?php else: ?>
        <h2>Ничего не найдено по вашему запросу</h2>
    <?php endif; ?>

</div>
