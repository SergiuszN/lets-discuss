<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Appraise
 *
 * @package AppBundle\Entity
 * @ORM\Entity()
 * @ORM\Table(name="appraise")
 */
class Appraise
{
    /**
     * @var int
     *
     * @ORM\Column(name="in", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="rate", type="integer", nullable=false)
     */
    private $rate;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * @var CompanyWorker
     *
     * Many Appraise one CompanyWorker
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\CompanyWorker", inversedBy="appraisals")
     * @ORM\JoinColumn(name="company_worker", referencedColumnName="id")
     */
    private $companyWorker;

    /***********************/

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return CompanyWorker
     */
    public function getCompanyWorker()
    {
        return $this->companyWorker;
    }

    /**
     * @return int
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @param CompanyWorker $companyWorker
     */
    public function setCompanyWorker($companyWorker)
    {
        $this->companyWorker = $companyWorker;
    }

    /**
     * @param int $rate
     */
    public function setRate($rate)
    {
        $this->rate = $rate;
    }
}