<?php

namespace AgileKernelBundle\EventListener;

use AgileKernelBundle\Mailer\MailerInterface;
use Symfony\Component\Debug\ExceptionHandler;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

/**
 * Class ExceptionListener
 */
class ExceptionListener
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * @var string
     */
    private $developerEmail;

    /**
     * @var string
     */
    private $projectTitle;

    /**
     * @var string
     */
    private $projectUrl;

    /**
     * @var
     */
    private $env;

    /**
     * ExceptionListener constructor.
     *
     * @param MailerInterface $mailer
     * @param string          $developerEmail
     * @param string          $projectTitle
     * @param string          $projectUrl
     * @param string          $env
     */
    public function __construct(MailerInterface $mailer, $developerEmail, $projectTitle, $projectUrl, $env)
    {
        $this->mailer         = $mailer;
        $this->developerEmail = $developerEmail;
        $this->projectTitle   = $projectTitle;
        $this->projectUrl     = $projectUrl;
        $this->env            = $env;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        $request   = $event->getRequest();
        $uri       = $request->getRequestUri();
        $prefix    = $request->getScheme();
        $domain    = $request->getHost();

        $flatten = FlattenException::create($exception);
        $handler = new ExceptionHandler();

        if (in_array($flatten->getStatusCode(), [404], true)) {
            return;
        }

        $this->mailer->send(
            'AgileKernelBundle:mail:error.html.twig',
            $this->developerEmail,
            [
                'css'          => $handler->getStylesheet($flatten),
                'content'      => $handler->getContent($flatten),
                'project_name' => $this->projectTitle,
                'project_url'  => $prefix.'://'.$domain.$uri,
            ],
            'no-reply@'.$domain,
            $this->projectTitle,
            [],
            null,
            ['core:error']
        );
    }
}
