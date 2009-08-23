<?php
class Server {
    var $host="10.100.15.13";
    var $login="billing";
    var $password="101";
    var $command="";

    function connect() {
        if (!function_exists("ssh2_connect")) die("function ssh2_connect doesn't exist");
        $methods = array ( 'kex' => 'diffie-hellman-group1-sha1' );
        $shell = ssh2_connect($this->host, 22, $methods);
        ssh2_auth_password($shell, $this->login, $this->password) or die("connect error");
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

    function addUser($id, $local_ip, $vpn_ip, $upload_speed, $download_speed, $login, $password) {
        $this->command.='ip firewall address-list add address='.$local_ip.' list=access comment='.$id.'; ';
	$this->command.='queue simple add max-limit='.$upload_speed.'000/'.$download_speed.'000 name='.$id.' target-addresses='.$vpn_ip.'/32; ';
	$this->command.='ppp secret add caller-id='.$local_ip.' comment="'.$id.'" name='.$login.' password='.$password.' profile=global-vpn remote-address='.$vpn_ip.' service=pptp; ';
    }

    function enableUser($id) {
        $this->command.='ip firewall address-list enable "'.$id.'"; ';
    }

    function disableUser($id) {
        $this->command.='ip firewall address-list disable "'.$id.'"; ';
    }

    function deleteUser($id) {
	$this->command.='ip firewall address-list remove "'.$id.'"; ';
	$this->command.='queue simple remove "'.$id.'"; ';
	$this->command.='ppp secret remove "'.$id.'"; ';
    }

    function doCommands($shell) {
        $this->sendCommand($shell, $this->command);
    }
}
?>
