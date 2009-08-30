<?
class NocsController extends AppController {
    var $name='Nocs';
    var $uses=null;

    function index() {
	$this->set('nocs', $this->User->find('all'));
    }
}
?>