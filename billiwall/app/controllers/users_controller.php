<?
class UsersController extends AppController {
    var $name='Users';
    var $helpers = array('Html', 'Javascript', 'Ajax');
    var $components = array( 'RequestHandler' );

    function beforeFilter() {
	$this->Auth->allow('*');
	//if ($this->Auth->user('role')!='admin') $this->Auth->deny('index', 'add', 'edit', 'plus_balance', 'delete');
	parent::beforeFilter();
    }

    function isAuthorized() {

    }


    function login() {
    }

    function logout() {
	$this->redirect($this->Auth->logout());
    }

    function login_router() {
	if ($this->Auth->user('role')=='user') $this->redirect(array('controller' => 'users', 'action'=>'stat'));
	elseif ($this->Auth->user('role')=='admin') $this->redirect(array('controller' => 'Users', 'action'=>'index'));
    }

    function index() {
    //echo $this->Auth->user('role');
	$this->set('users', $this->User->find('all'));
	$this->set('menu_selected_item', 'users');
	//Using UnlimitedTariff model to generate it's list
	$this->loadModel('UnlimitedTariff');
	$this->set('UnlimitedTariffs_list', $this->UnlimitedTariff->find('list', array('fields'=>array('UnlimitedTariff.name'))));
    }

    function add() {
	if (!empty($this->data)) {
	    if ($this->data['User']['balance']==0) $this->data['User']['blocked']=true;
	    $this->data['User']['role']='user';
	    $this->data['User']['hashedPassword']=$this->Auth->password($this->data['User']['password']);
	    $this->User->save($this->data);
	    $user=$this->User->find('first');

	    App::import('Vendor', 'mikrotik');
	    $server=new Server();
	    $shell=$server->connect();
	    $server->addUser($this->User->id, $this->data['User']['local_ip'], $this->data['User']['vpn_ip'], $this->data['User']['mac'], $user['UnlimitedTariff']['upload_speed'], $user['UnlimitedTariff']['download_speed'], $this->data['User']['login'], $this->data['User']['password']);
	    if ($this->data['User']['balance']!=0) $server->enableUser($this->User->id);

	    $this->Session->setFlash("Поздравляем с новым пользователем! ;)");
	    $server->doCommands($shell);
	    $this->redirect($this->referer());
	}
    }

    function plus_balance($id=null) {
	if (!empty($this->data)) {
	    $this->User->id=$this->data['User']['id'];
	    $user=$this->User->find('first');
	    $sum=$this->data['User']['balance'];
	    $newBalance=$user['User']['balance']+$sum;
	    $this->data['User']['balance']=$newBalance;
	    if ($user['User']['blocked']==true)
		if ($newBalance>=$user['UnlimitedTariff']['value']) {
		    App::import('Vendor', 'mikrotik');
		    $server=new Server();
		    $shell=$server->connect();
		    $server->enableUser($user['User']['id']);
		    $server->doCommands($shell);
		    $this->data['User']['blocked']=false;
		}
	    $this->User->save($this->data);
	    $this->Session->setFlash("Баланс пользователя ".$user['User']['real_name']." был увеличен на ".$sum." грн.");
	    $this->redirect($this->referer());
	}

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
			$server->disableUser($user['User']['id']);
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

    function edit($id=null) {
	$this->User->id=$id;
	if (empty($this->data)) {
	    $this->loadModel('UnlimitedTariff');
	    $this->set('UnlimitedTariffs_list', $this->UnlimitedTariff->find('list', array('fields'=>array('UnlimitedTariff.name'))));
	    $this->data=$this->User->read();
	    $this->set('SelectedUnlimitedTariff', $this->data['User']['unlimited_tariff_id']);
	}
	else {
	    App::import('Vendor', 'mikrotik');
	    $server=new Server();
	    $shell=$server->connect();

	    $this->loadModel('UnlimitedTariff');
	    $this->UnlimitedTariff->id=$this->data['User']['unlimited_tariff_id'];
	    $unlimited_tariff=$this->UnlimitedTariff->find('first');

	    $user=$this->User->find('first');
	    $server->editUser($this->User->id, $this->data['User']['local_ip'], $this->data['User']['vpn_ip'], $this->data['User']['mac'], $unlimited_tariff['UnlimitedTariff']['upload_speed'], $unlimited_tariff['UnlimitedTariff']['download_speed'], $this->data['User']['login'], $this->data['User']['password']);
	    if ($this->data['User']['balance']>=$user['UnlimitedTariff']['value']) {
		$server->enableUser($user['User']['id']);
		$this->data['User']['blocked']=false;
	    } else {
		$this->data['User']['blocked']=true;
		$server->disableUser($id);
	    }
	    $server->doCommands($shell);
	    $this->data['User']['hashedPassword']=$this->Auth->password($this->data['User']['password']);
	    if ($user['User']['role']=='') $this->data['User']['role']='user';
	    $this->User->save($this->data);
	    $this->Session->setFlash("Учётная запись пользователя ".$this->data['User']['real_name']." была успешно изменена");
	    $this->redirect($this->referer());
	}
    }

    function delete($id=null) {
	$this->User->delete($id);
	App::import('Vendor', 'mikrotik');
	$server=new Server();
	$shell=$server->connect();
	$server->deleteUser($id);
	$server->doCommands($shell);
	$this->Session->setFlash("Пользователь был успешно удалён");
	$this->redirect($this->referer());
    }

    function stat() {
    }

}	

?>