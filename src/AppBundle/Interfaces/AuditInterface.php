<?php

namespace AppBundle\Interfaces;

interface AuditInterface
{
    /**
     * @param array $data
     * @return void
     */
    public function saveAudit($data);
}