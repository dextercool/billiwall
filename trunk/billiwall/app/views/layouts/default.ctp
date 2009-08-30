<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>	
	<? echo $html->charset(); ?>
	<title>Billiwall</title>	
	<? echo $html->css('reset'); ?>	
	<? echo $html->css('style'); ?>
	
	<? echo $javascript->link('jquery'); ?>
	
	
	<style type="text/css">
		#<? echo $menu_selected_item ?> {
			color: #ffffff;
			text-decoration: underline;
		}
	</style>	
</head>	

<body>
<div id="top_header" style="padding: 0px;">
	<div id="menu">
		<? echo $html->link("Пользователи", "../users", array('class'=>'menu', 'id'=>'users')); ?> |
		<? echo $html->link("Тарифы", "../unlimited_tariffs", array('class'=>'menu', 'id'=>'tariffs')); ?> |
		<? echo $html->link("Логи", "../logs", array('class'=>'menu', 'id'=>'logs')); ?>
	</div>	
	<div id="product_information_top"><b>BILLiWall</b> system 0.1 (N-300809-1)</div>
</div>
<div id="top_menu">
	<div class="top_menu_button"></div>
</div>

<div id="content">
	<?php echo $content_for_layout ?>	
</div>
<div id="copyright">
    © Pavlo Zarichniy, Nikita Bykovsky<br>Kherson, Ukraine. 2009
</div>

</body>

</html><pre>
