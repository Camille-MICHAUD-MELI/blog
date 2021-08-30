<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\ExecutionContext;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @ORM\Entity()
 * @ORM\Table(name="comment")
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="comment")
     * @var User
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Message", inversedBy="comment")
     * @var Message
     */
    protected $message;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    protected $corpse;

    /**
     * @var \DateTime
     * @ORM\Column(name="date_of_post", type="datetime")
     */
    private $publicationDate;

    /**
     * @var \DateTime
     * @ORM\Column(name="last_modification", type="datetime", nullable=true)
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

    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }
}