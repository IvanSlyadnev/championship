<?php
use \yii\helpers\Html;
$names_of_groups = ['A', 'B', 'C', 'D'];
use yii\helpers\Url;
?>

<br>
<div class="container info">
    <?php foreach ($_SESSION['info'] as $key=>$info):?>
        <?php if ($key==0 || $key % (count($groups)*2) == 0) :?>
            <br>
            <?=($key/(count($groups)*2) + 1).' -ый тур'?>
            <br>
        <?php endif;?>
    <?=$_SESSION['info'][$key]." / "?>
    <?php endforeach;?>
</div>

<?php foreach ($groups as $index=>$group) :?>
    <?='Группа '.$names_of_groups[$index]?>
    <table class="table-danger" border="1">
        <thead>
        <tr>
            <td>Название команды</td>
            <td>флаг</td>
            <td>кол-во сыгранных матчей</td>
            <td>кол-во очков</td>
            <td>Разница забитых и пропущенных</td>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($group as $key=>$team) :?>
            <?php if ($controller >= 3) :?>
            <tr style="background-color: <?= (($key==0 ||$key==1)) ?'green' : 'red'?>">
            <?php else :?>
                <tr>
            <?php endif;?>
                <td><a href="<?=Url::toRoute(['team/view', 'id'=>$team->id])?>"><?=$team->name?></a></td>
                <td><img src="<?=$team->getImage()?>" width="30px" height="50px"></td>
                <td><?=$step?></td>
                <td><?=$team->score?></td>
                <td><?=($team->goals_scored-$team->goals_missed)?></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
<?php endforeach;?>

