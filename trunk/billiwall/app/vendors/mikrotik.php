<?php
class Server {
    var $host="10.0.10.1";
    var $login_access="billing";
    var $password_access="101";
    var $command="";

    var $id;
    var $local_ip;
    var $vpn_ip;
    var $mac;
    var $upload_speed;
    var $download_speed;
    var $login;
    var $password;

    function connect() {
        if (!function_exists("ssh2_connect")) die("function ssh2_connect doesn't exist");
        $methods = array ( 'kex' => 'diffie-hellman-group1-sha1' );
        $shell = ssh2_connect($this->host, 22, $methods);
        ssh2_auth_password($shell, $this->login_access, $this->password_access) or die("connect error!");
//	$shell=1;
        return $shell;
    }

    function sendCommand($shell, $command) {
        $stream = ssh2_exec($shell, $command);
        stream_set_blocking( $stream, true );
        $data = "";
        while( $buffer = fread($stream, 4096) ) {
            $data .= $buffer;
        }
        fclose($stream);
        return $data;
    }

    function addUser() {
        $this->command.='ip firewall address-list add address='.$this->vpn_ip.' list=vpn-access comment='.$this->id.'; ';
	$this->command.='queue simple add max-limit='.$this->upload_speed.'000/'.$this->download_speed.'000 name='.$this->id.' target-addresses='.$this->vpn_ip.'/32; ';
	$this->command.='ppp secret add comment="'.$this->id.'" name='.$this->login.' password='.$this->password.' profile=global-vpn remote-address='.$this->vpn_ip.' service=pppoe; ';
	$this->command.='ip dhcp-server lease add address='.$this->local_ip.' comment="'.$this->id.'" disabled=no mac-address='.$this->mac.'; ';
    }

    function enableUser() {
        $this->command.='ip firewall address-list disable "'.$this->id.'"; ';
        $this->command.='ppp secret enable "'.$this->id.'"; ';
    }

    function disableUser() {
        $this->command.='ip firewall address-list enable "'.$this->id.'"; ';
        $this->command.='ppp secret disable "'.$this->id.'"; ';        
    }

    function deleteUser() {
	$this->command.='ip firewall address-list remove "'.$this->id.'"; ';
	$this->command.='queue simple remove "'.$this->id.'"; ';
	$this->command.='ppp secret remove "'.$this->id.'"; ';
	$this->command.='ip dhcp-server lease remove "'.$this->id.'"; ';
    }

    function editUser() {
	$this->deleteUser();
	$this->addUser();
        $this->enableUser();
    }

    function changeUserSpeed () {
        $this->command.='queue simple remove "'.$this->id.'"; ';
	$this->command.='/queue simple add max-limit='.$this->upload_speed.'000/'.$this->download_speed.'000 name='.$this->id.' target-addresses='.$this->vpn_ip.'/32; ';
    }

    function doCommands($shell) {
        $this->sendCommand($shell, $this->command);
    }
}
?>
