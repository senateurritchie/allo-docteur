<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * DoctorTreatment
 *
 * @ORM\Table(name="doctor_treatment", options={"comment":"la liste des soins proposé par un medecin ou une clinic"})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DoctorTreatmentRepository")
 */
class DoctorTreatment
{
    /**
    * @var int
    *
    * @ORM\Column(name="id", type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;

    /**
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
    * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
    */
    private $user;

    /**
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Treatment")
    * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
    */
    private $treatment;

    /**
    * @var int
    *
    * @ORM\Column(name="price", type="integer")
    */
    private $price;

    /**
    * @var \Datetime
    *
    * @Groups({"group1"})
    * @Gedmo\Timestampable(on="create")
    * @ORM\Column(name="create_at", type="datetime")
    */
    private $createAt;


    /**
    * Get id
    *
    * @return int
    */
    public function getId()
    {
        return $this->id;
    }

    /**
    * Set price
    *
    * @param integer $price
    *
    * @return DoctorTreatment
    */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
    * Get price
    *
    * @return int
    */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set createAt
     *
     * @param \DateTime $createAt
     *
     * @return DoctorTreatment
     */
    public function setCreateAt($createAt)
    {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * Get createAt
     *
     * @return \DateTime
     */
    public function getCreateAt()
    {
        return $this->createAt;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return DoctorTreatment
     */
    public function setUser(\AppBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set treatment
     *
     * @param \AppBundle\Entity\Treatment $treatment
     *
     * @return DoctorTreatment
     */
    public function setTreatment(\AppBundle\Entity\Treatment $treatment)
    {
        $this->treatment = $treatment;

        return $this;
    }

    /**
     * Get treatment
     *
     * @return \AppBundle\Entity\Treatment
     */
    public function getTreatment()
    {
        return $this->treatment;
    }
}
