<?
class UnlimitedTariffsController extends AppController {
	var $name='UnlimitedTariffs';	
	var $helpers = array('Html', 'Javascript', 'Ajax'); 
	var $components = array( 'RequestHandler' );
	
	function index() {
		$this->set('unlimited_tariffs', $this->UnlimitedTariff->find('all'));
		$this->set('menu_selected_item', 'tariffs');
	}
	
	function edit($id=null) {		
		$this->set('menu_selected_item', 'tariffs');
		$this->UnlimitedTariff->id=$id;
		if(empty($this->data)) {
			$this->data=$this->UnlimitedTariff->read();			
		} else {
			$this->UnlimitedTariff->save($this->data);
			$this->Session->setFlash('Настройки тарифного плана успешно изменены');
			$this->redirect($this->referer());
		}				
	}	
	
	function add() {						
		$this->UnlimitedTariff->save($this->data);
		$this->Session->setFlash('Новый тарифный план был добавлен');
		$this->redirect($this->referer());
	}
	
	function delete($id=null) {
		$this->UnlimitedTariff->delete($id);
		$this->Session->setFlash('Тарифный план был удалён из базы');
		$this->redirect($this->referer());
	}
}	

?>