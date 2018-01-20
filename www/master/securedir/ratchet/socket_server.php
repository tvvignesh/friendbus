<?php
$noecho="yes";
$_SERVER['DOCUMENT_ROOT']="/var/www/html";
require_once $_SERVER['DOCUMENT_ROOT'].'/filemaster.php';
require_once ROOT_SECURE. '/ratchet/vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {
    public $clients;
    public $uidlink;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->uidlink=Array();
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        
        
        $logobj=new ta_logs();
        $comobj=new ta_communication();
        $comobj->process_message($from,$msg,$this->clients,$this->uidlink);
        
        
        
        //foreach ($this->clients as $client) {
            //if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
               // $client->send($msg);
            //}
       // }
    }

    public function onClose(ConnectionInterface $conn) {
    	
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}

?>