<html>
 <head>
  <title>PHP-Test</title>
 </head>
 <body>
 <?php

 require_once "loxberry_io.php";
require_once "phpMQTT/phpMQTT.php";

// Get the MQTT Gateway connection details from LoxBerry
$creds = mqtt_connectiondetails();

// MQTT requires a unique client id
$client_id = uniqid(gethostname()."_client");

// Value we'd like to publish
$value = 12345;

// Be careful about the required namespace on inctancing new objects:
$mqtt = new Bluerhinos\phpMQTT($creds['brokerhost'],  $creds['brokerport'], $client_id);
    if( $mqtt->connect(true, NULL, $creds['brokeruser'], $creds['brokerpass'] ) ) {
        $mqtt->publish("this/is/my/topic", $value, 0, 1);
        echo "this/is/my/topic";

        $mqtt->close();
    } else {
        echo "MQTT connection failed";
    }

$mqtt->debug = true;

$topics['rtls/position/'] = array('qos' => 0, 'function' => 'procMsg');
$mqtt->subscribe($topics, 0);

while($mqtt->proc()) {

}

$mqtt->close();

function procMsg($topic, $msg){
		echo 'Msg Recieved: ' . date('r') . "\n";
		echo "Topic: {$topic}\n\n";
		echo "\t$msg\n\n";
}

 ?>
 </body>
</html>
