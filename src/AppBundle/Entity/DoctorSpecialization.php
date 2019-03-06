<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * DoctorSpecialization
 *
 * @ORM\Table(name="doctor_specialization")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DoctorSpecializationRepository")
 */
class DoctorSpecialization
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
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Specialization")
    * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
    */
    private $specialization;

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
     * Set createAt
     *
     * @param \DateTime $createAt
     *
     * @return DoctorSpecialization
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
     * @return DoctorSpecialization
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
     * Set specialization
     *
     * @param \AppBundle\Entity\Specialization $specialization
     *
     * @return DoctorSpecialization
     */
    public function setSpecialization(\AppBundle\Entity\Specialization $specialization)
    {
        $this->specialization = $specialization;

        return $this;
    }

    /**
     * Get specialization
     *
     * @return \AppBundle\Entity\Specialization
     */
    public function getSpecialization()
    {
        return $this->specialization;
    }
}
