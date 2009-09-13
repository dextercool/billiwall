<?
class NocsController extends AppController {
    var $name='Nocs';
    public $uses=null;
    var $helpers = array('Html', 'Javascript', 'Ajax');

    function beforeFilter() {
	$this->Auth->allow('*');
	if ($this->Auth->user('role')!='admin') $this->Auth->deny('index');
	parent::beforeFilter();
    }

    function index() {
	$this->set('menu_selected_item', 'nocs');
    }
}
?>