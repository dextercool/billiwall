<?
class UsersController extends AppController {
    var $name='Users';
    var $helpers = array('Html', 'Javascript', 'Ajax');
    var $components = array( 'RequestHandler' );

    function beforeFilter() {
        $this->Auth->allow('take_payment');
        parent::beforeFilter();
    }

    function index() {
        $this->set('users', $this->User->find('all', array('conditions'=>array('is_group'=>'0', 'group_id'=>'0'))));
        $user_group_headers=$this->User->find('all', array('conditions'=>array('is_group'=>'1')));
        foreach ($user_group_headers as $user_group_header) {
            //$users_groups[]=$this->User->Query("SELECT * FROM `users` WHERE (`is_group`='1' AND `id`=".$user_group_header['User']['id'].") OR (`is_group`='0' AND `group_id`=".$user_group_header['User']['id'].")");
            $users_groups[]=$this->User->find('all', array('conditions'=>"(`User`.`is_group`='1' AND `User`.`id`=".$user_group_header['User']['id'].") OR (`User`.`is_group`='0' AND `User`.`group_id`=".$user_group_header['User']['id'].")"));
        }
        if (isset($users_groups)) $this->set('users_groups', $users_groups);

        $this->set('menu_selected_item', 'users');
        //Using UnlimitedTariff model to generate it's list
        $this->loadModel('UnlimitedTariff');
        $this->set('UnlimitedTariffs_list', $this->UnlimitedTariff->find('list', array('fields'=>array('UnlimitedTariff.name'))));
        $this->set('Streets_list', $this->User->Street->find('list', array('fields'=>array('Street.name'))));
        $this->set('UserGroups_list', $this->User->find('list', array('fields'=>array('User.login'), 'conditions' => array('is_group' => 1))));
    }

    function add() {
        if (!empty($this->data)) {
            //if ($this->data['User']['is_group']=="no_group") $this->data['User']['is_group']=false;
            if ($this->data['User']['is_group']=="link_to") {
                $this->data['User']['is_group']=false;
                $this->data['User']['balance']=0;
            }

            if ($this->data['User']['balance']==0) $this->data['User']['blocked']=true;
            $this->data['User']['role']='user';
            $this->data['User']['hashedPassword']=$this->Auth->password($this->data['User']['password']);
            $this->User->save($this->data);
            $user=$this->User->find('first');

            App::import('Vendor', 'mikrotik');
            $server=new Server();
            $shell=$server->connect();
            $server->id=$this->User->id;
            $server->local_ip=$this->data['User']['local_ip'];
            $server->vpn_ip=$this->data['User']['vpn_ip'];
            $server->mac=$this->data['User']['mac'];
            if ($this->data['User']['speed_type']=="1") {
                $server->upload_speed=$user['UnlimitedTariff']['upload_speed'];
                $server->download_speed=$user['UnlimitedTariff']['download_speed'];
            } elseif ($this->data['User']['speed_type']=="2") {
                $server->upload_speed=$this->data['User']['upload_speed'];
                $server->download_speed=$this->data['User']['download_speed'];
            } elseif ($this->data['User']['speed_type']=="3") {
                $server->total_speed=$user['User']['total_speed'];
            }
            $server->speed_type=$this->data['User']['speed_type'];

            $server->login=$this->data['User']['login'];
            $server->password=$this->data['User']['password'];            
            $server->addUser();
            if ($this->data['User']['balance']!=0) $server->enableUser();
            $server->doCommands($shell);

            $this->Session->setFlash("Поздравляем с новым пользователем! ;)");
            
            $this->redirect($this->referer());
        }
    }

    function plus_balance($id=null) {
        if (!empty($this->data)) {
            $this->User->id=$this->data['User']['id'];
            $user=$this->User->find('first');
            if  ($this->data['User']['balance']<0) $sum=$this->data['User']['balance'];
            elseif ($user['User']['credit_balance']<=$this->data['User']['balance']) {
                $sum=$this->data['User']['balance']-$user['User']['credit_balance'];
                $this->data['User']['credit_balance']=0;
            } else {
                $sum=0;
                $this->data['User']['credit_balance']=$user['User']['credit_balance']-$this->data['User']['balance'];
            }

            $newBalance=$user['User']['balance']+$sum;
            $this->data['User']['balance']=$newBalance;
            if ($user['User']['blocked']==true)
                if ($newBalance>=$user['UnlimitedTariff']['value']) {
                    App::import('Vendor', 'mikrotik');
                    $server=new Server();
                    $shell=$server->connect();
                    $server->id=$user['User']['id'];
                    $server->enableUser();
                    $server->doCommands($shell);
                    $this->data['User']['blocked']=false;
                }
            $this->User->save($this->data);
            $this->Session->setFlash("Баланс пользователя был увеличен на ".$sum." грн.");
            $this->redirect($this->referer());
        }

    }

