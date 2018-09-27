<?php 

namespace CoMiDolegaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="symptoms")
 */
class Symptoms
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="symptoms_name", type="string", length=100)
     */
    protected $symptoms;

    public function getId()
    {
        return $this->id;
    }

    public function getSymptomName()
    {  
        return $this->symptoms;
    }

    public function __construct()
    {
        parent::__construct();
    }
}