<?php

namespace pingdecopong\SamplePDPGeneratorBundle\Form;

use Doctrine\ORM\Mapping as ORM;

/**
 * WatertankSearchModel
 */
class WatertankSearchModel
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $Name;

    /**
     * @var integer
     */
    private $SystemUserId;

    /**
     * @var integer
     */
    private $IntegerData;

    /**
     * @var boolean
     */
    private $DefTest1;

    /**
     * @var string
     */
    private $ColumnText;

    /**
     * @var \DateTime
     */
    private $ColumnDate;

    /**
     * @var \DateTime
     */
    private $ColumnDatetime;

    /**
     * @var \DateTime
     */
    private $ColumnTime;

    /**
     * @var \pingdecopong\SamplePDPGeneratorBundle\Entity\SystemUser
     */
    private $systemuser;


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
     * Set Name
     *
     * @param string $name
     * @return WatertankSearchModel
     */
    public function setName($name)
    {
        $this->Name = $name;
    
        return $this;
    }

    /**
     * Get Name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->Name;
    }

    /**
     * Set SystemUserId
     *
     * @param integer $systemUserId
     * @return WatertankSearchModel
     */
    public function setSystemUserId($systemUserId)
    {
        $this->SystemUserId = $systemUserId;
    
        return $this;
    }

    /**
     * Get SystemUserId
     *
     * @return integer 
     */
    public function getSystemUserId()
    {
        return $this->SystemUserId;
    }

    /**
     * Set IntegerData
     *
     * @param integer $integerData
     * @return WatertankSearchModel
     */
    public function setIntegerData($integerData)
    {
        $this->IntegerData = $integerData;
    
        return $this;
    }

    /**
     * Get IntegerData
     *
     * @return integer 
     */
    public function getIntegerData()
    {
        return $this->IntegerData;
    }

    /**
     * Set DefTest1
     *
     * @param boolean $defTest1
     * @return WatertankSearchModel
     */
    public function setDefTest1($defTest1)
    {
        $this->DefTest1 = $defTest1;
    
        return $this;
    }

    /**
     * Get DefTest1
     *
     * @return boolean 
     */
    public function getDefTest1()
    {
        return $this->DefTest1;
    }

    /**
     * Set ColumnText
     *
     * @param string $columnText
     * @return WatertankSearchModel
     */
    public function setColumnText($columnText)
    {
        $this->ColumnText = $columnText;
    
        return $this;
    }

    /**
     * Get ColumnText
     *
     * @return string 
     */
    public function getColumnText()
    {
        return $this->ColumnText;
    }

    /**
     * Set ColumnDate
     *
     * @param \DateTime $columnDate
     * @return WatertankSearchModel
     */
    public function setColumnDate($columnDate)
    {
        $this->ColumnDate = $columnDate;
    
        return $this;
    }

    /**
     * Get ColumnDate
     *
     * @return \DateTime 
     */
    public function getColumnDate()
    {
        return $this->ColumnDate;
    }

    /**
     * Set ColumnDatetime
     *
     * @param \DateTime $columnDatetime
     * @return WatertankSearchModel
     */
    public function setColumnDatetime($columnDatetime)
    {
        $this->ColumnDatetime = $columnDatetime;
    
        return $this;
    }

    /**
     * Get ColumnDatetime
     *
     * @return \DateTime 
     */
    public function getColumnDatetime()
    {
        return $this->ColumnDatetime;
    }

    /**
     * Set ColumnTime
     *
     * @param \DateTime $columnTime
     * @return WatertankSearchModel
     */
    public function setColumnTime($columnTime)
    {
        $this->ColumnTime = $columnTime;
    
        return $this;
    }

    /**
     * Get ColumnTime
     *
     * @return \DateTime 
     */
    public function getColumnTime()
    {
        return $this->ColumnTime;
    }

    /**
     * Set systemuser
     *
     * @param \pingdecopong\SamplePDPGeneratorBundle\Entity\SystemUser $systemuser
     * @return WatertankSearchModel
     */
    public function setSystemuser(\pingdecopong\SamplePDPGeneratorBundle\Entity\SystemUser $systemuser = null)
    {
        $this->systemuser = $systemuser;
    
        return $this;
    }

    /**
     * Get systemuser
     *
     * @return \pingdecopong\SamplePDPGeneratorBundle\Entity\SystemUser 
     */
    public function getSystemuser()
    {
        return $this->systemuser;
    }
}
