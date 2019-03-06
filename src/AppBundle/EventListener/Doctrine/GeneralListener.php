<?php 
namespace AppBundle\EventListener\Doctrine;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;


use AppBundle\Entity\User;
use AppBundle\Entity\Role;
use AppBundle\Entity\UserRole;


class GeneralListener{

    public function prePersist(LifecycleEventArgs $args){
        $entity = $args->getEntity();
        $em = $args->getEntityManager();
        
        if ($entity instanceof User){
            $rep = $em->getRepository(Role::class);
            $role = null;

            if($entity->getUserType()){
                if($entity->getUserType()->getSlug() == "clinic"){
                    $role = "ROLE_CLINIC";
                }
                else if($entity->getUserType()->getSlug() == "hospital"){
                    $role = "ROLE_HOSPITAL";
                }
                else if($entity->getUserType()->getSlug() == "patient"){
                    $role = "ROLE_PATIENT";
                }
                else if($entity->getUserType()->getSlug() == "doctor"){
                    $role = "ROLE_DOCTOR";
                }
            }
            

            $entity->setUsername(implode(" ",[$entity->getFirstName(),$entity->getLastname()]));
            $entity->setPhone(preg_replace("# #", "", $entity->getPhone()));
           
            if($role){
                if(($role = $rep->findOneBy(["label"=>$role]))){
                    $userrole = new UserRole();
                    $userrole->setUser($entity);
                    $userrole->setRole($role);
                    $em->persist($userrole);
                }
            }
        }


    }

    public function preRemove(LifecycleEventArgs $args){
        $entity = $args->getEntity();

        
    }
}