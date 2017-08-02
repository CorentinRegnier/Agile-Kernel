<?php

namespace AgileKernelBundle\Controller\Traits;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface as User;

trait UtilsTrait
{
    /**
     * @param bool $strict
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
     * @throws \LogicException
     */
    protected function doGetUser()
    {
        if(!$this->container->has('security.token_storage')){
            throw new \LogicException('The SecurityBundle is not registered in your application.');
        }

        if (null === $token = $this->container->get('security.token_storage')->getToken()) {
            return;
        }

        if (!is_object($user = $token->getUser())) {
            return;
        }

        return $user;
    }

    public function addFlash($type, $message, $translate = true, array $parameters = [], $translationDomain = null)
    {
        if ($translate) {
            $message = $this->container->get('translator')->trans($message, $parameters, $translationDomain);
        }
        $this->container->get('session')->getFlashBag()->add($type, $message);
    }
}
