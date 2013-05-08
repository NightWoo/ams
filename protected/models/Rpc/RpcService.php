<?php
/*
eg.
$rpc = new RpcService()
$ret = $rpc->openGate();

*/
class RpcService
{
	protected $host;
	protected $port;
	protected $retry = 2;
    public function __construct($name = 'rpc.gate') {
		$this->host = Yii::app()->params[$name]['host'];
		$this->port = Yii::app()->params[$name]['port'];
    }

	public function openGate() {
		$msg = 'Open';
		$timeout = 5;
		$socket = fsockopen($this->host, $this->port, $errno, $errstr, $timeout); 
		do  {
			fputs($socket, $msg);
			$ret = fgets($socket, 255);
			-- $this->retry;
		} while($ret === 'Tcp/Ip error' && $this->retry > 0);
		fclose($socket);


		if(trim($ret) === 'Opening Success') {
			return '驾驶员不要驾车在道杆下逗留，谨慎快速通过';
		}
		return $ret;
	}
}