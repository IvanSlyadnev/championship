<h1>Приветствую вас на туринре шестнадцати команд</h1>
<?php
use yii\helpers\Html;
?>
<div class="menu">
    <?php if ($controller >= 3):?>
        <?="Четвертьфинал"."<br>"?>
        <?php foreach ($play_off as $play):?>
            <?=$play[0]->name;?>
            <?=$play[0]->match_goals.' - '.$play[1]->match_goals?>
            <?=$play[1]->name;?>
            <br>
        <?php endforeach;?>
    <?php endif;?>
    <br>

    <?php if ($controller >= 4) : ?>
        <?="Полуфинал"."<br>"?>
        <?php foreach ($semifinalists as $play):?>
            <?=$play[0]->name;?>
            <?=$play[0]->semifinal_goals.' - '.$play[1]->semifinal_goals?>
            <?=$play[1]->name;?>
            <br>
        <?php endforeach;?>
    <?php endif;?>
    <?php if ($controller >=5) :?>
        <?="Финал"."<br>"?>
        <h2><?=$finalists[0]->name.' '.$finalists[0]->final_goals.' -  '.$finalists[1]->final_goals.' '.$finalists[1]->name?></h2>
        <?php if ($controller > 5) :?>
            <?php if ($finalists[0]->final_goals > $finalists[1]->final_goals) :?>
                <h1><?=$finalists[0]->name.' - Чемпион'?></h1>
            <?php else:?>
                <h1><?=$finalists[1]->name.' - Чемпион'?></h1>
            <?php endif;?>
        <?php endif;?>
    <?php endif;?>

</div>
<div class="container">
    <?php if ($controller == 0) :?>
        <?=Html::a('Играть первый тур', ['sixteenth', 'controller'=>($controller+1), 'step'=>1])?>
    <?php endif;?>

    <?php if ($controller == 1) :?>
        <?=Html::a('Игарть второй тур', ['sixteenth', 'controller'=>($controller+1), 'step'=>2])?>
    <?php endif;?>

    <?php if ($controller == 2) :?>
        <?=Html::a('Игарть третий тур', ['sixteenth', 'controller'=>($controller+1), 'step'=>3])?>
    <?php endif;?>

    <?php if ($controller == 3) :?>
        <?=Html::a('Играть четвертьфинал', ['sixteenth', 'controller'=>($controller+1), 'step'=>4])?>
    <?php endif;?>

    <?php if ($controller == 4) :?>
        <?=Html::a('Играть полуфинал', ['sixteenth', 'controller'=>($controller+1), 'step'=>5])?>
    <?php endif;?>

    <?php if ($controller == 5) :?>
        <?=Html::a('Играть финал', ['sixteenth', 'controller'=>($controller+1), 'step'=>6])?>
    <?php endif;?>
    <?php
    include 'group.php';
    ?>

    <br>
    <?=Html::a('Начать заново', ['sixteenth', 'controller' =>0, 'step'=>0])?>
</div>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

<!--<?php if ($controller >=3) :?>
    <script>
        $(window).scrollTop($(document).height());
    </script>

<?php endif;?>-->
