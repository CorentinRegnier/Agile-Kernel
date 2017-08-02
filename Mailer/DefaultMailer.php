<?php

namespace AgileKernelBundle\Mailer;

use \Swift_Mailer;
use \Swift_Message;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Translation\TranslatorInterface;
use \Twig_Environment;
use \Swift_Attachment;

/**
 * Class DefaultMailer
 */
class DefaultMailer extends AbstractMailer
{
    /**
     * @var Swift_Mailer
     */
    private $swiftMailer;

    /**
     * @var string
     */
    private $mailerHost;

    /**
     * @var string
     */
    private $projectName;

    /**
     * @var string
     */
    private $hostEnv;

    /**
     * @param Twig_Environment      $twig
     * @param TranslatorInterface   $translator
     * @param TokenStorageInterface $security
     * @param string                $fromEmail
     * @param Swift_Mailer          $swiftMailer
     * @param string                $mailerHost
     * @param string                $projectName
     * @param string                $hostEnv
     */
    public function __construct(
        Twig_Environment $twig,
        TranslatorInterface $translator,
        TokenStorageInterface $security,
        $fromEmail,
        Swift_Mailer $swiftMailer,
        $mailerHost,
        $projectName,
        $hostEnv
    ) {
        parent::__construct($twig, $translator, $security, $fromEmail, '');
        $this->swiftMailer = $swiftMailer;
        $this->mailerHost  = $mailerHost;
        $this->projectName = $projectName;
        $this->hostEnv     = $hostEnv;
    }

    /**
     * @param string $subject
     * @param string $htmlBody
     * @param string $textBody
     * @param string $fromEmail
     * @param string $fromName
     * @param string $toEmail
     * @param array  $attachments
     * @param array  $tags
     */
    public function sendMessage(
        $subject,
        $htmlBody,
        $textBody,
        $fromEmail,
        $fromName,
        $toEmail,
        array $attachments = [],
        array $tags = []
    ) {
        /** @var Swift_Message $message */
        $message = Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($fromEmail, $fromName)
            ->setTo($toEmail);

        if ($htmlBody) {
            $message->setBody($htmlBody, 'text/html');
            $message->addPart($textBody, 'text/plain');
        } else {
            $message->setBody($textBody);
        }

        foreach ($attachments as $src) {
            $message->attach(Swift_Attachment::fromPath($src));
        }

        $this->handleTags($message, $tags);

        $this->swiftMailer->send($message);
    }

    /**
     * @param Swift_Message $message
     * @param array         $tags
     */
    private function handleTags(Swift_Message $message, array $tags)
    {
        if (in_array($this->mailerHost, [
            'smtp.sendgrid.net',
            'smtp.mandrillapp.com',
        ])) {
            $headers = $message->getHeaders();
            $tags    = array_map(function ($tag) {
                return implode(':', [
                    $this->projectName,
                    $this->hostEnv,
                    $tag,
                ]);
            }, $tags);

            if ('smtp.sendgrid.net' === $this->mailerHost) {
                $headers->addTextHeader('X-SMTPAPI', json_encode([
                    'category' => array_slice($tags, 0, 10),
                ]));
            } elseif ('smtp.mandrillapp.com' === $this->mailerHost) {
                $headers->addTextHeader('X-MC-Tags', implode(',', array_map(function ($tag) {
                    return ltrim(str_replace(',', '_', $tag), '_');
                }, $tags)));
            }
        }
    }
}
