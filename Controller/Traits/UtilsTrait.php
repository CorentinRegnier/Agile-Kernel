<?php

namespace AgileKernelBundle\Controller\Traits;

use LogicException;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\AdvancedUserInterface as User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

trait UtilsTrait
{
    /**
     * @param bool $strict
     *
     * @return User
     * @throws AccessDeniedException
     */
    public function getUser($strict = true)
    {
        $user = $this->doGetUser();
        if ($strict && !$user instanceof User) {
            throw new AccessDeniedException('User must be logged in.');
        }

        return $user;
    }

    /**
     * @return User
     * @throws LogicException
     */
    protected function doGetUser()
    {
        if (!$this->has('security.token_storage')) {
            throw new LogicException('The SecurityBundle is not registered in your application.');
        }

        /** @var TokenStorageInterface $securityTokenStorage */
        $securityTokenStorage = $this->get('security.token_storage');
        if (null === $token = $securityTokenStorage->getToken()) {
            return null;
        }

        if (!is_object($user = $token->getUser())) {
            return null;
        }

        return $user;
    }

    /**
     * @param string $type
     * @param string $message
     * @param bool   $translate
     * @param array  $parameters
     * @param null   $translationDomain
     */
    public function addFlash($type, $message, $translate = true, array $parameters = [], $translationDomain = null)
    {
        if ($translate) {
            /** @var TranslatorInterface $translator */
            $translator = $this->get('translator');
            $message    = $translator->trans($message, $parameters, $translationDomain);
        }

        /** @var Session $session */
        $session = $this->get('session');
        $session->getFlashBag()->add($type, $message);
    }
}
