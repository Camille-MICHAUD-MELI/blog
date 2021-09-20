<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\ExecutionContext;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\DateTime;
use AppBundle\Entity\User as User;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * @ORM\Entity()
 * @ORM\Table(name="post")
 * @ExclusionPolicy("all")
 */
class Message
{
    /**
     * @Expose
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @Exclude
     * @ORM\ManyToOne(targetEntity="User", inversedBy="messages")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @var User
     */
    protected $user;

    /**
     * @Exclude
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="Message")
     * @var Comment[]
     */
    protected $comments;

    /**
     * @Exclude
     * @ORM\OneToMany(targetEntity="Fav", mappedBy="Message")
     * @var Fav[]
     */
    protected $fav;

    /**
     * @Expose
     * @ORM\Column(type="text", nullable=false)
     * @ORM\Column(unique=true)
     */
    protected $title;

    /**
     * @Expose
     * @ORM\Column(type="text", nullable=false)
     */
    protected $corpse;

    /**
     * @Expose
     * @var \DateTime
     * @ORM\Column(name="date_of_post", type="datetime")
     */
    private $publicationDate;

    /**
     * @Expose
     * @var \DateTime
     * @ORM\Column(name="date_of_modification", type="datetime", nullable=true)
     */
    public $modificationDate;

    public function getId()
    {
        return $this->id;
    }

    public function getCorpse()
    {
        return $this->corpse;
    }

    public function getPublicationDate()
    {
        return $this->publicationDate;
    }

    public function getModificationDate()
    {
        return $this->modificationDate;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setCorpse($corpse)
    {
        $this->corpse = $corpse;
        return $this;
    }

    public function setPublicationDate($publicationDate)
    {
        $this->publicationDate = $publicationDate;
        return $this;
    }

    public function setModificationDate($modificationDate)
    {
        $this->modificationDate = $modificationDate;
        return $this;
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }
}