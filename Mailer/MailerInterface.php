<?php

namespace AgileKernelBundle\Mailer;

/**
 * Interface MailerInterface
 */
interface MailerInterface
{
    /**
     * @param string       $templateName
     * @param string|array $toEmail
     * @param array        $params
     * @param string|array $fromEmail
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
     * @param string       $templateName
     * @param array        $params
     * @param User         $user
     * @param array|string $fromEmail
     * @param array        $attachments
     * @param array        $tags
     */
    public function sendToUser(
        $templateName,
        array $params = [],
        User $user = null,
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