    function edit($id=null) {
        $this->User->id=$id;
        if (empty($this->data)) {
            $this->loadModel('UnlimitedTariff');
            $this->set('UnlimitedTariffs_list', $this->UnlimitedTariff->find('list', array('fields'=>array('UnlimitedTariff.name'))));
            $this->data=$this->User->read();
            $this->set('UserData', $this->data);
            $this->set('SelectedUnlimitedTariff', $this->data['User']['unlimited_tariff_id']);
            $this->set('UnlimitedTariffs_list', $this->User->UnlimitedTariff->find('list', array('fields'=>array('UnlimitedTariff.name'))));
            $this->set('Streets_list', $this->User->Street->find('list', array('fields'=>array('Street.name'))));
            $this->set('UserGroups_list', $this->User->find('list', array('fields'=>array('User.login'), 'conditions' => array('is_group' => 1))));

            if ($this->data['User']['speed_type']==1) $speed_types['1']="checked=\"checked\""; else $speed_types['1']="";
            if ($this->data['User']['speed_type']==2) $speed_types['2']="checked=\"checked\""; else $speed_types['2']="";
            if ($this->data['User']['speed_type']==3) $speed_types['3']="checked=\"checked\""; else $speed_types['3']="";
            if ($speed_types['3']=="" && $speed_types['2']=="") $speed_types['1']="checked=\"checked\"";
            $this->set('Speed_types', $speed_types);

            if ($this->data['User']['is_group']==true) $is_group_id['2']="checked=\"checked\""; else $is_group_id['2']="";
            if ($this->data['User']['is_group']==false && $this->data['User']['group_id']!=0) $is_group_id['1']="checked=\"checked\""; else $is_group_id['1']="";
            if ($this->data['User']['is_group']==false && $this->data['User']['group_id']==0) $is_group_id['3']="checked=\"checked\""; else $is_group_id['3']="";
            if ($is_group_id['1']=="" && $is_group_id['2']=="") $is_group_id['3']="checked=\"checked\"";
            $this->set('is_group_id', $is_group_id);

        }
        else {
            if ($this->data['User']['is_group']=="link_to") {
                $this->data['User']['is_group']=false;
                $this->data['User']['balance']=0;
            } else $this->data['User']['group_id']=0;
            
            $this->loadModel('UnlimitedTariff');
            $this->UnlimitedTariff->id=$this->data['User']['unlimited_tariff_id'];
            $unlimited_tariff=$this->UnlimitedTariff->find('first');

            if ($this->data['User']['speed_type']=='1') {
                $this->data['User']['upload_speed']=0;
                $this->data['User']['download_speed']=0;
                $this->data['User']['total_speed']=0;
            } elseif ($this->data['User']['speed_type']=='2') {
                $this->data['User']['total_speed']=0;
            } elseif ($this->data['User']['speed_type']=='3') {
                $this->data['User']['upload_speed']=0;
                $this->data['User']['download_speed']=0;
            }

            $user=$this->User->find('first');

            App::import('Vendor', 'mikrotik');
            $server=new Server();
            $shell=$server->connect();
            $server->id=$this->User->id;
            $server->local_ip=$this->data['User']['local_ip'];
            $server->vpn_ip=$this->data['User']['vpn_ip'];
            $server->mac=$this->data['User']['mac'];
            $server->upload_speed=$user['UnlimitedTariff']['upload_speed'];
            $server->download_speed=$user['UnlimitedTariff']['download_speed'];
            $server->login=$this->data['User']['login'];
            $server->password=$this->data['User']['password'];
            if ($this->data['User']['speed_type']=="1") {
                $server->upload_speed=$user['UnlimitedTariff']['upload_speed'];
                $server->download_speed=$user['UnlimitedTariff']['download_speed'];
            } elseif ($this->data['User']['speed_type']=="2") {
                $server->upload_speed=$this->data['User']['upload_speed'];
                $server->download_speed=$this->data['User']['download_speed'];
            } elseif ($this->data['User']['speed_type']=="3") {
                $server->total_speed=$user['User']['total_speed'];
            }
            $server->speed_type=$this->data['User']['speed_type'];

            $server->editUser();
            $server->doCommands($shell);

            $this->data['User']['hashedPassword']=$this->Auth->password($this->data['User']['password']);
            if ($user['User']['role']=='') $this->data['User']['role']='user';
            $this->User->save($this->data);
            $this->Session->setFlash("Учётная запись пользователя была успешно изменена");
            $this->redirect($this->referer());
        }
    }

