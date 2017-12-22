<?php
namespace Jjxuhuade\Smtp\Model;

use Magento\Framework\ObjectManagerInterface;
use Magento\Backend\App\Config;

class connection
{
    private $_objectManager;
    
    private $_config;
    
    private $_connection;
    
    
    function __construct(
        ObjectManagerInterface $_objectManager,
        Config                 $_config
    ){
        $this->_objectManager=$_objectManager;
        $this->_config = $_config;
        $this->_connection=$this->_config->getValue('jjxuhuade_smtp/configuration/connection');
    }
    
   public function getConnection(){
       if($this->_connection!=='disable'){
           return call_user_func_array([$this,$this->_connection],[]);
       }else{
           return false;
       }
   }
    
    
   private function google(){
        $host = 'smtp.gmail.com';
        $config = array(
            'ssl' => 'tls',
            'port' => 587,
            'auth' => 'login',
            'username' => $this->_config->getValue('jjxuhuade_smtp/google/email'),
            'password' => $this->_config->getValue('jjxuhuade_smtp/google/password')
        );
        $init_mail = new \Zend_Mail_Transport_Smtp($host,$config);
        return $init_mail;
    }
    
   private function smtp(){
        $host = $this->_config->getValue('jjxuhuade_smtp/smtp/host');
        $config = array(
            'ssl' => $this->_config->getValue('jjxuhuade_smtp/smtp/ssl'),
            'port' => $this->_config->getValue('jjxuhuade_smtp/smtp/port'),
            'auth' => $this->_config->getValue('jjxuhuade_smtp/smtp/authentication'),
            'username' => $this->_config->getValue('jjxuhuade_smtp/smtp/email'),
            'password' => $this->_config->getValue('jjxuhuade_smtp/smtp/password')
        );
        $init_mail = new \Zend_Mail_Transport_Smtp($host,$config);
        return $init_mail;
    }
    
    private function sendgrid(){
        $host = 'smtp.sendgrid.net';
        $config = array(
            'ssl' => 'tls',
            'port' => 587,
            'auth' => 'login',
            'username' => $this->_config->getValue('jjxuhuade_smtp/sendgrid/email'),
            'password' => $this->_config->getValue('jjxuhuade_smtp/sendgrid/password')
        );
        $init_mail = new \Zend_Mail_Transport_Smtp($host,$config);
        return $init_mail;
        
    }
}

