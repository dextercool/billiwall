<?
echo $form->create('User', array('action'=>'edit'));
echo $form->input('real_name', array('label'=>'Реальное имя:', 'before'=>'<table class="form_table"><tr><td>', 'between'=>'</td><td>', 'after'=>'</td></tr>'));
echo $form->input('login', array('label'=>'Логин:', 'before'=>'<tr><td>', 'between'=>'</td><td>', 'after'=>'</td></tr>'));
echo $form->input('password', array('label'=>'Пароль:', 'type'=>'text', 'before'=>'<tr><td>', 'between'=>'</td><td>', 'after'=>'</td></tr>'));
echo $form->input('local_ip', array('label'=>'Local IP:', 'before'=>'<tr><td>', 'between'=>'</td><td>', 'after'=>'</td></tr>'));
echo $form->input('vpn_ip', array('label'=>'VPN IP:', 'before'=>'<tr><td>', 'between'=>'</td><td>', 'after'=>'</td></tr>'));
echo $form->input('mac', array('label'=>'MAC - адрес:', 'before'=>'<tr><td>', 'between'=>'</td><td>', 'after'=>'</td></tr>'));
echo "<tr><td>Тарифный план</td><td>".$form->select('unlimited_tariff_id', $UnlimitedTariffs_list, $SelectedUnlimitedTariff)."</td></tr>";
echo $form->input('balance', array('label'=>'<b>Баланс</b>:', 'before'=>'<tr><td>', 'between'=>'</td><td>', 'after'=>'</td></tr>'));
echo $form->input('comment', array('label'=>'Комментарий:', 'cols'=>'25', 'rows'=>'4', 'before'=>'<tr><td>', 'between'=>'</td><td>', 'after'=>'</td></tr>'));
echo '<tr><td colspan="2">'.$form->end('Сохранить изменения').'</td></tr></table>';
?>
