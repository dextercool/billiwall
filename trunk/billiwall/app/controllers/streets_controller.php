<?
class StreetsController extends AppController {
    var $name='Streets';
    var $helpers = array('Html', 'Javascript', 'Ajax');
    var $components = array( 'RequestHandler' );   

    function isAuthorized() {        
        if ($this->Auth->user('role')!='admin' && $this->Auth->user('role')!='sub_admin') $this->redirect(array('controller'=>'users', 'action'=>'login'));
        else return true;
    }

    function index() {
	$this->set('streets', $this->Street->find('all', array('order'=>'name')));
	$this->set('menu_selected_item', 'streets');
    }

    function edit($id=null) {
	$this->set('menu_selected_item', 'tariffs');
	$this->Street->id=$id;
	if(empty($this->data)) {
	    $this->data=$this->Street->read();
	} else {
	    $this->UnlimitedTariff->save($this->data);
	    $this->Session->setFlash('Дом успешно изменен');
	    $this->redirect($this->referer());
	}
    }

    function add() {
	$this->Street->save($this->data);
	$this->Session->setFlash('Новый дом успешно добавлен');
	$this->redirect($this->referer());
    }

    function delete($id=null) {
	$this->Street->delete($id);
	$this->Session->setFlash('Выбранный дом был удален');
	$this->redirect($this->referer());
    }
}	

?>