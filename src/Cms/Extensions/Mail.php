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

    /**
     * @param bool $config
     * @return Mail
     */
    public static function ready($config = false)
    {
        $mail = new self();
        $mail->config = !$config ? App::$config['mail'] : $config;
        return $mail;
    }

    /**
     * @param $to
     * @param $title
     * @param $content
     * @return bool
     */
    public function getSend($to, $title, $content)
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
            return true;
        } catch (\Swift_ConnectionException $e) {
            $this->error = $e->getMessage();
            return false;
        }

    }
}