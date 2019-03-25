<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Specialization
 *
 * @ORM\Table(name="specialization", options={"comment":"les spécialités dans lesquelles un medecin ou clinique peut consulter"},indexes={@ORM\Index(columns={"name"},flags={"fulltext"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SpecializationRepository")
 */
class Specialization
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
    * @var string
    *
    * @Groups({"group1"})
    * @Gedmo\Translatable
    * @ORM\Column(name="name", type="string", length=255, unique=true)
    */
    private $name;

    /**
    * @var string
    *
    * @Groups({"group1"})
    * @Gedmo\Translatable
    * @Gedmo\Slug(fields={"name"})
    * @ORM\Column(name="slug", type="string", length=255, unique=true)
    */
    private $slug;


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
     * Set name
     *
     * @param string $name
     *
     * @return Treatment
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
     * @return Treatment
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

