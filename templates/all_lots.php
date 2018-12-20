<div class="container">
    <?php if (!empty($lots)): ?>
        <section class="lots">
            <h2>Все лоты в категории <span>«<?=$category['title'];?>»</span></h2>
            <ul class="lots__list">
                <?php foreach ($lots as $lot): ?>
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="<?=$lot['picture'];?>" width="350" height="260" alt="Сноуборд">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?=$lot['category_title'];?></span>
                        <h3 class="lot__title"><a class="text-link" href="lot.php?id=<?=$lot['id'];?>"><?=htmlspecialchars($lot['lot_title']);?></a></h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <?php if ($lot['current_bet']): ?>
                                    <span class="lot__amount"><?=$lot['lot_bets'];?> ставок</span>
                                    <span class="lot__cost"><?=cost_formatting(htmlspecialchars($lot['current_bet']));?></span>
                                <?php else: ?>
                                    <span class="lot__amount">Стартовая цена</span>
                                    <span class="lot__cost"><?=cost_formatting(htmlspecialchars($lot['start_price']));?></span>
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
        <?php if ($pages_count > 1): ?>
            <ul class="pagination-list">
                <li class="pagination-item pagination-item-prev">
                    <?php if ($cur_page > 1): ?>
                        <a href="/all_lots.php?category=<?=$category_id?>&page=<?=$cur_page - 1;?>">Назад</a>
                    <?php endif; ?>
                </li>
                <?php foreach ($pages as $page): ?>
                    <li class="pagination-item <?php if ((int)$page === (int)$cur_page): ?>pagination-item-active<?php endif; ?>">
                        <a href="/all_lots.php?category=<?=$category_id?>&page=<?=$page;?>"><?=$page;?></a>
                    </li>
                <?php endforeach; ?>
                <li class="pagination-item pagination-item-next">
                    <?php if ($cur_page < $pages_count): ?>
                        <a href="/all_lots.php?category=<?=$category_id?>&page=<?=$cur_page + 1; ?>">Вперед</a>
                    <?php endif; ?>
                </li>
            </ul>
        <?php endif; ?>
    <?php else: ?>
        <h2>В этой категории нет активных лотов на данный момент</h2>
    <?php endif; ?>
</div>
