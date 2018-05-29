<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class CompanyWorker
 *
 * @package AppBundle\Entity
 * @ORM\Entity()
 * @ORM\Table(name="company_worker")
 */
class CompanyWorker
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
     * @var string
     *
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", length=50, nullable=false)
     */
    private $surname;

    /**
     * @var Company
     *
     * Many CompanyWorker one Company
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Company", inversedBy="workers")
     * @ORM\JoinColumn(name="company", referencedColumnName="id")
     */
    private $company;

    /**
     * @var Appraise | ArrayCollection
     *
     * One CompanyWorker many Appraise
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Appraise", mappedBy="companyWorker")
     */
    private $appraisals;

    /****************************/

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @return Appraise | ArrayCollection
     */
    public function getAppraisals()
    {
        return $this->appraisals;
    }

    /**
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param Company $company
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }

    /**
     * @param string $surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    /**
     * Add appraise
     *
     * @param Appraise $appraise
     */
    public function addAppraise($appraise)
    {
        $this->appraisals[] = $appraise;
    }
}