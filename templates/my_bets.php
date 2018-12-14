<section class="rates container">
    <h2>Мои ставки</h2>
    <table class="rates__list">
        <?php foreach ($bets as $val): ?>
        <tr class="rates__item">
            <td class="rates__info">
                <div class="rates__img">
                    <img src="<?=$val['picture'];?>" width="54" height="40" alt="<?=$val['lot_title'];?>">
                </div>
                <h3 class="rates__title"><a href="lot.php?id=<?=$val['lot_id'];?>"><?=$val['lot_title'];?></a></h3>
            </td>
            <td class="rates__category">
                <?=$val['category_title'];?>
            </td>
            <td class="rates__timer">
                <div class="timer timer--finishing">
                    <?=lottime_left($val['completion_date'])?>
                </div>
            </td>
            <td class="rates__price">
                <?=cost_formatting($val['bet_amount'])?>
            </td>
            <td class="rates__time">
                <?=add_time_of_bet($val['add_date']);?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</section>
