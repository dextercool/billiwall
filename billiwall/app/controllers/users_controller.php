<?
class UsersController extends AppController {
	var $name='Users';	
	var $helpers = array('Html', 'Javascript', 'Ajax'); 
	var $components = array( 'RequestHandler' );
	
	function index() {
		$this->set('users', $this->User->find('all'));
		$this->set('menu_selected_item', 'users');
		//Using UnlimitedTariff model to generate it's list
		$this->loadModel('UnlimitedTariff');
		$this->set('UnlimitedTariffs_list', $this->UnlimitedTariff->find('list', array('fields'=>array('UnlimitedTariff.name'))));
	}
	
	function add() {
		if (!empty($this->data)) {
			$this->User->save($this->data);			
                        App::import('Vendor', 'mikrotik');
                        $server=new Server();
                        $shell=$server->connect();
                        $server->addUser($this->User->id, $this->data['User']['local_ip']);                        
                        if ($this->data['User']['balance']==0) $server->disableUser($this->User->id);
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
            $users=$this->User->find('all');
            foreach ($users as $user) {
                if ($user['User']['blocked']==false) {
                    if ($user['User']['balance']>=$user['UnlimitedTariff']['value']){
                        $user['User']['balance']=$user['User']['balance']-$user['UnlimitedTariff']['value'];
                        $this->User->save($user);
                    }
                    if ($user['User']['balance']<$user['UnlimitedTariff']['value']){
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


        function test() {
            App::import('Vendor', 'mikrotik');
            $server=new Server();
            $shell=$server->connect();            
            ssh2_exec($shell, "ip firewall address-list add address=10.0.10.100 list=vpn-access comment=30; ".'ip firewall address-list disable "22"');
            
        }
	
}	

?>