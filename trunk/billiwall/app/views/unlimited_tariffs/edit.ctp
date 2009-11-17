<h1>Редактирование ТП</h1>
<?
echo $form->create('UnlimitedTariff', array('action'=>'edit'));
echo $form->input('name', array('label'=>'Название', 'before'=>'<table class="form_table"><tr><td>', 'between'=>'</td><td>', 'after'=>'</td></tr>'));
echo $form->input('value', array('label'=>'Стоимость', 'before'=>'<tr><td>', 'between'=>'</td><td>', 'after'=>'</td></tr>'));
echo $form->input('upload_speed', array('label'=>'Скорость upload', 'before'=>'<tr><td>', 'between'=>'</td><td>', 'after'=>'</td></tr>'));
echo $form->input('download_speed', array('label'=>'Скорость download', 'before'=>'<tr><td>', 'between'=>'</td><td>', 'after'=>'</td></tr>'));
echo $form->input('is_sum_speed', array('label'=>'Общая скорость', 'before'=>'<tr><td colspan="2">', 'after'=>'</td></tr>'));
echo '<tr><td colspan="2">';
echo $form->end('Сохранить изменения');
echo '</td></tr></table>';
?>