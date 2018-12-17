<section class="rates container">
    <h2>Мои ставки</h2>
    <?php if (!empty($bets)): ?>
        <table class="rates__list">
            <?php foreach ($bets as $val): ?>
                <?php $classname = intval($val['winner_id']) === intval($user_id) ? 'rates__item--win' : ''; ?>
                <tr class="rates__item <?=$classname;?>">
                    <td class="rates__info">
                        <div class="rates__img">
                            <img src="<?=$val['picture'];?>" width="54" height="40" alt="<?=htmlspecialchars($val['lot_title']);?>">
                        </div>
                        <div>
                            <h3 class="rates__title"><a href="lot.php?id=<?=$val['lot_id'];?>"><?=htmlspecialchars($val['lot_title']);?></a></h3>
                            <?php if (intval($val['winner_id']) === intval($user_id)): ?>
                                <p><?=htmlspecialchars($val['contacts']);?></p>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td class="rates__category">
                        <?=$val['category_title'];?>
                    </td>
                    <td class="rates__timer">
                        <?php if (intval($val['winner_id']) === intval($user_id)): ?>
                            <div class="timer timer--win">Ставка выиграла</div>
                        <?php elseif (!empty($val['winner_id']) && intval($val['winner_id']) !== intval($user_id)): ?>
                            <div class="timer timer--win">Торги окончены</div>
                        <?php else : ?>
                            <div class="timer timer--finishing">
                                <?=lottime_left($val['completion_date'])?>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td class="rates__price">
                        <?=cost_formatting(htmlspecialchars($val['bet_amount']))?>
                    </td>
                    <td class="rates__time">
                        <?=add_time_of_bet($val['add_date']);?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <h2>Вы пока не сделали ни одной ставки</h2>
    <?php endif; ?>
</section>
