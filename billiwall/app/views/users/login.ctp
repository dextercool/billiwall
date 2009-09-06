<div class="vspace"></div>
<h1>Авторизация в системе</h1>
<?php
if  ($session->check('Message.auth')) $session->flash('auth');
    echo $form->create('User', array('action' => 'login'));
    echo $form->input('login', array('before'=>'<table class="form_table centered"><tr><td>', 'between'=>'</td><td>', 'after'=>'</td></tr>', 'label'=>'Логин: '));
    echo $form->input('hashedPassword', array( 'before'=>'<tr><td>', 'type'=>'password', 'between'=>'</td><td>', 'after'=>'</td></tr>', 'label' => 'Пароль: '));
    echo '<tr><td colspan="2" class="submit">'.$form->end('Войти')."</td></tr></table>";
?>
