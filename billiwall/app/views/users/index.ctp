<style type="text/css">
    .actions_panels {
        display: none;
        float: right;
        margin-left: 5px;
    }

    .name_row {
        cursor: pointer;
    }

    .td_name_divs {
        float: left;
        min-height: 18px;
        padding-top: 2px;
    }

    #table_part {
        float: left;
        padding: 1px;
    }

    #additional_part {
        float: left;
        margin-left: 20px;
        overflow: hidden;
        width: 340px;
    }

    #edit_form {
        border: 1px dashed #acacac;
        padding: 10px;
        display: none;
        background-color: #ecfbce;
        margin: 0px 0px;
    }

    #add_form {
        border: 1px dashed #acacac;
        padding: 10px;
        background-color: #e1ebf2;
        overflow: hidden;
        display: none;
        margin-bottom: 20px;
    }

    #flashMessage {
        margin-bottom: 10px;
        text-align: center;
    }    
    .tools {
        padding-left: 3px;
        margin: 3px;
        margin-top: 0px;
        border: 1px solid #87aade;
        background-color: #e1ebf2;
        display: none;
        overflow: hidden;
    }

    .tools_buttons {
        float: right;
        margin-top: 3px;
        margin-bottom: 3px;
    }

    input {
        margin: 0px;
        padding: 0px;
    }
</style>

<script type="text/javascript">
    var toolbox_visible=false;
    $(document).ready(function() {
        $("#UserLinkTo").attr('disabled', true);
        tp_choice();
        var loader=$("#loader");
        $(".name_row").mouseover(function() {
            $("#panel_"+this.id).show();
        });
        $(".name_row").mouseout(function() {
            $("#panel_"+this.id).hide();
        });
        $(".edit_links").click(function(){
            loader.show();
            $.get("<? echo $html->url(array('action'=>'edit')) ?>/"+this.id, function(data){
                var edit_form=$("#edit_form");
                edit_form.html(data);
                edit_form.show();
                $("#add_form").hide();
                loader.hide();
            });
        });
    });

    function toolBoxGo(id) {
        if (toolbox_visible==false) {
            toolbox_visible=true;
            showToolBox(id);
        } else {
            toolbox_visible=false;
            hideToolBox(id);
        }
    }
    function showToolBox(id) {
        $("#tool_box_"+id).show();
    }
    function hideToolBox(id) {
        $("#tool_box_"+id).hide();
    }

    function linkTo(){
        $("#UserUnlimitedTariffId").attr('disabled', true);
        $("#UserBalance_addform").attr('disabled', true);
        $("#UserLinkTo").attr('disabled', false);
    }

    function noGroups(){
        $("#UserUnlimitedTariffId").attr('disabled', false);
        $("#UserBalance_addform").attr('disabled', false);
        $("#UserLinkTo").attr('disabled', true);
    }

    function makeGroup(){
        $("#UserUnlimitedTariffId").attr('disabled', false);
        $("#UserBalance_addform").attr('disabled', false);
        $("#UserLinkTo").attr('disabled', true);
    }

    function tp_choice(){
        $("#manual_download_speed").attr('disabled', true);
        $("#manual_upload_speed").attr('disabled', true);        
        $("#non_sym_channel").attr('disabled', true);
    }

    function tp_manual_choice(){
        $("#manual_download_speed").attr('disabled', false);
        $("#manual_upload_speed").attr('disabled', false);        
        $("#non_sym_channel").attr('disabled', true);
    }

    function tp_manual_choice_sym(){
        $("#manual_download_speed").attr('disabled', true);
        $("#manual_upload_speed").attr('disabled', true);        
        $("#non_sym_channel").attr('disabled', false);
    }
</script>

