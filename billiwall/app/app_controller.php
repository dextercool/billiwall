<?
class AppController extends Controller {
    var $components = array('Auth');

 function beforeFilter() {	
	$this->Auth->fields = array(
	    'username' => 'login',
	    'password' => 'hashedPassword'
	);
	$this->Auth->userModel = 'User';	
	$this->Auth->loginRedirect = array('controller' => 'users', 'action' => 'login_router');
	$this->Auth->loginError = "К сожалению, Вы ошиблись при вводе логина или пароля";
	$this->Auth->authError = "Извините, но для того, чтобы находиться здесь нужно авторизироваться ;)";
	$this->Auth->authorize = 'controller';
    }    
}
?>