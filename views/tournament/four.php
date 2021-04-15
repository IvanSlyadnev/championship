<?php
use yii\helpers\Html;
?>
<h1>Приветствуем вас на нашем туринре четырех команд</h1>

<?php if ($controller == 0) :?>
    <?=Html::a('Играть первый тур', ['four', 'controller'=>($controller+1), 'step'=>1])?>
<?php endif;?>

<?php if ($controller == 1) :?>
    <?=Html::a('Игарть второй тур', ['four', 'controller'=>($controller+1), 'step'=>2])?>
<?php endif;?>

<?php if ($controller == 2) :?>
    <?=Html::a('Игарть третий тур', ['four', 'controller'=>($controller+1), 'step'=>3])?>
<?php endif;?>
<?php if ($controller == 3) :?>
    <?=Html::a('Играть финал', ['four', 'controller'=>($controller+1), 'step'=>4])?>
<?php endif;?>

<?php
include "group.php";
?>
<br>
<?=($controller>=3) ? 'ФИНАЛ' : ''?>
<br>
<?php if ($controller >= 3) :?>
    <?php foreach ($play_off as $play):?>
        <?=$play[0]->name;?>
        <?=$play[0]->match_goals.' - '.$play[1]->match_goals?>
        <?=$play[1]->name;?>
        <br>
    <?php endforeach;?>
<?php endif;?>
<?php if ($controller == 4) :?>
    <h1>Чемпион турнира <?=$results[0]['winner']->name?></h1>
<?php endif;?>


<?=Html::a('Начать заново', ['four', 'controller' =>0, 'step'=>0])?>