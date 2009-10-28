<style type="text/css">
    #real_name {
        padding: 3px;
        padding-left: 0px;
        font: 18px Tahoma;
    }

    .invisible_table td {
        padding: 2px;
        padding-left: 0px;
        padding-right: 5px;
        font: 11px Tahoma;
    }

    #stat_userinfo {
        padding: 15px;
        padding-top: 10px;
        background-color: #e2ebf2;
        float: left;
    }

    #balance {
        font: 36px Tahoma;
        color: #0b7100;
        font-weight: bold;
    }

    #stat_credit {
        margin-top: 20px;
        padding: 15px;
        padding-top: 12px;
        background-color: #ecfbce;
        float: left;
    }

    .submit {
        text-align: center;
    }

</style>

<script type="text/javascript">
    $(document).ready(function(){
        $("#stat_userinfo").corner("10px");
        $("#stat_credit").corner("10px");
        $("#stat_credit").width($("#stat_userinfo").width());
    })
</script>

<? if ($user['UnlimitedTariff']['upload_speed']=='0') $upload_speed="max"; else $upload_speed=$user['UnlimitedTariff']['upload_speed'];
if ($user['UnlimitedTariff']['download_speed']=='0') $download_speed="max"; else $download_speed=$user['UnlimitedTariff']['download_speed'];

$days=0; $sum=0;
while (($sum+$user['UnlimitedTariff']['value'])<=$user['User']['balance']) {
    $sum+=$user['UnlimitedTariff']['value'];
    $days++;
}
if ($user['User']['blocked']==true) $off="-"; else $off=date("d-m-Y", strtotime("+".$days." days"))."</b> (".$days." дней)"

        ?>

<div id="body_div">
    <div class="container">
        <div style="float: left; margin-right: 20px;">
            <!-- Общая статистика пользователя -->
            <div class="container">
                <div id="stat_userinfo">
                    <div id="real_name"><b><? echo $user['User']['first_name'].' '.$user['User']['third_name'].' '.$user['User']['second_name'] ?></b></div>
                    <div>
                        <table class="invisible_table">
                            <tr>
                                <td>Local IP:</td>
                                <td><b><? echo $user['User']['local_ip'] ?></b></td>
                            </tr>
                            <tr>
                                <td>VPN IP:</td>
                                <td><b><? echo $user['User']['vpn_ip'] ?></b></td>
                            </tr>
                            <tr>
                                <td>Тариф:</td>
                                <td><b><? echo $user['UnlimitedTariff']['name'] ?></b> (<? echo $user['UnlimitedTariff']['value']." грн./день" ?>)</td>
                            </tr>
                            <tr>
                                <td>Отключение:</td>
                                <td><b><? echo $off ?></b></td>
                            </tr>
                            <tr>
                                <td style="vertical-align: middle;"><b>Баланс:</b></td>
                                <td><span id="balance" <? if ($user['User']['blocked']==1) echo 'style="color: #cc0000;"'?>><? echo $user['User']['balance'] ?></span> грн.</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <? if ($user['User']['credit_balance']==0 && $user['User']['balance']<=($user['UnlimitedTariff']['value']*2)) {  ?>
            <!-- Меню кредитования -->
            <div class="container">
                <div id="stat_credit">
                    <div id="real_name">
                            <? echo $form->create('user', array('action'=>'get_credit/'.$user['User']['id']));
                            echo $form->end('Получить кредит на 3 дня'); ?>
                    </div>
                </div>
            </div>
            <? } ?>
        </div>
        <div style="float: left; max-width: 950px; text-align: center;">
            <? $session->flash(); ?>
            <b>Дорогой пользователь нашей сети! Мы рады приветствовать Вас на нашем биллинге!</b><br><br>
            Обращаем Ваше внимание на то, что это - базовая версия нашей нашей системы, которая не является стабильной и полнофункциональной. <br>
            Если Вы заметили какую-то проблему в её работе - пожалуйста, сообщите нам по электронной почте <a href="mailto:p.zarichniy@gmail.com">p.zarichniy@gmail.com</a>
            или <a href="mailto:kabal@globalnet.ks.ua">kabal@globalnet.ks.ua</a>.
            
        </div>

    </div>
</div>