<?php

namespace Cms\Extensions;

use Brave\App;

class Mail
{
    /**
     * @var array
     */
    public $config = [
        //'host' => 'smtp.qq.com',
        //'username' => 'test@qq.com',
        //'password' => 'xxxxxx',
        //'name' => 'Brave',
        //'port' => '465',
        //'encryption' => 'ssl', //ssl、tls
    ];

    /**
     * @var
     */
    public $error;

    public function __construct($config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * @param $to
     * @param $title
     * @param $content
     * @return bool
     */
    public function send($to, $title, $content)
    {

        try {
            // message
            $message = \Swift_Message::newInstance();
            $message->setFrom($this->config['username'], $this->config['name']);
            $message->setTo($to);
            $message->setSubject($title);
            $message->setBody($content, 'text/html', 'utf-8');
            //$message->attach(\Swift_Attachment::fromPath('pic.jpg', 'image/jpeg')->setFilename('rename_pic.jpg'));

            //transport
            $transport = \Swift_SmtpTransport::newInstance($this->config['host'], $this->config['port']);
            $transport->setUsername($this->config['username']);
            $transport->setPassword($this->config['password']);
            if (isset($this->config['encryption'])) {
                $transport->setEncryption($this->config['encryption']);
            }

            //mailer
            $mailer = \Swift_Mailer::newInstance($transport);
            $mailer->send($message);

        } catch (\Swift_SwiftException $e) {
            $this->error = $e->getMessage();
        }

    }
}