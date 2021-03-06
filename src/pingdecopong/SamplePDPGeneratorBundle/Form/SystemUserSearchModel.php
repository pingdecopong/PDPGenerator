<?php

namespace pingdecopong\SamplePDPGeneratorBundle\Form;

use Doctrine\ORM\Mapping as ORM;

/**
 * SystemUserSearchModel
 */
class SystemUserSearchModel
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $email;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $watertanks;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->watertanks = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return SystemUserSearchModel
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Add watertanks
     *
     * @param \pingdecopong\SamplePDPGeneratorBundle\Entity\Watertank $watertanks
     * @return SystemUserSearchModel
     */
    public function addWatertank(\pingdecopong\SamplePDPGeneratorBundle\Entity\Watertank $watertanks)
    {
        $this->watertanks[] = $watertanks;
    
        return $this;
    }

    /**
     * Remove watertanks
     *
     * @param \pingdecopong\SamplePDPGeneratorBundle\Entity\Watertank $watertanks
     */
    public function removeWatertank(\pingdecopong\SamplePDPGeneratorBundle\Entity\Watertank $watertanks)
    {
        $this->watertanks->removeElement($watertanks);
    }

    /**
     * Get watertanks
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getWatertanks()
    {
        return $this->watertanks;
    }
}
