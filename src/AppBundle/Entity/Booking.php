<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Booking
 *
 * @ORM\Table(name="booking", options={"comment":"enregistrement des reservations de consultation en ligne"})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BookingRepository")
 */
class Booking
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
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="bookers")
    * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
    */
    private $user;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", options={"comment":"la date de la consultation"})
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="hour", type="time", options={"comment":"l'heure de la consultation"})
     */
    private $hour;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer", options={"comment":"le status de la consultation. soit confirmé, annulé, ou rejeté"})
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="client_name", type="string", length=100, options={"le nom du client"})
     */
    private $clientName;

    /**
     * @var string
     *
     * @ORM\Column(name="client_phone", type="string", length=100, options={"comment":"le numero de téléphone du client"})
     */
    private $clientPhone;

    /**
     * @var string
     *
     * @ORM\Column(name="client_email", type="string", length=100, options={"comment":"l'adresse email du client"})
     */
    private $clientEmail;

    /**
    * @var \DateTime
    *
    * @Gedmo\Timestampable(on="create")
    * @ORM\Column(name="create_at", type="datetime", options={"comment":"date à laquelle ce enregistrement est effectué"})
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Booking
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set hour
     *
     * @param \DateTime $hour
     *
     * @return Booking
     */
    public function setHour($hour)
    {
        $this->hour = $hour;

        return $this;
    }

    /**
     * Get hour
     *
     * @return \DateTime
     */
    public function getHour()
    {
        return $this->hour;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Booking
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set clientName
     *
     * @param string $clientName
     *
     * @return Booking
     */
    public function setClientName($clientName)
    {
        $this->clientName = $clientName;

        return $this;
    }

    /**
     * Get clientName
     *
     * @return string
     */
    public function getClientName()
    {
        return $this->clientName;
    }

    /**
     * Set clientPhone
     *
     * @param string $clientPhone
     *
     * @return Booking
     */
    public function setClientPhone($clientPhone)
    {
        $this->clientPhone = $clientPhone;

        return $this;
    }

    /**
     * Get clientPhone
     *
     * @return string
     */
    public function getClientPhone()
    {
        return $this->clientPhone;
    }

    /**
     * Set clientEmail
     *
     * @param string $clientEmail
     *
     * @return Booking
     */
    public function setClientEmail($clientEmail)
    {
        $this->clientEmail = $clientEmail;

        return $this;
    }

    /**
     * Get clientEmail
     *
     * @return string
     */
    public function getClientEmail()
    {
        return $this->clientEmail;
    }

    /**
     * Set createAt
     *
     * @param \DateTime $createAt
     *
     * @return Booking
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
     * @return Booking
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
}
