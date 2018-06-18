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
     * @ORM\Column(name="id", type="integer")
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
     * @ORM\Column(name="description", type="text", nullable=true)
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
     * Get Appraise description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get Appraise id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get Appraise date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Get Company Worker attached to Appraise
     *
     * @return CompanyWorker
     */
    public function getCompanyWorker()
    {
        return $this->companyWorker;
    }

    /**
     * Get rate of Appraise
     *
     * @return int
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Set Appraise description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Set Appraise date
     *
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * Set CompanyWorker attached to Appraise
     *
     * @param CompanyWorker $companyWorker
     */
    public function setCompanyWorker($companyWorker)
    {
        $this->companyWorker = $companyWorker;
    }

    /**
     * Set rate of Appraise
     *
     * @param int $rate
     */
    public function setRate($rate)
    {
        $this->rate = $rate;
    }
}