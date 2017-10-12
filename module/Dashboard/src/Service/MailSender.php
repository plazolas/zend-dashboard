<?php
namespace Dashboard\Service;

use Zend\Mail;
use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;

/**
 * This service class is used to deliver an E-mail message to recipient.
 */
class MailSender 
{
    /**
     * Sends the mail message.
     */
    public function sendMail($type, $sender, $recipient, $subject, $text)
    {


            // Create E-mail Message
            $mail = new Message();
            $mail->setBody($text);
            $mail->setFrom($sender);
            $mail->addTo($recipient);
            $mail->setSubject($subject);

            if ($type == 'Sendmail') {
                // Send E-mail message
                $transport = new Sendmail('-f' . $sender);
            } else {
                // Setup SMTP transport using PLAIN authentication
                $transport = new SmtpTransport();
                $options = new SmtpOptions(array(
                    'name' => 'smtp.gmail.com',
                    'host' => 'smtp.gmail.com',
                    'port' => 465,
                    'connection_class' => 'login',
                    'connection_config' => array(
                        'username' => 'plazolas@gmail.com',
                        'password' => 'y6u7i8o9',
                        'ssl' => 'ssl'
                    )
                ));
                $transport->setOptions($options);
            }
            try {
                $transport->send($mail);
            } catch ( \Exception $e) {
                throw new \Exception($e->getMessage());
            }
        return true;
    }
};


