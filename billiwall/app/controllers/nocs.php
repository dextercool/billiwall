<?
class NocsController extends AppController {
    var $name='Nocs';
    public $uses=null;
    var $helpers = array('Html', 'Javascript', 'Ajax'); 

    function index() {
	$this->set('menu_selected_item', 'nocs');
    }
}
?>