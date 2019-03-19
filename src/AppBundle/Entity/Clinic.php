<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Clinic
 *
 * @ORM\Table(name="clinic", options={"options":"enregistrement des cliniques"})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClinicRepository")
 */
class Clinic
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
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", cascade={"persist","remove"}, inversedBy="clinics")
    * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
    */
    private $user;

    /**
    * @Groups({"group2"})
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\ClinicDoctor", mappedBy="clinic", cascade={"persist","remove"})
    */
    private $doctors;


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
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Clinic
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
     * Constructor
     */
    public function __construct()
    {
        $this->doctors = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add doctor
     *
     * @param \AppBundle\Entity\ClinicDoctor $doctor
     *
     * @return Clinic
     */
    public function addDoctor(\AppBundle\Entity\ClinicDoctor $doctor)
    {
        if (!$this->doctors->contains($doctor)) {
            $this->doctors[] = $doctor;
            $doctor->setClinic($this);
        }
        return $this;
    }

    /**
     * Remove doctor
     *
     * @param \AppBundle\Entity\ClinicDoctor $doctor
     */
    public function removeDoctor(\AppBundle\Entity\ClinicDoctor $doctor){
        if ($this->doctors->contains($doctor)) {
            $this->doctors->removeElement($doctor);
            if ($doctor->getClinic() === $this) {
                $doctor->setClinic(null);
            }
        }
        return $this;
    }

    /**
     * Get doctors
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDoctors(){
        return $this->doctors;
    }
}
