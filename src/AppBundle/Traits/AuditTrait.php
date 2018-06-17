<?php

namespace AppBundle\Traits;

use AppBundle\Entity\Audit;
use Doctrine\Bundle\DoctrineBundle\Registry;

trait AuditTrait
{
    /**
     * @param array $data
     * @return void
     */
    public function saveAudit($data)
    {
        /** @var Registry $doctrine */
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();

        $audit = new Audit();
        $audit->setDate(new \DateTime());
        $audit->setUser($this->getUser());
        $audit->setAction($this->container->get('request_stack')->getCurrentRequest()->attributes->get('_controller'));
        $audit->setIp($this->get('request_stack')->getCurrentRequest()->getClientIp());
        $audit->setParameters(json_encode($data));

        $em->persist($audit);
        $em->flush();
    }
}