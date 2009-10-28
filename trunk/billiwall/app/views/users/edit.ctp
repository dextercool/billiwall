<script type="text/javascript">
    function tp_choice_edit(){
        $("#manual_download_speed_edit").attr('disabled', true);
        $("#manual_upload_speed_edit").attr('disabled', true);
        $("#non_sym_channel_edit").attr('disabled', true);
    }

    function tp_manual_choice_edit(){
        $("#manual_download_speed_edit").attr('disabled', false);
        $("#manual_upload_speed_edit").attr('disabled', false);
        $("#non_sym_channel_edit").attr('disabled', true);
    }

    function tp_manual_choice_sym_edit(){
        $("#manual_download_speed_edit").attr('disabled', true);
        $("#manual_upload_speed_edit").attr('disabled', true);
        $("#non_sym_channel_edit").attr('disabled', false);
    }
</script>
<?
echo $form->create('User', array('action'=>'edit_personal_data'));

echo $form->input('second_name', array('label'=>'Фамилия:', 'before'=>'<table class="form_table"><tr><td style="width: 190px;">', 'between'=>'</td><td>', 'after'=>'</td></tr>'));
echo $form->input('first_name', array('label'=>'Имя:', 'before'=>'<tr><td>', 'between'=>'</td><td>', 'after'=>'</td></tr>'));
echo $form->input('third_name', array('label'=>'Отчество:', 'before'=>'<tr><td>', 'between'=>'</td><td>', 'after'=>'</td></tr>'));
echo "<tr><td>Дом</td><td>".$form->select('street_id', $Streets_list)."</td></tr>";
echo $form->input('house_part', array('label'=>'Корпус:', 'before'=>'<tr><td>', 'between'=>'</td><td>', 'after'=>'</td></tr>'));
echo $form->input('apt', array('label'=>'Квартира:', 'before'=>'<tr><td>', 'between'=>'</td><td>', 'after'=>'</td></tr>'));
echo $form->input('floor', array('label'=>'Подъезд:', 'before'=>'<tr><td>', 'between'=>'</td><td>', 'after'=>'</td></tr>'));
echo $form->input('floor', array('label'=>'Этаж:', 'before'=>'<tr><td>', 'between'=>'</td><td>', 'after'=>'</td></tr>'));
echo $form->input('email', array('label'=>'E-Mail:', 'before'=>'<tr><td>', 'between'=>'</td><td>', 'after'=>'</td></tr>'));
echo $form->input('tel', array('label'=>'Телефон:', 'cols'=>'25', 'rows'=>'2', 'before'=>'<tr><td>', 'between'=>'</td><td>', 'after'=>'</td></tr>'));
echo $form->input('comment', array('label'=>'Комментарий:', 'cols'=>'25', 'rows'=>'4', 'before'=>'<tr><td>', 'between'=>'</td><td>', 'after'=>'</td></tr>'));
echo '<tr><td colspan="2" style="padding-bottom: 10px;">'.$form->end('Сохранить изменения').'</td></tr></table>';

echo $form->create('User', array('action'=>'edit'));
echo $form->input('login', array('label'=>'Логин:', 'before'=>'<table class="form_table"><tr><td style="border-top: 1px dashed #acacac; padding-top: 10px; width: 190px;">', 'between'=>'</td><td  style="border-top: 1px dashed #acacac; padding-top: 10px;">', 'after'=>'</td></tr>'));
echo $form->input('password', array('label'=>'Пароль:', 'type'=>'text', 'before'=>'<tr><td>', 'between'=>'</td><td>', 'after'=>'</td></tr>'));
echo $form->input('local_ip', array('label'=>'Local IP:', 'before'=>'<tr><td>', 'between'=>'</td><td>', 'after'=>'</td></tr>'));
echo $form->input('vpn_ip', array('label'=>'VPN IP:', 'before'=>'<tr><td>', 'between'=>'</td><td>', 'after'=>'</td></tr>'));
echo $form->input('mac', array('label'=>'MAC - адрес:', 'before'=>'<tr><td>', 'between'=>'</td><td>', 'after'=>'</td></tr>'));

echo '<tr style="height: 15px;"><td></td></tr>';
echo '<tr><td colspan="2" style="border: 1px solid #acacac"><table>';
echo '<tr><td><input type="radio" name="data[User][is_group]" value="link_to" onclick="linkTo();" /> Присоединить к:</td><td>'.$form->select('group_id', $UserGroups_list).'</td></tr>';
echo '<tr><td><input type="radio" name="data[User][is_group]" value="true" onclick="makeGroup();" /> Групповой акк</td><td><input type="radio" name="data[User][is_group]" value="no_group" checked="checked" onclick="noGroups();" /> Без групп</td></tr>';
echo '</table></td></tr>';
//echo '<tr style="height: 15px;"><td></td></tr>';

echo '<tr style="height: 15px;"><td></td></tr>';
echo '<tr><td colspan="2" style="border: 1px solid #acacac"><table>';

echo '<tr><td>Тарифный план:</td><td>'.$form->select('unlimited_tariff_id', $UnlimitedTariffs_list).'</td></tr>';
echo '<tr><td><input type="radio" name="data[User][speed_type]" value="1" onclick="tp_choice_edit();" '.$Speed_types['1'].' /> Скорость ТП</td><td></td></tr>';
echo '<tr><td><input type="radio" name="data[User][speed_type]" value="3" onclick="tp_manual_choice_sym_edit();" '.$Speed_types['3'].' /> Сумм. скорость: </td><td> <input type="text" name="data[User][total_speed]" id="non_sym_channel_edit" style="width: 50px;" value="'.$UserData['User']['total_speed'].'" /></td></tr>';
echo '<tr><td><input type="radio" name="data[User][speed_type]" value="2" onclick="tp_manual_choice()_edit;" '.$Speed_types['2'].' /> Разд. скорость: </td><td> D:<input type="text" name="data[User][download_speed]" id="manual_download_speed_edit" style="width: 50px;" value="'.$UserData['User']['download_speed'].'" /> U:<input type="text" name="data[User][upload_speed]" id="manual_upload_speed_edit" style="width: 50px;" value="'.$UserData['User']['upload_speed'].'"></td></tr>';
echo '</table></td></tr>';
echo '<tr style="height: 15px;"><td></td></tr>';

echo '<tr><td colspan="2">'.$form->end('Сохранить изменения').'</td></tr></table>';
?>