<div id="body_div">

    <div id="table_part">
        <table class="db_table">
            <tr>
                <th width="20" class="center">ID</th>
                <th width="205">Имя</th>
                <th width="100" class="center">Логин</th>
                <th width="80" class="center">Local IP</th>
                <th width="80" class="center">VPN IP</th>
                <th width="150" class="center">Тарифный план</th>
                <th width="75" class="center">Отключение</th>
                <th width="65" class="center">Баланс</th>
            </tr>
            <!-- Табличка со списком юзверей -->
            <? foreach ($users as $user):
                $id=$user['User']['id'];
                if ($user['User']['blocked']==true) $balance_class="blocked_balance_value"; else $balance_class="unblocked_balance_value";
                $days=0;
                $sum=0;
                if ($sum+$user['UnlimitedTariff']['value']>0)
                    while (($sum+$user['UnlimitedTariff']['value'])<=$user['User']['balance']) {
                        $sum+=$user['UnlimitedTariff']['value'];
                        $days++;
                    }
                ?>
            <tr>
                <td class="center"><? echo $user['User']['id'] ?></td>
                <td class="name_row" id="<? echo $user['User']['id'] ?>">
                    <div class="overflow">
                        <div class="td_name_divs"><? echo $user['User']['second_name'].' '.$user['User']['first_name'].' '.$user['User']['third_name'] ?><br><span class="comment"><? echo $user['User']['comment'] ?></span></div>
                        <div class="actions_panels" id="panel_<? echo $user['User']['id'] ?>">
                                <? echo $html->link($html->image("16x16/ticket_pencil.png", array("alt" => "edit", "title" => "Редактировать")), "", array('escape'=>false, 'onclick'=>'javascript: return false;', 'id'=>$id, 'class'=>'edit_links'))."&nbsp;";
                                echo $html->image('16x16/wrench_screwdriver.png', array('alt'=>'tools', 'onclick'=>'toolBoxGo('.$user['User']['id'].')')) ?>
                        </div>
                    </div>
                    <div class="tools" class="toolboxes" id="tool_box_<? echo $user['User']['id'] ?>">
                            <? echo $form->create('User', array('action'=>'plus_balance'));
                            echo $form->input('id', array('type'=>'hidden', 'value'=>$user['User']['id']));
                            echo $form->input('balance', array('label'=>'Баланс +', 'style'=>'width: 35px;', 'before'=>'<table class="noborder"><tr><td>', 'between'=>'</td><td>', 'after'=>'</td><td>'));
                            echo $form->end('грн.').'</td></tr></table>'; ?>
                        <span class="small">MAC: <b><? echo $user['User']['mac'] ?></b></span><br>
                        <span class="small">Пароль: <b><? echo $user['User']['password'] ?></b></span><br>
                        <span class="small">Download-speed: <b><? echo $user['UnlimitedTariff']['download_speed'] ?> Kb</b></span><br>
                        <span class="small">Upload-speed: <b><? echo $user['UnlimitedTariff']['upload_speed'] ?> Kb</b></span><br><br>
                        <span class="small">Адрес: <b><? echo $user['Street']['name'].', '.$user['User']['apt'] ?></b></span><br>
                        <span class="small">Подъезд/этаж: <b><? echo $user['User']['entr'].'/'.$user['User']['floor'] ?></b></span><br>
                        <span class="small">Корпус: <b><? echo $user['User']['house_part'] ?></b></span><br>
                        <span class="small">Телефон: <b><? echo $user['User']['tel'] ?></b></span><br>
                        <span class="small">E-Mail: <b><? echo $user['User']['email'] ?></b></span><br>



                        <!-- Форма назначения администратора или снятие этого статуса -->
                            <? if ($userRole=='admin') {
                                if ($user['User']['role']!='admin') echo '<div style="height: 10px;"></div>';
                                echo '<div class="lefted">';
                                if ($user['User']['role']=='user') echo $form->create('User', array('action'=>'new_sub_admin')).$form->input('id', array('value'=>$user['User']['id'])).'<input type="submit" style="color: #b90101; font-weight: bold;" value="Администратор" /></form>';
                                elseif ($user['User']['role']=='sub_admin') echo $form->create('User', array('action'=>'cancel_sub_admin')).$form->input('id', array('value'=>$user['User']['id'])).'<input type="submit" style="color: #037700; font-weight: bold;" value="Пользователь" /></form>';
                                echo '</div>';
                            } ?>

                        <div class="tools_buttons">
                                <? echo $html->link($html->image("16x16/cross.png", array("alt" => "delete", "title" => "Удалить")), array('action'=>'delete', $id), array('escape'=>false), "Вы действительно хотите удалить пользователя ".$user['User']['real_name']."?"); ?>
                        </div>
                    </div>
                </td>
                <td class="center"><? echo $user['User']['login'] ?></td>
                <td class="center"><? echo $user['User']['local_ip'] ?></td>
                <td class="center"><? echo $user['User']['vpn_ip'] ?></td>
                <td class="center"><? echo $user['UnlimitedTariff']['name'].'<span class="small"> ('.$user['UnlimitedTariff']['value'].')</span>' ?></td>
                <td class="center"><? if ($user['User']['blocked']==true) echo "-"; else echo date("d-m-Y", strtotime("+".$days." days"))."<br>(<b>".$days."</b> дней)" ?></td>
                <td class="center"><span class="<? echo $balance_class ?>"><? echo $user['User']['balance'] ?></span>
                                                <? if ($user['User']['credit_balance']>0) echo '<br>Кредит: <b>'.$user['User']['credit_balance'].'</b>' ?></td>
            </tr>
            <? endforeach ?>
        </table>

    </div>

    <div id="additional_part">
        <? $session->flash(); ?>
        <a href="/" style="display: block; margin-bottom: 5px;" onclick="javascript: $('#add_form').show(); return false;">Новый пользователь</a>
        <div id="add_form">            
            <? echo $form->create('User', array('action'=>'add'));
            
            echo $form->input('second_name', array('label'=>'Фамилия:', 'before'=>'<table class="form_table"><tr><td>', 'between'=>'</td><td>', 'after'=>'</td></tr>'));
            echo $form->input('first_name', array('label'=>'Имя:', 'before'=>'<tr><td>', 'between'=>'</td><td>', 'after'=>'</td></tr>'));
            echo $form->input('third_name', array('label'=>'Отчество:', 'before'=>'<tr><td>', 'between'=>'</td><td>', 'after'=>'</td></tr>'));
            echo "<tr><td>Дом</td><td>".$form->select('street_id', $Streets_list)."</td></tr>";
            echo $form->input('house_part', array('label'=>'Корпус:', 'before'=>'<tr><td>', 'between'=>'</td><td>', 'after'=>'</td></tr>'));
            echo $form->input('apt', array('label'=>'Квартира:', 'before'=>'<tr><td>', 'between'=>'</td><td>', 'after'=>'</td></tr>'));
            echo $form->input('floor', array('label'=>'Подъезд:', 'before'=>'<tr><td>', 'between'=>'</td><td>', 'after'=>'</td></tr>'));
            echo $form->input('floor', array('label'=>'Этаж:', 'before'=>'<tr><td>', 'between'=>'</td><td>', 'after'=>'</td></tr>'));
            echo $form->input('email', array('label'=>'E-Mail:', 'before'=>'<tr><td>', 'between'=>'</td><td>', 'after'=>'</td></tr>'));
            echo $form->input('tel', array('label'=>'Телефон:', 'cols'=>'25', 'rows'=>'2', 'before'=>'<tr><td style="padding-bottom: 10px;">', 'between'=>'</td><td style="padding-bottom: 10px;">', 'after'=>'</td></tr>'));


            echo $form->input('login', array('label'=>'Логин:', 'before'=>'<tr><td style="border-top: 1px dashed #acacac; padding-top: 10px;">', 'between'=>'</td><td  style="border-top: 1px dashed #acacac; padding-top: 10px;">', 'after'=>'</td></tr>'));
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
            echo '<tr><td><input type="radio" name="data[User][speed_type]" value="1" onclick="tp_choice();" checked="checked" /> Скорость ТП</td><td></td></tr>';
            echo '<tr><td><input type="radio" name="data[User][speed_type]" value="3" onclick="tp_manual_choice_sym();" /> Сумм. скорость: </td><td> <input type="text" name="data[User][total_speed]" id="non_sym_channel" style="width: 50px;" /></td></tr>';
            echo '<tr><td><input type="radio" name="data[User][speed_type]" value="2" onclick="tp_manual_choice();" /> Разд. скорость: </td><td> D:<input type="text" name="data[User][download_speed]" id="manual_download_speed" style="width: 50px;" /> U:<input type="text" name="data[User][upload_speed]" id="manual_upload_speed" style="width: 50px;"</td></tr>';
            echo '</table></td></tr>';
            echo '<tr style="height: 15px;"><td></td></tr>';

            
            echo $form->input('balance', array('label'=>'<b>Начальный баланс</b>:', 'value'=>'0', 'id'=>'UserBalance_addform','before'=>'<tr><td>', 'between'=>'</td><td>', 'after'=>'</td></tr>'));
            echo '<tr style="height: 15px;"><td></td></tr>';

            echo $form->input('comment', array('label'=>'Комментарий:', 'cols'=>'25', 'rows'=>'4', 'before'=>'<tr><td>', 'between'=>'</td><td>', 'after'=>'</td></tr>'));
            echo '<tr><td colspan="2">'.$form->end('В биллинг!').'</td></tr></table>';
            ?>
        </div></div>
    <div id="edit_form"></div>
    <div style="text-align: center; clear: both;"><? echo $html->image("24x24/loader.gif", array('alt'=>'loader', 'id'=>'loader')) ?></div>
</div>

</div>