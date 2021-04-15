<?php
if (Yii::$app->user->isGuest) {
    echo "<h1>You are guest of our site, please login</h1>>";
} else {
    echo "<h1>Welcome to our site</h1>". "<h1>" .Yii::$app->user->identity->name. "</h1>";
}
?>

<?php if ($_GET['message'] !== null):?>
    <h2><?=$_GET['message']?></h2>
<?php endif;?>


