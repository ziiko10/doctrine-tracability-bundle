<?php

namespace DctT\TracabilityBundle\EventListener;

use DateTime;
use App\Entity\User;
use Doctrine\ORM\Events;
use Symfony\Component\Yaml\Yaml;
use Doctrine\Common\EventSubscriber;
use Symfony\Component\Security\Core\Security;
use DctT\TracabilityBundle\Entity\Tracability;
use DctT\TracabilityBundle\Annotation\Tracable;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class TracabilitySubscriber implements EventSubscriber
{
    private $tokenStorage;

    private $container;

    public $deletedEntity;

    public function __construct(TokenStorageInterface $tokenStorage, ContainerInterface $container){
        $this->tokenStorage = $tokenStorage;
        $this->container = $container;
    }
    
    public function getSubscribedEvents()
    {
        return[
            Events::postPersist,
            Events::postUpdate,
            Events::postRemove,
            Events:: preRemove,
        ];
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $this->deletedEntity = $args->getObject()->getId();
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $this->saveTrace($args, 'persist');
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->saveTrace($args, 'update');
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        $this->saveTrace($args, 'remove');
    }

    private function saveTrace(LifecycleEventArgs $args, $action){
        if(!in_array($action, $this->getConfigActions())){
            return;
        }

        $em = $args->getObjectManager();
        
        $resource = $args->getObject();
        $reflectionClass = new \ReflectionClass(get_class($resource));
        $reader = new AnnotationReader();
        $isTraceable = $reader->getClassAnnotation($reflectionClass, Tracable::class);
        
        if($isTraceable instanceof Tracable){
            $propertyAccessor = PropertyAccess::createPropertyAccessor();
            $traceability = new Tracability();
            $token = $this->tokenStorage->getToken();
            $ressourceIdentifier = $resource->getId() ? $resource->getId(): $this->deletedEntity;
            $user = $token && $token->getUser() && $token->getUser() instanceof User ? $propertyAccessor->getValue($token->getUser(), $this->container->getParameter('tracability.user_identifier')) : $token->getUser(); 
            $traceability
                ->setUser($user)
                ->setAction($action)
                ->setCreatedAt(new DateTime())
                ->setResource($isTraceable->resourceName .'-'. $ressourceIdentifier);

            $em->persist($traceability);
            $em->flush();
        }
    }

    /**
     * Return an array contains enabled actions in config file (tracability.yaml)
     *
     * @return array
     */
    public function getConfigActions(): array{
        $actions = $this->container->getParameter('tracability.actions');
        if($actions){
            $existActions = [];
            foreach ($actions as $key => $action) {
                if($action){
                    $existActions[] = $key;
                }
            }
            return $existActions;
        }
    }

}