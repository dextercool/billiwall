<?
class User extends AppModel {
	var $name='User';
	var $belongsTo=array('UnlimitedTariff', 'Street');
}
?>