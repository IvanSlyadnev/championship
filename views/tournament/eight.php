<?php
use yii\helpers\Html;
?>
<h1>Приветствуем вас на нашем туринре восьми команд</h1>
<br>
<?php if ($controller == 0) :?>
    <?php if (!$basket) :?>
        <?=Html::a('Сортировать по карзинам', ['eight', 'basket' => true])?>
    <?php else:?>
        <?=Html::a('Просто сортировать', ['eight', 'basket' => false])?>
    <?php endif?>

<?php endif;?>
<br>
<div class="menu">

    <?php if ($controller >= 3):?>
        <?="Полуфинал"."<br>"?>
        <?php foreach ($play_off as $play):?>
            <?=$play[0]->name;?>
            <?=$play[0]->match_goals.' - '.$play[1]->match_goals?>
            <?=$play[1]->name;?>
            <br>
        <?php endforeach;?>
    <?php endif;?>
    <br>

    <?php if ($controller >= 4):?>
        <h2><?=$finalists[0]->name.' '.$finalists[0]->final_goals.' -  '.$finalists[1]->final_goals.' '.$finalists[1]->name?></h2>
        <?php if ($controller > 4) :?>
            <?php if ($finalists[0]->final_goals > $finalists[1]->final_goals) :?>
                <h1><?=$finalists[0]->name.' - Чемпион'?></h1>
            <?php else:?>
                <h1><?=$finalists[1]->name.' - Чемпион'?></h1>
            <?php endif;?>
        <?php endif;?>
    <?php endif;?>
</div>
<?php if ($controller == 0) :?>
    <?=Html::a('Играть первый тур', ['eight', 'controller'=>($controller+1), 'step'=>1])?>
<?php endif;?>

<?php if ($controller == 1) :?>
    <?=Html::a('Игарть второй тур', ['eight', 'controller'=>($controller+1), 'step'=>2])?>
<?php endif;?>

<?php if ($controller == 2) :?>
    <?=Html::a('Игарть третий тур', ['eight', 'controller'=>($controller+1), 'step'=>3, 'semifinal' =>true])?>
<?php endif;?>

<?php if ($controller == 3) :?>
    <?=Html::a('Игарть полуфиналы', ['eight', 'controller'=>($controller+1), 'step'=>4, 'final' =>true])?>
<?php endif;?>


<?php if ($controller ==4) :?>
    <?=Html::a('Игарть финал', ['eight', 'controller'=>($controller+1), 'step'=>5, 'final' =>true])?>
<?php endif;?>

<?php
include "group.php";
?>

<?=Html::a('Начать заново', ['eight', 'controller' =>0, 'step'=>0])?>
