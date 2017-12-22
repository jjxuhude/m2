<?php
namespace Jjxuhuade\Smtp\Model;

use Magento\Framework\App\ObjectManager;
use Symfony\Component\DependencyInjection\Reference;

class Transport extends \Magento\Framework\Mail\Transport
{

    
    private function getFrom(){
        $class=new \ReflectionClass($this->_message);
        $_headers=$class->getProperty('_headers');
        $_headers->setAccessible(true);
        $from=$_headers->getValue($this->_message)['From'][0];
        preg_match('/(.+?)\<(.+)\>/', $from,$match);
        return [
            'email' => trim($match[2]),
            'name' => trim($match[1])
        ];
    }
    
    private function getAccount($connection){
        $class=new \ReflectionClass($connection);
        $config=$class->getProperty('_config');
        $config->setAccessible(true);
        $username=$config->getValue($connection)['username'];
        return $username;
    }
    
    /**
     * Send a mail using this transport
     *
     * @return void
     * @throws \Magento\Framework\Exception\MailException
     */
    public function sendMessage()
    {
            $from=$this->getFrom();
            $connection=ObjectManager::getInstance()->get('Jjxuhuade\Smtp\Model\connection');
            $init_mail = $connection->getConnection();
            $account=$this->getAccount($init_mail);
            $toMail=$this->_message->getRecipients();
            $title=$this->_message->getSubject();
            $html=$this->_message->getBody()->getRawContent();
            $mail = new \Zend_Mail('UTF-8');
            $result = $mail->addTo($toMail) 
            ->setFrom($from['email'],$from['name']) 
            ->setSubject('=?utf-8?B?' . base64_encode($title) . '?=') 
            ->setBodyHtml($html)
            ->send($init_mail); 
            
            $this->log($account,$toMail,$title,$html);
            return $result;
    }
    
    public function log($fromMail,$toMail,$title,$html){
        $logModel = ObjectManager::getInstance()->get('Jjxuhuade\Smtp\Model\Log');
        $logModel->setData([
            'email_from' => $fromMail,
            'email_to' => join(',',$toMail),
            'subject' => $title,
            'email_body' => $html
        ])->save();
    }
    
    
    
}

