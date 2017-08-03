<?php

namespace AgileKernelBundle\EventListener;

use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use AgileKernelBundle\Model\HistorisableInterface;
use AgileKernelBundle\Model\ObjectHistoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class HistorisableListener
 *
 * @package AgileKernelBundle\EventListener
 */
class HistorisableListener implements EventSubscriber
{
    /**
     * @var string
     */
    protected $objectHistoryClass;

    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * ObjectHistoryListener constructor.
     *
     * @param string                $objectHistoryClass
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct($objectHistoryClass, TokenStorageInterface $tokenStorage)
    {
        $this->objectHistoryClass = $objectHistoryClass;
        $this->tokenStorage       = $tokenStorage;
    }

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            Events::postPersist => 'postPersist',
        ];
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity        = $args->getEntity();
        $entityManager = $args->getEntityManager();

        if ($entity instanceof HistorisableInterface) {
            $user = null;
            if(null !== $token = $this->tokenStorage->getToken()) {
                $user = $token->getUser();
            }

//            dump($args->getObject()); exit;
//
//
//            /** @var ObjectHistoryInterface $objectHistory */
//            $objectHistory = new $this->objectHistoryClass();
//            $objectHistory
//                ->setActor(null)
//                ->setObject($entity->getObjectReference())
//                ->setAction('create')
//                ->setField(null);
//
//            $entityManager->persist($objectHistory);
//            $entityManager->flush();
        }
    }
}
