<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


/**
 * Class Company
 *
 * @package AppBundle\Entity
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CompanyRepository")
 * @ORM\Table(name="company")
 */
class Company
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
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", nullable=true)
     */
    private $description;

    /**
     * @var CompanyWorker | ArrayCollection
     *
     * One Company many CompanyWorkers
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\CompanyWorker", mappedBy="company")
     */
    private $workers;

    /**
     * @var User | ArrayCollection
     *
     * One Company many User
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\User", mappedBy="company")
     */
    private $managers;

    /*******************************/

    /**
     * Get Company id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get Company description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get Company name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get Company workers
     *
     * @return CompanyWorker | ArrayCollection
     */
    public function getWorkers()
    {
        return $this->workers;
    }

    /**
     * Get Company managers
     *
     * @return User | ArrayCollection
     */
    public function getManagers()
    {
        return $this->managers;
    }

    /**
     * Set Company name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Set Company description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Add CompanyWorker to Company
     *
     * @param CompanyWorker $worker
     */
    public function addWorker($worker)
    {
        $this->workers[] = $worker;
    }

    /**
     * Add Manager to Company
     *
     * @param User $manager
     */
    public function addManager($manager)
    {
        $this->managers[] = $manager;
    }
}