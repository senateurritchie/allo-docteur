<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * Country
 *
 * @ORM\Table(name="country", options={"comment":"enregistre tout les pays"})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CountryRepository")
 * @UniqueEntity("name", message="le nom du pays doit être unique")
 */
class Country
{
    /**
    * @var int
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="id", type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;

    /**
    * @var string
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="name", type="string", length=30)
    */
    private $name;

    /**
    * @var string
    * 
    * @Groups({"group1","group2"})
    * @Gedmo\Slug(fields={"name"})
    * @ORM\Column(name="slug", type="string", length=60)
    */
    private $slug;

    /**
    * @var string
    * 
    * @Groups({"group1","group2"})
    * @ORM\Column(name="code", type="string", length=4, options={"comment":"le code iso de ce pays"})
    */
    private $code;
    /**
    * @var string
    * 
    * @Groups({"group1","group2"})
    * @ORM\Column(name="locale", type="string", length=4, options={"comment":"le code de la langue parlée"})
    */
    private $locale;

    /**
    * @var string
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="cnt_code", type="string", length=3, nullable=true)
    */
    private $cntCode;

    /**
    * @var string
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="cnt_name", type="string", length=50, nullable=true)
    */
    private $cntName;

    /**
    * @var boolean
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="is_in_european_union", type="boolean", nullable=true)
    */
    private $isInEuropeanUnion = 0;


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
     * Set name
     *
     * @param string $name
     *
     * @return Country
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Country
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



    /**
     * Set code
     *
     * @param string $code
     *
     * @return Country
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set locale
     *
     * @param string $locale
     *
     * @return Country
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
     * Set cntCode
     *
     * @param string $cntCode
     *
     * @return Country
     */
    public function setCntCode($cntCode)
    {
        $this->cntCode = $cntCode;

        return $this;
    }

    /**
     * Get cntCode
     *
     * @return string
     */
    public function getCntCode()
    {
        return $this->cntCode;
    }

    /**
     * Set cntName
     *
     * @param string $cntName
     *
     * @return Country
     */
    public function setCntName($cntName)
    {
        $this->cntName = $cntName;

        return $this;
    }

    /**
     * Get cntName
     *
     * @return string
     */
    public function getCntName()
    {
        return $this->cntName;
    }

    /**
     * Set isInEuropeanUnion
     *
     * @param boolean $isInEuropeanUnion
     *
     * @return Country
     */
    public function setIsInEuropeanUnion($isInEuropeanUnion)
    {
        $this->isInEuropeanUnion = $isInEuropeanUnion;

        return $this;
    }

    /**
     * Get isInEuropeanUnion
     *
     * @return boolean
     */
    public function getIsInEuropeanUnion()
    {
        return $this->isInEuropeanUnion;
    }
}
