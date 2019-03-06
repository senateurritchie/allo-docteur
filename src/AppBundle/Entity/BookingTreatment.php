<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BookingTreatment
 *
 * @ORM\Table(name="booking_treatment", options={"comment":"les differentes spécialités d'une consultation"})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BookingTreatmentRepository")
 */
class BookingTreatment
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
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Booking")
    * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
    */
    private $booking;

    /**
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\DoctorTreatment")
    * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
    */
    private $doctorTreatment;

    /**
    * @var string
    *
    * @ORM\Column(name="status", type="string", length=15, columnDefinition="ENUM('approved','pending','rejected')",)
    */
    private $status;

    /**
    * @var \DateTime
    *
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
     * @return BookingTreatment
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
     * @return BookingTreatment
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
     * Set booking
     *
     * @param \AppBundle\Entity\Booking $booking
     *
     * @return BookingTreatment
     */
    public function setBooking(\AppBundle\Entity\Booking $booking)
    {
        $this->booking = $booking;

        return $this;
    }

    /**
     * Get booking
     *
     * @return \AppBundle\Entity\Booking
     */
    public function getBooking()
    {
        return $this->booking;
    }

    /**
     * Set doctorTreatment
     *
     * @param \AppBundle\Entity\DoctorTreatment $doctorTreatment
     *
     * @return BookingTreatment
     */
    public function setDoctorTreatment(\AppBundle\Entity\DoctorTreatment $doctorTreatment)
    {
        $this->doctorTreatment = $doctorTreatment;

        return $this;
    }

    /**
     * Get doctorTreatment
     *
     * @return \AppBundle\Entity\DoctorTreatment
     */
    public function getDoctorTreatment()
    {
        return $this->doctorTreatment;
    }
}
