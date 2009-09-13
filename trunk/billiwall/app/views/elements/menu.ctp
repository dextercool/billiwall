<? if ($userRole=='user') { ?>
<div id="menu">
    <div id="menu_left">
            <? echo $html->link("Текущая статистика", "/users/stat", array('class'=>'menu', 'id'=>'stat')); ?> |
            <? echo $html->link("Тикеты", "/users/stat", array('class'=>'menu', 'id'=>'finance_operation')); ?>
    </div>
    <div id="menu_right"><? echo $html->link($html->image("32x32/logout.png", array("alt" => "exit", "title" => "Выход")), "/users/logout", array('escape'=>false)); ?></div>
</div>
<?} elseif ($userRole=='admin') { ?>
<div id="menu">
    <div id="menu_left">
            <? echo $html->link("Пользователи", "../users", array('class'=>'menu', 'id'=>'users')); ?> |
            <? echo $html->link("Тарифы", "../unlimited_tariffs", array('class'=>'menu', 'id'=>'tariffs')); ?> |
            <? echo $html->link("NOC", "../nocs", array('class'=>'menu', 'id'=>'nocs')); ?> |
            <? echo $html->link("Логи", "../logs", array('class'=>'menu', 'id'=>'logs')); ?>
    </div>
    <div id="menu_right"><? echo $html->link($html->image("32x32/logout.png", array("alt" => "exit", "title" => "Выход")), "/users/logout", array('escape'=>false)); ?></div>
</div>

<? } ?>