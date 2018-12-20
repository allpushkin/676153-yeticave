<section class="lot-item container">
    <h2><?=htmlspecialchars($lot['lot_title']);?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="<?=$lot['picture'];?>" width="730" height="548" alt="Сноуборд">
            </div>
            <p class="lot-item__category">Категория: <span><?=$lot['category_title'];?></span></p>
            <p class="lot-item__description"><?=htmlspecialchars($lot['desc']);?></p>
        </div>
        <div class="lot-item__right">
            <div class="lot-item__state">
                <?php if (!$lot_close): ?>
                <div class="lot-item__timer timer">
                    <?=lottime_left($lot['completion_date'])?>
                </div>
                <?php endif; ?>
                <div class="lot-item__cost-state">
                    <div class="lot-item__rate">
                        <span class="lot-item__amount">Текущая цена</span>
                        <span class="lot-item__cost">
                            <?php
                            if($lot['current_bet']) {
                                print(cost_formatting(htmlspecialchars($lot['current_bet'])));
                            } else {
                                print(cost_formatting(htmlspecialchars($lot['start_price'])));
                            }
                            ?>
                        </span>
                    </div>
                    <div class="lot-item__min-cost">
                        Мин. ставка
                        <span><?=cost_formatting($min_bet);?></span>
                    </div>
                </div>

                <?php if (!empty($is_auth) && $is_auth['id'] !== $lot['author_id'] && !$bet_done && !$lot_close): ?>
                    <form class="lot-item__form" action="../lot.php?id=<?=$lot['id']?>" method="post">
                        <?php $classname = !empty($error) ? 'form__item--invalid' : '';
                        $value = isset($bet) ? $bet : ''; ?>
                        <p class="lot-item__form-item form__item <?=$classname;?>">
                            <label for="cost">Ваша ставка</label>
                            <input id="cost" type="text" name="bet_amount" placeholder="<?=$min_bet;?>" required>
                            <span class="form__error"><?=$error;?></span>
                        </p>
                        <button type="submit" class="button">Сделать ставку</button>
                    </form>
                <?php endif; ?>
            </div>
            <div class="history">
                <h3>История ставок (<?=count($bets);?>)</h3>
                <table class="history__list">
                    <?php if ($bets):?>
                      <?php foreach ($bets as $val):?>
                      <tr class="history__item">
                          <td class="history__name"><?=htmlspecialchars($val['username'])?></td>
                          <td class="history__price"><?=cost_formatting(htmlspecialchars($val['bet_amount']))?></td>
                          <td class="history__time"><?=add_time_of_bet($val['add_date']);?></td>
                      </tr>
                      <?php endforeach; ?>
                    <?php endif;?>

                </table>
            </div>
        </div>
    </div>
</section>