    function edit_personal_data($id=null) {
        if (!empty($this->data)) {
            $this->User->id=$id;
            $this->User->save($this->data);
            $this->redirect($this->referer());
        }
    }

    function delete($id=null) {
        $this->User->delete($id);
        App::import('Vendor', 'mikrotik');
        $server=new Server();
        $shell=$server->connect();
        $server->id=$id;
        $server->deleteUser();
        $server->doCommands($shell);
        $this->Session->setFlash("Пользователь был успешно удалён");
        $this->redirect($this->referer());
    }

    function stat() {
        $this->set('menu_selected_item', 'stat');
        $this->User->id=$this->Auth->user('id');
        $this->set('user', $this->User->find('first'));
    }

    function get_credit($id=null) {
        $this->User->id=$id;
        $user=$this->User->find('first');
        if ($user['User']['credit_balance']==0) {
            $credit_sum=$user['UnlimitedTariff']['value']*3;
            $this->data['User']['credit_balance']=$credit_sum;
            $this->data['User']['balance']=$user['User']['balance']+$credit_sum;
            $this->data['User']['blocked']=false;
            $this->User->save($this->data);

            if ($user['User']['blocked']==true ) {
                App::import('Vendor', 'mikrotik');
                $server=new Server();
                $shell=$server->connect();
                $server->id=$user['User']['id'];
                $server->enableUser();
                $server->doCommands($shell);
            }

            $this->Session->setFlash('Кредит, необходимый для 3-х дней работы был Вами успешно получен!');
        } else $this->Session->setFlash('Извините, но у Вас есть не закрыт предыдущий кредит!');
        $this->redirect($this->referer());
    }

    function take_payment() {
        if ($_SERVER['REMOTE_ADDR']=='10.0.10.1') {
            $users=$this->User->find('all');
            foreach ($users as $user) {
                if ($user['User']['blocked']==false) {
                    if ($user['User']['balance']>=$user['UnlimitedTariff']['value']) {
                        $user['User']['balance']=$user['User']['balance']-$user['UnlimitedTariff']['value'];
                        $this->User->save($user);
                    }
                    if ($user['User']['balance']<$user['UnlimitedTariff']['value']) {
                        App::import('Vendor', 'mikrotik');
                        $server=new Server();
                        $shell=$server->connect();
                        $server->id=$user['User']['id'];
                        $server->disableUser();
                        $server->doCommands($shell);
                        $deactivatedUsers[]=$user['User']['real_name'];
                        $user['User']['blocked']=true;
                        $this->User->save($user);
                    }
                }
            }
            if (isset($deactivatedUsers)) $this->set('deactivatedUsers', $deactivatedUsers);
        }
    }

    function isAuthorized() {
        $admin_methods=array('new_sub_admin', 'cancel_sub_admin');
        $sub_admin_methods=array('index', 'add', 'edit', 'plus_balance', 'delete');
        $users_methods=array('stat');
        if (in_array($this->action, $admin_methods) && $this->Auth->user('role')!='admin') $this->redirect(array('controller'=>'users', 'action'=>'login'));
        elseif (in_array($this->action, $sub_admin_methods) && $this->Auth->user('role')!='sub_admin' && $this->Auth->user('role')!='admin') $this->redirect(array('controller'=>'users', 'action'=>'login'));
        elseif (in_array($this->action, $users_methods) && $this->Auth->user('role')!='admin' && $this->Auth->user('role')!='user' && $this->Auth->user('role')!='sub_admin') $this->redirect(array('controller'=>'users', 'action'=>'login'));
        else return true;
    }

    function new_sub_admin() {
        if (!empty($this->data)) {
            $this->data['User']['role']='sub_admin';
            $this->User->save($this->data);
            $this->redirect($this->referer());
        }
    }

    function cancel_sub_admin() {
        if (!empty($this->data)) {
            $this->data['User']['role']='user';
            $this->User->save($this->data);
            $this->redirect($this->referer());
        }
    }

    function login() {
        if ($this->Auth->user('role')=='user') $this->redirect(array('action'=>'stat'));
        elseif ($this->Auth->user('role')=='admin') $this->redirect(array('action'=>'index'));
        elseif ($this->Auth->user('role')=='sub_admin') $this->redirect(array('action'=>'index'));
    }

    function logout() {
        $this->redirect($this->Auth->logout());
    }
}
?>
