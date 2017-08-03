<?php

namespace AgileKernelBundle\Mailer;

use FOS\UserBundle\Model\UserInterface;

/**
 * Interface MailerInterface
 *
 * @package AgileKernelBundle\Mailer
 */
interface MailerInterface
{
    /**
     * @param string       $templateName
     * @param string|array $toEmail
     * @param array        $params
     * @param string       $fromEmail
     * @param string       $fromName
     * @param array        $attachments
     * @param string|null  $locale
     * @param array        $tags
     */
    public function send(
        $templateName,
        $toEmail,
        array $params = [],
        $fromEmail = null,
        $fromName = null,
        array $attachments = [],
        $locale = null,
        array $tags = []
    );

    /**
     * @param string        $templateName
     * @param array         $params
     * @param UserInterface $user
     * @param array|string  $fromEmail
     * @param string        $fromName
     * @param array         $attachments
     * @param array         $tags
     */
    public function sendToUser(
        $templateName,
        array $params = [],
        UserInterface $user = null,
        $fromEmail = null,
        $fromName = null,
        array $attachments = [],
        array $tags = []
    );

    /**
     * @param string $subject
     * @param string $htmlBody
     * @param string $textBody
     * @param string $fromEmail
     * @param string $fromName
     * @param string $toEmail
     * @param array  $attachments
     * @param array  $tags
     *
     * @return mixed
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
    );
}
