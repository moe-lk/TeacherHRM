<?php
function numberFormat($tp){
	if(strlen($tp)==10){
		return $tp;
	}elseif(strlen($tp)==9){
		return '0'.$tp;
	}
}
class sms {

    var $endPint;
    var $header;
    var $gmessage;
    var $grecepient;
    var $depCode;

    function __construct() {
        $this->endPint = 'http://lankagate.gov.lk:9080/services/GovSMSMTHandlerProxy.GovSMSMTHandlerProxyHttpSoap11Endpoint';
        /* $this->header = "<govsms:authData xmlns:govsms=\"http://govsms.icta.lk/\">\n
			<govsms:user>icta</govsms:user>\n
			<govsms:key>g0v5ms123</govsms:key>\n
		</govsms:authData>"; */
		$this->header = "<govsms:authData xmlns:govsms=\"http://govsms.icta.lk/\">\n
			<govsms:user>duminda</govsms:user>\n
			<govsms:key>1234</govsms:key>\n
		</govsms:authData>";
        
       // $this->depCode = "IctaTest";
		//$this->depCode = "NEMIS";
		$this->depCode = "TG";
    }

    public function sendsms($config = array()) {
        require_once('nusoap/nusoap.php'); //includes nusoap
        
        $this->gmessage = $config['message'];
        $this->grecepient = $config['recepient'];

        $errorflag = 0;
        $errormsg = '';
        $client = new nusoap_client($this->endPint, false, '', '', '', '');
        $err = $client->getError();
        if ($err) {
            $errorflag = 1;
            $errormsg = 'Error/sms/1';
        } else {

            $client->setUseCurl(0);
            $client->useHTTPPersistentConnection();
            $param = array(
                'v1:outSms' => $this->gmessage,
                'v1:recepient' => $this->grecepient,
                'v1:depCode' => $this->depCode,
                'v1:smscId' => "",
                'v1:billable' => "",
            );
            $params = array('v1:requestData' => $param);
            $result = $client->call('v1:SMSRequest', $params, '', 'sendSms', $this->header);            
           
            if ($client->fault) {
                $errorflag = 1;
                $errormsg = 'Error/sms/2';
            } else {
                $err = $client->getError();
                if ($err) {
                    $errorflag = 1;
                    $errormsg = 'Error/sms/3';
                }
            }
        }

        if ($errorflag == 1) {
            return array(0, $errormsg);
        } else {
            return array(1, $result);
        }
    }

    public function sendsmsbatch($dataarray) {        
       
        require_once('nusoap/nusoap.php'); //includes nusoap

        $errorflag = 0;
        $errormsg = '';
        $client = new nusoap_client($this->endPint, false, '', '', '', '');
        $err = $client->getError();
        if ($err) {
            $errorflag = 1;
            $errormsg = 'Error/sms/1';
        } else {

            $client->setUseCurl(0);
            $client->useHTTPPersistentConnection();

            $params ='';
           
            foreach ($dataarray as $row) {                  
                $newstring = '<v1:outSms>'.$row['sms_content'].'</v1:outSms>'.'<v1:recepient>'.$row['sms_recepient'].'</v1:recepient>'.'<v1:depCode>'.$this->depCode.'</v1:depCode><v1:smscId></v1:smscId><v1:billable></v1:billable>';              
                $params = $params.'<v1:requestData>'.$newstring.'</v1:requestData>';
            }
            
            $result = $client->call('v1:SMSRequest', $params, '', 'sendSms', $this->header);
            if ($client->fault) {
                $errorflag = 1;
                $errormsg = 'Error/sms/2';
            } else {
                $err = $client->getError();
                if ($err) {
                    $errorflag = 1;
                    $errormsg = 'Error/sms/3';
                }
            }
        }

        if ($errorflag == 1) {
            return array(0, $errormsg);
        } else {
            return array(1, $result);
        }
    }

}
