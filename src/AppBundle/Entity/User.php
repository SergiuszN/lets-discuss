<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as FosUser;
/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User extends FosUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Company
     *
     * Many User one Company
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Company", inversedBy="managers")
     * @ORM\JoinColumn(name="company", referencedColumnName="id")
     */
    private $company;

    /**
     * One User has Many Audits.
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Audit", mappedBy="user")
     */
    private $audits;

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->audits = new ArrayCollection();
    }

    /****************************/

    /**
     * Get User id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get User company
     *
     * @return Company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set User company
     *
     * @param Company $company
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }

    /**
     * Get User audits
     *
     * @return mixed
     */
    public function getAudits()
    {
        return $this->audits;
    }

    /**
     * Set User audits
     *
     * @param mixed $audits
     */
    public function setAudits($audits)
    {
        $this->audits = $audits;
    }
}