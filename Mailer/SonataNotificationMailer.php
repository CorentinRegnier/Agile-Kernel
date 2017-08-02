<?php

namespace AgileKernelBundle\Mailer;

use Sonata\NotificationBundle\Backend\BackendInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Translation\TranslatorInterface;
use \Twig_Environment;

/**
 * Class SonataNotificationMailer
 */
class SonataNotificationMailer extends AbstractMailer
{
    /**
     * @var BackendInterface
     */
    private $notificationProducer;

    /**
     * @var string
     */
    private $messageQueueName;

    /**
     * @param Twig_Environment         $twig
     * @param TranslatorInterface      $translator
     * @param SecurityContextInterface $securityContext
     * @param                          $fromEmail
     * @param BackendInterface         $notificationProducer
     * @param                          $messageQueueName
     */
    public function __construct(
        Twig_Environment $twig,
        TranslatorInterface $translator,
        SecurityContextInterface $securityContext,
        $fromEmail,
        BackendInterface $notificationProducer,
        $messageQueueName
    ) {
        parent::__construct($twig, $translator, $securityContext, $fromEmail);
        $this->notificationProducer = $notificationProducer;
        $this->messageQueueName     = $messageQueueName;
    }

    /**
     * @param       $subject
     * @param       $htmlBody
     * @param       $textBody
     * @param       $fromEmail
     * @param       $toEmail
     * @param array $attachments
     * @param array $tags
     */
    public function sendMessage(
        $subject,
        $htmlBody,
        $textBody,
        $fromEmail,
        $toEmail,
        array $attachments = [],
        array $tags = []
    ) {
        $this->notificationProducer->createAndPublish($this->messageQueueName, [
            $subject,
            $htmlBody,
            $textBody,
            $fromEmail,
            $toEmail,
            $attachments,
            $tags,
        ]);
    }
}
