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

    /*******************************/

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
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return CompanyWorker | ArrayCollection
     */
    public function getWorkers()
    {
        return $this->workers;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
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
}