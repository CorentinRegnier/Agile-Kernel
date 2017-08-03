<?php

namespace AgileKernelBundle\Mailer;

use \Twig_Template;
use \Twig_Environment;
use \InvalidArgumentException;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class AbstractMailer
 *
 * @package AgileKernelBundle\Mailer
 */
abstract class AbstractMailer implements MailerInterface
{
    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * @var TokenStorageInterface
     */
    private $security;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var string
     */
    private $fromEmail;

    /**
     * @var string
     */
    private $fromName;

    /**
     * @param Twig_Environment      $twig
     * @param TranslatorInterface   $translator
     * @param TokenStorageInterface $security
     * @param string                $fromEmail
     * @param string                $fromName
     */
    public function __construct(
        Twig_Environment $twig,
        TranslatorInterface $translator,
        TokenStorageInterface $security,
        $fromEmail,
        $fromName
    ) {
        $this->twig       = $twig;
        $this->translator = $translator;
        $this->security   = $security;
        $this->fromEmail  = $fromEmail;
        $this->fromName   = $fromName;
    }

    /**
     * @param string       $templateName
     * @param array|string $toEmail
     * @param array        $params
     * @param null|string  $fromEmail
     * @param null|string  $fromName
     * @param array        $attachments
     * @param null|string  $locale
     * @param array        $tags
     *
     * @return int
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
    ) {
        if (null !== $locale && $this->translator) {
            $originalLocale = $this->translator->getLocale();
            $this->translator->setLocale($locale);
        }

        if (null === $fromEmail) {
            $fromEmail = $this->fromEmail;
        }

        if (null === $fromName) {
            $fromName = $this->fromName;
        }

        $context = $this->twig->mergeGlobals($params);
        /** @var Twig_Template $template */
        $template = $this->twig->loadTemplate($templateName);
        $subject  = $template->renderBlock('subject', $context);
        $textBody = $template->renderBlock('body_text', $context);
        $htmlBody = $template->renderBlock('body_html', $context);

        if (isset($originalLocale)) {
            $this->translator->setLocale($originalLocale);
        }

        return $this->sendMessage($subject, $htmlBody, $textBody, $fromEmail, $fromName, $toEmail, $attachments, $tags);
    }

    /**
     * @param string        $templateName
     * @param array         $params
     * @param UserInterface $user
     * @param null|string   $fromEmail
     * @param null|string   $fromName
     * @param array         $attachments
     * @param array         $tags
     *
     * @return int
     */
    public function sendToUser(
        $templateName,
        array $params = [],
        UserInterface $user = null,
        $fromEmail = null,
        $fromName = null,
        array $attachments = [],
        array $tags = []
    ) {
        if (null === $user) {
            $user = $this->getUser();
            if (!$user instanceof UserInterface) {
                throw new InvalidArgumentException('User is not defined for mail');
            }
        }

        if (method_exists($user, 'getLocale')) {
            $locale = $user->getLocale();
        } else {
            $locale = null;
        }

        return $this->send($templateName, $user->getEmail(), array_merge([
            'user' => $user,
        ], $params), $fromEmail, $fromName, $attachments, $locale, $tags);
    }

    /**
     * @return null|UserInterface
     */
    private function getUser()
    {
        if (null === $token = $this->security->getToken()) {
            return null;
        }

        if (!is_object($user = $token->getUser())) {
            return null;
        }

        return $user;
    }
}
