<?php
class Server {
    var $host="10.0.10.1";
    var $login="billing";
    var $password="101";
    var $command="";

    function connect() {
        if (!function_exists("ssh2_connect")) die("function ssh2_connect doesn't exist");
        $methods = array ( 'kex' => 'diffie-hellman-group1-sha1' );
        $shell = ssh2_connect($this->host, 22, $methods);
        ssh2_auth_password($shell, $this->login, $this->password) or die("connect error");
	$shell=1;
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

    function addUser($id, $local_ip, $vpn_ip, $mac, $upload_speed, $download_speed, $login, $password) {
        $this->command.='ip firewall address-list add address='.$local_ip.' list=access comment='.$id.'; ';
	$this->command.='queue simple add max-limit='.$upload_speed.'000/'.$download_speed.'000 name='.$id.' target-addresses='.$vpn_ip.'/32; ';
	$this->command.='ppp secret add caller-id='.$local_ip.' comment="'.$id.'" name='.$login.' password='.$password.' profile=global-vpn remote-address='.$vpn_ip.' service=pptp; ';
	$this->command.='ip dhcp-server lease add address='.$local_ip.' comment="'.$id.'" disabled=no mac-address='.$mac.'; ';
	//$this->command.='ip arp add address='.$local_ip.' comment="'.$id.'" disabled=no interface=LAN mac-address='.$mac.'; ';
    }

    function enableUser($id) {
        $this->command.='ip firewall address-list disable "'.$id.'"; ';
    }

    function disableUser($id) {
        $this->command.='ip firewall address-list enable "'.$id.'"; ';	
    }

    function deleteUser($id) {
	$this->command.='ip firewall address-list remove "'.$id.'"; ';
	$this->command.='queue simple remove "'.$id.'"; ';
	$this->command.='ppp secret remove "'.$id.'"; ';
	//$this->command.='ip arp remove "'.$id.'"; ';
	$this->command.='ip dhcp-server lease remove "'.$id.'"; ';
    }

    function editUser($id, $local_ip, $vpn_ip, $mac, $upload_speed, $download_speed, $login, $password) {
	$this->deleteUser($id);
	$this->addUser($id, $local_ip, $vpn_ip, $mac, $upload_speed, $download_speed, $login, $password);
    }

    function changeUserSpeed ($id, $upload_speed, $download_speed, $vpn_ip) {
        $this->command.='queue simple remove "'.$id.'"; ';
	$this->command.='/queue simple add max-limit='.$upload_speed.'000/'.$download_speed.'000 name='.$id.' target-addresses='.$vpn_ip.'/32; ';
    }

    function doCommands($shell) {
        $this->sendCommand($shell, $this->command);
    }
}
?>
