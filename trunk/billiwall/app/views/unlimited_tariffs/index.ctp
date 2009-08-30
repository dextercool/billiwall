<style type="text/css">
.actions_panels {
	display: none;	
	float: right;	
	margin-left: 5px;
}

.name_row {
	cursor: default;	
}

.td_name_divs {
	float: left;
	min-height: 18px;
	padding-top: 2px;
}

#table_part {
	float: left;
}

#additional_part {
    overflow: hidden;
    float: left;
    margin-left: 20px;
	
}

#edit_form {
	border: 1px dashed #acacac;
	padding: 10px;
	display: none;
	background-color: #ecfbce;
	margin: 20px 0px;
}

#add_form {
	border: 1px dashed #acacac;
	padding: 10px;	
	background-color: #e1ebf2;
	
}

#flashMessage {
	width: 300px;
	margin-bottom: 10px;
	text-align: center;
}

#body_div {
	width: 850px;
	margin: 0 auto;	
	margin-top: 20px;
	margin-bottom: 20px;
	overflow: hidden;
	padding: 1px;
	/* background-color: #723464;
	#height: 50px; */
}

</style>

<script type="text/javascript">
	$(document).ready(function() {
	    var loader=$("#loader");
	    var edit_form=$("#edit_form");
	    $(".edit_links").click(function(){
		loader.show();
		$.get("<? echo $html->url(array('action'=>'edit')) ?>/"+this.id, function(data){
		    edit_form.html(data);
		    edit_form.show();
		    loader.hide();
		});
	    });
	});
	
	function panelMouseOver(id) {
	    $("#panel_"+id).show();
	}
	function panelMouseOut (id){
	    $("#panel_"+id).hide();
	}
</script>

<div id="body_div">

<div id="table_part">
<table class="db_table">
	<tr>
		<th>Название тарифного плана</th>
		<th>Upload (Kb/s)</th>
		<th>Download (Kb/s)</th>
		<th>Стоимость</th>		
	</tr>	
<? foreach ($unlimited_tariffs as $unlimited_tariff): 
	$id=$unlimited_tariff['UnlimitedTariff']['id'] ?>
	<tr>
	    <td class="name_row" onmouseover="panelMouseOver(<? echo $id ?>)" onmouseout="panelMouseOut(<? echo $id ?>)">
			<div class="td_name_divs" ><? echo $unlimited_tariff['UnlimitedTariff']['name'] ?></div>
			<div class="actions_panels" id="panel_<? echo $id ?>">
				<? echo $html->link($html->image("16x16/ticket_pencil.png", array("alt" => "edit", "title" => "Редактировать")), array('action'=>'edit', $id), array('escape'=>false, 'onclick'=>'javascript: return false;', 'id'=>$id, 'class'=>'edit_links')); ?>
				<? echo $html->link($html->image("16x16/cross.png", array("alt" => "delete", "title" => "Удалить")), array('action'=>'delete', $id), array('escape'=>false), "Вы действительно хотите удалить тарифный план?"); ?>				
			</div>		
		</td>
		<td><? echo $unlimited_tariff['UnlimitedTariff']['upload_speed'] ?></td>
		<td><? echo $unlimited_tariff['UnlimitedTariff']['download_speed'] ?></td>
		<td><? echo $unlimited_tariff['UnlimitedTariff']['value'] ?></td>
	</tr>
<? endforeach ?>
</table>
</div>

<div id="additional_part">	
	<? $session->flash(); ?>
	<div id="add_form">
		<h1>Новый тарифный план</h1>
		<?
		echo $form->create('UnlimitedTariff', array('action'=>'add'));
		echo $form->input('name', array('label'=>'Название', 'before'=>'<table class="form_table"><tr><td>', 'between'=>'</td><td>', 'after'=>'</td></tr>'));
		echo $form->input('value', array('label'=>'Стоимость', 'before'=>'<tr><td>', 'between'=>'</td><td>', 'after'=>'</td></tr>'));
		echo $form->input('upload_speed', array('label'=>'Скорость upload', 'before'=>'<tr><td>', 'between'=>'</td><td>', 'after'=>'</td></tr>'));
		echo $form->input('download_speed', array('label'=>'Скорость download', 'before'=>'<tr><td>', 'between'=>'</td><td>', 'after'=>'</td></tr>'));
		echo '<tr><td colspan="2">';
		echo $form->end('Добавить');
		echo '</td></tr></table>';
		?>
	</div></div>
	<div id="edit_form"></div>
	<div style="text-align: center; clear: both;"><? echo $html->image("24x24/loader.gif", array('alt'=>'loader', 'id'=>'loader')) ?></div>
</div>
