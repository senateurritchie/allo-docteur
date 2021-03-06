<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Doctor
 *
 * @ORM\Table(name="doctor")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DoctorRepository")
 */
class Doctor
{
    /**
    * @var int
    *
    * @Groups({"group1"})
    * @ORM\Column(name="id", type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;

    /**
    * @Groups({"group1"})
    * @Assert\Valid
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="doctors", cascade={"persist","remove"})
    * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
    */
    private $user;

    /**
    * @Groups({"group1"})
    * @Assert\NotBlank
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\DoctorType", inversedBy="doctors", cascade={"persist","remove"})
    * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
    */
    private $doctorType;

    /**
    * @Groups({"group1"})
    * @Assert\NotBlank
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Job", inversedBy="doctors", cascade={"persist","remove"})
    * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
    */
    private $job;


    /**
    * @Groups({"group2"})
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\ClinicDoctor", mappedBy="doctor", cascade={"persist","remove"})
    */
    private $clinics;

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
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Doctor
     */
    public function setUser(\AppBundle\Entity\User $user = null)
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
     * Set doctorType
     *
     * @param \AppBundle\Entity\DoctorType $doctorType
     *
     * @return Doctor
     */
    public function setDoctorType(\AppBundle\Entity\DoctorType $doctorType = null)
    {
        $this->doctorType = $doctorType;

        return $this;
    }

    /**
    * Get doctorType
    *
    * @return \AppBundle\Entity\DoctorType
    */
    public function getDoctorType()
    {
        return $this->doctorType;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->clinics = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add clinic
     *
     * @param \AppBundle\Entity\ClinicDoctor $clinic
     *
     * @return Doctor
     */
    public function addClinic(\AppBundle\Entity\ClinicDoctor $clinic)
    {
        if (!$this->clinics->contains($clinic)) {
            $this->clinics[] = $clinic;
            $clinic->setDoctor($this);
        }
        return $this;
    }

    /**
     * Remove clinic
     *
     * @param \AppBundle\Entity\ClinicDoctor $clinic
     */
    public function removeClinic(\AppBundle\Entity\ClinicDoctor $clinic)
    {
        if ($this->clinics->contains($clinic)) {
            $this->clinics->removeElement($clinic);
            if ($clinic->getDoctor() === $this) {
                $clinic->setDoctor(null);
            }
        }
        return $this;
    }

    /**
     * Get clinics
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getClinics()
    {
        return $this->clinics;
    }

    /**
     * Set job
     *
     * @param \AppBundle\Entity\Job $job
     *
     * @return Doctor
     */
    public function setJob(\AppBundle\Entity\Job $job = null)
    {
        $this->job = $job;

        return $this;
    }

    /**
     * Get job
     *
     * @return \AppBundle\Entity\Job
     */
    public function getJob()
    {
        return $this->job;
    }
}
