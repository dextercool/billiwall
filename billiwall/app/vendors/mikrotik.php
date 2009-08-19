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
        ssh2_auth_password($shell, $this->user, $this->password);
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

    function addUser($id, $local_ip) {
        $this->command.='ip firewall address-list add address='.$local_ip.' list=vpn-access comment='.$id.'; ';
    }

    function enableUser($id) {
        $this->command.='ip firewall address-list enable "'.$id.'"; ';
    }

    function disableUser($id) {
        $this->command.='ip firewall address-list disable "'.$id.'"; ';
    }

    function doCommands($shell) {
        $this->sendCommand($shell, $this->command);
    }
}
?>
