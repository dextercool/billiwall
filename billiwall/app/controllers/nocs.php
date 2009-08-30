<?
class NocsController extends AppController {
    var $name='Nocs';
    var $uses=null;
    var $helpers = array('Html', 'Javascript', 'Ajax'); 

    function index() {
	$this->set('nocs', $this->User->find('all'));
    }
}
?>