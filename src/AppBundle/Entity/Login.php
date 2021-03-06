<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Login
 *
 * @ORM\Table(name="login")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LoginRepository")
 */
class Login
{
    const ACTION_LOGIN = "login";
    const ACTION_LOGOUT = "logout";
    const ACTION_FAILS = "fails";

    /**
    * @var int
    *
    * @ORM\Column(name="id", type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;
    /**
    * @var AppBundle\Entity\User
    *
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
    * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
    */
    private $user;
    /**
    * @var AppBundle\Entity\Country
    *
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Country")
    * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
    */
    private $country;
    /**
    * @var string
    *
    * @ORM\Column(name="type", type="string", options={"comment":"le type d'action"}, columnDefinition="ENUM('login','logout','fails')", nullable=true)
    */
    private $action;
    /**
    * @var string
    *
    * @Assert\Ip
    * @ORM\Column(name="ip", type="string", length=64, nullable=true, options={"comment":"adresse ip du client"})
    */
    private $ip;
    /**
    * @var string
    *
    * @ORM\Column(name="city", type="string", length=100, nullable=true,options={"comment":"la ville actuelle du client"})
    */
    private $city;
    /**
    * @var int
    *
    * @ORM\Column(name="lat", type="integer", nullable=true, options={"comment":"les coordonnées gps lors de la connexion"})
    */
    private $lat;
    /**
    * @var int
    *
    * @ORM\Column(name="lon", type="integer", nullable=true,options={"comment":"les coordonnées gps lors de la connexion"})
    */
    private $lon;
    /**
    * @var string
    *
    * @ORM\Column(name="device", length=100, type="string", options={"comment":"le type d'appareil utilisé pour la connexion"}, columnDefinition="ENUM('phone','tablet','desktop')", nullable=true)
    */
    private $device;
    /**
    * @var string
    *
    * @ORM\Column(name="user_agent", type="string", length=255, nullable=true,options={"comment":"le navigateur utilisé"})
    */
    private $userAgent;
    /**
    * @var string
    *
    * @ORM\Column(name="token", type="string", length=32, nullable=true,options={"comment":"un token unique pour chaque connexion"})
    */
    private $token;
    /**
    * @var \DateTime
    *
    * @Gedmo\Timestampable(on="create")
    * @ORM\Column(name="create_at", type="datetime", nullable=true,options={"comment":"la date de connexion"})
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
     * Set ip
     *
     * @param string $ip
     *
     * @return Login
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Login
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set lat
     *
     * @param integer $lat
     *
     * @return Login
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return int
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lon
     *
     * @param integer $lon
     *
     * @return Login
     */
    public function setLon($lon)
    {
        $this->lon = $lon;

        return $this;
    }

    /**
     * Get lon
     *
     * @return int
     */
    public function getLon()
    {
        return $this->lon;
    }

    /**
     * Set device
     *
     * @param string $device
     *
     * @return Login
     */
    public function setDevice($device)
    {
        $this->device = $device;

        return $this;
    }

    /**
     * Get device
     *
     * @return string
     */
    public function getDevice()
    {
        return $this->device;
    }

    /**
     * Set userAgent
     *
     * @param string $userAgent
     *
     * @return Login
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    /**
     * Get userAgent
     *
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * Set token
     *
     * @param string $token
     *
     * @return Login
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set createAt
     *
     * @param \DateTime $createAt
     *
     * @return Login
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
     * @return Login
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
     * Set action
     *
     * @param string $action
     *
     * @return Login
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set country
     *
     * @param \AppBundle\Entity\Country $country
     *
     * @return Login
     */
    public function setCountry(\AppBundle\Entity\Country $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return \AppBundle\Entity\Country
     */
    public function getCountry()
    {
        return $this->country;
    }
}
