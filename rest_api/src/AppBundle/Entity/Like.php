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
 * @ORM\Table(name="like")
 */
class Like
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="like")
     * @var User
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Comment", inversedBy="like")
     * @var Comment
     */
    protected $comment;

    public function getId()
    {
        return $this->id;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }
}