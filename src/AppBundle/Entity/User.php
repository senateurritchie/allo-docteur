<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * User
 *
 * @ORM\Table(name="user", options={"comment":"enregistre les utilisateurs de la plateforme avec différents niveau d'acces"}, indexes={@ORM\Index(columns={"username"},flags={"fulltext"})} )
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @UniqueEntity("email", message="cet adresse est déja enregistrée")
 */
class User implements UserInterface, EquatableInterface, \Serializable
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
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Country", inversedBy="users")
    * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
    */
    private $country;

    /**
    * @Groups({"group1"})
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\City", inversedBy="users")
    * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
    */
    private $city;

    /**
    * @Groups({"group1"})
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\UserType", inversedBy="users")
    * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
    */
    private $userType;

    /**
    * @var string
    * @Groups({"group1"})
    * @Assert\NotBlank
    * @Assert\Length(min=3, max=50)
    * @ORM\Column(name="firstname", type="string", length=255, nullable=true)
    */
    private $firstname;

    /**
    * @var string
    * @Groups({"group1"})
    * @Assert\Length(min=3, max=100)
    * @ORM\Column(name="lastname", type="string", length=255, nullable=true)
    */
    private $lastname;

    /**
    * @var string
    * @Groups({"group1"})
    * @Assert\Length(min=3, max=150)
    * @ORM\Column(name="username", type="string", length=150)
    */
    private $username;

    /**
    * @var string
    *
    * @Groups({"group1"})
    * @Gedmo\Slug(fields={"username"})
    * @ORM\Column(name="slug", type="string", length=255, unique=true)
    */
    private $slug;

    /**
    * @var string
    *
    * @Groups({"group1"})
    * @Assert\Email
    * @ORM\Column(name="email", type="string", length=255, nullable=true, unique=true)
    */
    private $email;

    /**
    * @var string
    *
    * @Groups({"group1"})
    * @ORM\Column(name="about_me", type="text", nullable=true)
    */
    private $aboutMe;

    /**
    * @var string
    *
    * @Groups({"group3"})
    * @ORM\Column(name="salt", type="string", length=64, nullable=true)
    */
    private $salt;

    /**
    * @var string
    *
    * @Groups({"group3"})
    * @Assert\Length(min=8)
    * @ORM\Column(name="password", type="string", length=70)
    */
    private $password;

    /**
    * @var string
    *
    * @Groups({"group3"})
    * @ORM\Column(name="signup_token", type="string", options={"comment":"un code arbitraire généré pour l'activation du compte"}, nullable=true, length=64)
    */
    private $signUpToken;

    /**
    * @var \DateTime
    *
    * @Groups({"group1"})
    * @Gedmo\Timestampable(on="create")
    * @ORM\Column(name="create_at", type="datetime", nullable=true)
    */
    private $createAt;

    /**
    * @var string
    *
    * @Groups({"group1"})
    * @ORM\Column(name="state", type="string", options={"comment":"le status d'un utilisateur"}, columnDefinition="ENUM('activate','pending','blocked')", nullable=true)
    */
    private $state = "pending";

    /**
    * @var string
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="image", type="string", length=255, nullable=true)
    * @assert\Image(mimeTypes={"image/jpg","image/jpeg","image/png"},minWidth=270,maxWidth=565, minHeight=360,maxHeight=565)
    */
    private $image;

    
    /**
    * @var string
    *
    * @Groups({"group1","group2","group3"})
    * @ORM\Column(name="locale", type="string", options={"comment":"la langue de l'utilisateur"}, nullable=true, length=5)
    */
    private $locale = 'fr';

    /**
    * @var boolean
    *
    * @Groups({"group1"})
    * @ORM\Column(name="email_verified", type="boolean", options={"comment":"permet de savoir si l'adresse mail a été vérifiée"}, nullable=true)
    */
    private $emailVerified = 0;

    /**
    * @var string
    *
    * @Groups({"group1"})
    * @ORM\Column(name="address", type="string", length=225, options={"comment":"la localisation textuelle de l'utilisateur"}, nullable=true)
    */
    private $address;

    /**
    * @var int
    *
    * @Groups({"group1"})
    * @ORM\Column(name="lat", type="integer", options={"comment":"geolocalisation latitude"}, nullable=true)
    */
    private $lat;

    /**
    * @var int
    *
    * @Groups({"group1"})
    * @ORM\Column(name="lng", type="integer", options={"comment":"geolocalisation longitude"}, nullable=true)
    */
    private $lng;

    /**
    * @var string
    *
    * @Groups({"group1"})
    * @ORM\Column(name="phone", type="string", length=100, options={"comment":"le numero de téléphone de l'utilisateur"}, nullable=true)
    */
    private $phone;

    /**
    * @var string
    *
    * @Groups({"group1"})
    * @ORM\Column(name="office_phone", type="string", length=100, options={"comment":"le numero de téléphone du bureau de l'utilisateur"}, nullable=true)
    */
    private $officePhone;

    /**
    * @var string
    *
    * @Groups({"group1"})
    * @Assert\Url
    * @ORM\Column(name="website", type="string", length=255, options={"comment":"le site web de l'utilisateur"}, nullable=true)
    */
    private $website;

    /**
    * @var string
    *
    * @Groups({"group1"})
   * @Assert\Regex(pattern="/^https:\/\/www.facebook.com\/(.+)/")
    * @Assert\Url
    * @ORM\Column(name="profile_facebook", type="string", length=255, options={"comment":"le lien du profile facebook"}, nullable=true)
    */
    private $profileFacebook;

    /**
    * @var string
    *
    * @Groups({"group1"})
    * @Assert\Regex(pattern="/^https:\/\/www.linkedin.com\/(.+)/")
    * @Assert\Url
    * @ORM\Column(name="profile_linkedin", type="string", length=255, options={"comment":"le lien du profile linkedin"}, nullable=true)
    */
    private $profileLinkedin;

    /**
    * @var string
    *
    * @Groups({"group1"})
    * @Assert\Regex(pattern="/^https:\/\/www.twitter.com\/(.+)/")
    * @Assert\Url
    * @ORM\Column(name="profile_twitter", type="string", length=255, options={"comment":"le lien du profile twitter"}, nullable=true)
    */
    private $profileTwitter;

    /**
    * @var int
    *
    * @Groups({"group1"})
    * @ORM\Column(name="page_view", type="integer", options={"comment":"nombre de vue de la page"}, nullable=true)
    */
    private $page_view = 0;


    /**
    * @var string
    *
    * @ORM\Column(name="gender", type="string", length=15, columnDefinition="ENUM('feminin','masculin')", nullable=true, options={"comment":"le status du programme, c'est les differents etats que peu avoir un programme"})
    */
    private $gender = 'masculin';
    
    /**
    * @var array
    *
    * @Groups({"group1"})
    */
    private $roles;

    /**
    * @var Role
    *
    * @Groups({"group1","group2"})
    */
    private $masterRole;
    /**
    * @var array<Role>
    *
    * @Groups({"group1","group2"})
    */
    private $privileges = array();

    

    /**
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\UserRole", mappedBy="user")
    * @Groups({"group4"})
    */
    private $uroles;

    /**
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\Doctor", mappedBy="user")
    * @Groups({"group2"})
    */
    private $doctors;

    /**
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\Clinic", mappedBy="user")
    * @Groups({"group2"})
    */
    private $clinics;

    /**
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\DoctorSpecialization", mappedBy="user", cascade={"persist","remove"})
    * @Groups({"group2"})
    */
    private $specializations;

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
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set salt
     *
     * @param string $salt
     *
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    

    /**
     * Set roles
     *
     * @param array $roles
     *
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get roles
     *
     * @return array
     */
    public function getRoles()
    {
        $uroles = $this->getUroles();
        $roles = [];

        foreach ($uroles as $key => $el) {
            $roles[] = $el->getRole();
        }

        // role principal
        $role = array_filter($roles,function($el){
            return ($el->getType() == "role");
        });
        $role = array_values($role);

        if(count($role)){
            $this->setMasterRole($role[0]);
        };

        // privileges
        $privileges = array_filter($roles,function($el){
            return ($el->getType() == "privilege");
        });
        $privileges = array_values($privileges);
        $this->setPrivileges($privileges);

        $roles = array_map(function($el){
            return $el->getLabel();
        }, $roles);

        $this->setRoles($roles);

        return $this->roles;
    }

    public function setMasterRole(Role $masterRole){
        $this->masterRole = $masterRole;
        return $this;
    }

    public function setPrivileges(array $roles = array()){
        $this->privileges = $roles;
        return $this;
    }

    public function getMasterRole(){
        return $this->masterRole;
    }

    public function getPrivileges(){
        return $this->privileges;
    }

    public function eraseCredentials()
    {
    }

    public function isEqualTo(UserInterface $user)
    {
        if (!$user instanceof WebserviceUser) {
            return false;
        }

        if ($this->password !== $user->getPassword()) {
            return false;
        }

        if ($this->salt !== $user->getSalt()) {
            return false;
        }

        if ($this->username !== $user->getUsername()) {
            return false;
        }

        return true;
    }

    /** @see \Serializable::serialize() */
    public function serialize(){
        return serialize(array(
            $this->id,
            $this->email,
            $this->username,
            $this->password,
            $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized){
        list (
            $this->id,
            $this->email,
            $this->password,
            $this->salt
        ) = unserialize($serialized);
    }

    /**
     * Set createAt
     *
     * @param \DateTime $createAt
     *
     * @return User
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
     * Set state
     *
     * @param string $state
     *
     * @return User
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set signUpToken
     *
     * @param string $signUpToken
     *
     * @return User
     */
    public function setSignUpToken($signUpToken)
    {
        $this->signUpToken = $signUpToken;

        return $this;
    }

    /**
     * Get signUpToken
     *
     * @return string
     */
    public function getSignUpToken()
    {
        return $this->signUpToken;
    }

    public static function generateToken($length = 8){
        return substr(trim(base64_encode(bin2hex(openssl_random_pseudo_bytes(64,$ok))),"="),0,$length);
    }
    



    /**
     * Add urole
     *
     * @param \AppBundle\Entity\UserRole $urole
     *
     * @return User
     */
    public function addUrole(\AppBundle\Entity\UserRole $urole)
    {
        $this->uroles[] = $urole;

        return $this;
    }

    /**
     * Remove urole
     *
     * @param \AppBundle\Entity\UserRole $urole
     */
    public function removeUrole(\AppBundle\Entity\UserRole $urole)
    {
        $this->uroles->removeElement($urole);
    }

    /**
     * Get uroles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUroles()
    {
        return $this->uroles;
    }

    /**
     * Set aboutMe
     *
     * @param string $aboutMe
     *
     * @return User
     */
    public function setAboutMe($aboutMe)
    {
        $this->aboutMe = $aboutMe;

        return $this;
    }

    /**
     * Get aboutMe
     *
     * @return string
     */
    public function getAboutMe()
    {
        return $this->aboutMe;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return User
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set locale
     *
     * @param string $locale
     *
     * @return User
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Get locale
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set emailVerified
     *
     * @param boolean $emailVerified
     *
     * @return User
     */
    public function setEmailVerified($emailVerified)
    {
        $this->emailVerified = $emailVerified;

        return $this;
    }

    /**
     * Get emailVerified
     *
     * @return boolean
     */
    public function getEmailVerified()
    {
        return $this->emailVerified;
    }

    /**
     * Set userType
     *
     * @param \AppBundle\Entity\UserType $userType
     *
     * @return User
     */
    public function setUserType(\AppBundle\Entity\UserType $userType = null)
    {
        $this->userType = $userType;

        return $this;
    }

    /**
     * Get userType
     *
     * @return \AppBundle\Entity\UserType
     */
    public function getUserType()
    {
        return $this->userType;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return User
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set lat
     *
     * @param integer $lat
     *
     * @return User
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return integer
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lng
     *
     * @param integer $lng
     *
     * @return User
     */
    public function setLng($lng)
    {
        $this->lng = $lng;

        return $this;
    }

    /**
     * Get lng
     *
     * @return integer
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set officePhone
     *
     * @param string $officePhone
     *
     * @return User
     */
    public function setOfficePhone($officePhone)
    {
        $this->officePhone = $officePhone;

        return $this;
    }

    /**
     * Get officePhone
     *
     * @return string
     */
    public function getOfficePhone()
    {
        return $this->officePhone;
    }

    /**
     * Set website
     *
     * @param string $website
     *
     * @return User
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set profileFacebook
     *
     * @param string $profileFacebook
     *
     * @return User
     */
    public function setProfileFacebook($profileFacebook)
    {
        $this->profileFacebook = $profileFacebook;

        return $this;
    }

    /**
     * Get profileFacebook
     *
     * @return string
     */
    public function getProfileFacebook()
    {
        return $this->profileFacebook;
    }

    /**
     * Set profileLinkedin
     *
     * @param string $profileLinkedin
     *
     * @return User
     */
    public function setProfileLinkedin($profileLinkedin)
    {
        $this->profileLinkedin = $profileLinkedin;

        return $this;
    }

    /**
     * Get profileLinkedin
     *
     * @return string
     */
    public function getProfileLinkedin()
    {
        return $this->profileLinkedin;
    }

    /**
     * Set profileTwitter
     *
     * @param string $profileTwitter
     *
     * @return User
     */
    public function setProfileTwitter($profileTwitter)
    {
        $this->profileTwitter = $profileTwitter;

        return $this;
    }

    /**
     * Get profileTwitter
     *
     * @return string
     */
    public function getProfileTwitter()
    {
        return $this->profileTwitter;
    }

    /**
     * Set pageView
     *
     * @param integer $pageView
     *
     * @return User
     */
    public function setPageView($pageView)
    {
        $this->page_view = $pageView;

        return $this;
    }

    /**
     * Get pageView
     *
     * @return integer
     */
    public function getPageView()
    {
        return $this->page_view;
    }

    /**
     * Set gender
     *
     * @param string $gender
     *
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
    * Constructor
    */
    public function __construct($username=null, $email=null,$password=null, $salt=null,$createAt=null){
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->salt = $salt;

        $this->uroles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->doctors = new \Doctrine\Common\Collections\ArrayCollection();
        $this->specializations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add doctor
     *
     * @param \AppBundle\Entity\Doctor $doctor
     *
     * @return User
     */
    public function addDoctor(\AppBundle\Entity\Doctor $doctor)
    {
        $this->doctors[] = $doctor;

        return $this;
    }

    /**
     * Remove doctor
     *
     * @param \AppBundle\Entity\Doctor $doctor
     */
    public function removeDoctor(\AppBundle\Entity\Doctor $doctor)
    {
        $this->doctors->removeElement($doctor);
    }

    /**
     * Get doctors
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDoctors()
    {
        return $this->doctors;
    }

    /**
     * Set country
     *
     * @param \AppBundle\Entity\Country $country
     *
     * @return User
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

    /**
     * Set city
     *
     * @param \AppBundle\Entity\City $city
     *
     * @return User
     */
    public function setCity(\AppBundle\Entity\City $city = null)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return \AppBundle\Entity\City
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Add clinic
     *
     * @param \AppBundle\Entity\Clinic $clinic
     *
     * @return User
     */
    public function addClinic(\AppBundle\Entity\Clinic $clinic)
    {
        $this->clinics[] = $clinic;

        return $this;
    }

    /**
     * Remove clinic
     *
     * @param \AppBundle\Entity\Clinic $clinic
     */
    public function removeClinic(\AppBundle\Entity\Clinic $clinic)
    {
        $this->clinics->removeElement($clinic);
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
     * Add specialization
     *
     * @param \AppBundle\Entity\DoctorSpecialization $specialization
     *
     * @return User
     */
    public function addSpecialization(\AppBundle\Entity\DoctorSpecialization $specialization)
    {
        if (!$this->specializations->contains($specialization)) {
            $this->specializations[] = $specialization;
            $specialization->setUser($this);
        }
        return $this;
    }

    /**
     * Remove specialization
     *
     * @param \AppBundle\Entity\DoctorSpecialization $specialization
     */
    public function removeSpecialization(\AppBundle\Entity\DoctorSpecialization $specialization)
    {
        if ($this->specializations->contains($specialization)) {
            $this->specializations->removeElement($specialization);
            if ($specializations->getClinic() === $this) {
                $specialization->setUser(null);
            }
        }
        return $this;
    }

    /**
     * Get specializations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSpecializations()
    {
        return $this->specializations;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return User
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }
}
