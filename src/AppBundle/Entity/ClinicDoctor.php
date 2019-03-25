<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
/**
 * ClinicDoctor
 *
 * @ORM\Table(name="clinic_doctor")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClinicDoctorRepository")
 */
class ClinicDoctor
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
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Clinic", inversedBy="doctors")
    * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
    */
    private $clinic;

    /**
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Doctor", inversedBy="clinics")
    * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
    */
    private $doctor;

    /**
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Job")
    * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
    */
    private $job;

    /**
    * @var string
    *
    * @ORM\Column(name="status", type="string", length=15, columnDefinition="ENUM('approved','pending','cancelled','blocked')", nullable=true)
    */
    private $status = "pending";

    /**
    * @var \DateTime
    *
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
     * Set status
     *
     * @param string $status
     *
     * @return ClinicDoctor
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set createAt
     *
     * @param \DateTime $createAt
     *
     * @return ClinicDoctor
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
     * Set clinic
     *
     * @param \AppBundle\Entity\Clinic $clinic
     *
     * @return ClinicDoctor
     */
    public function setClinic(\AppBundle\Entity\Clinic $clinic)
    {
        $this->clinic = $clinic;

        return $this;
    }

    /**
     * Get clinic
     *
     * @return \AppBundle\Entity\Clinic
     */
    public function getClinic()
    {
        return $this->clinic;
    }

    /**
     * Set doctor
     *
     * @param \AppBundle\Entity\Doctor $doctor
     *
     * @return ClinicDoctor
     */
    public function setDoctor(\AppBundle\Entity\Doctor $doctor)
    {
        $this->doctor = $doctor;

        return $this;
    }

    /**
     * Get doctor
     *
     * @return \AppBundle\Entity\Doctor
     */
    public function getDoctor()
    {
        return $this->doctor;
    }

    /**
     * Set job
     *
     * @param \AppBundle\Entity\Job $job
     *
     * @return ClinicDoctor
     */
    public function setJob(\AppBundle\Entity\Job $job)
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
