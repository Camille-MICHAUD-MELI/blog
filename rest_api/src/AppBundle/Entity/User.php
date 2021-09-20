<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\ExecutionContext;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Security\Core\User\UserInterface;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Exclude;

/**
 * @ORM\Entity
 * @ORM\Table(name="users",
 *      uniqueConstraints={@ORM\UniqueConstraint(name="users_username_unique",columns={"username"})}
 * )
 * @UniqueEntity("username")
 * @ORM\HasLifecycleCallbacks 
 * @UniqueEntity("email")
 * @ExclusionPolicy("all")
 */
class User implements UserInterface
{
    /**
     * @Expose
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    // /**
    //  * @ORM\OneToMany(targetEntity="Message", mappedBy="user")
    //  * @var Message[]
    //  * @Exclude
    //  */
    // protected $message;

    // /**
    //  * @ORM\OneToMany(targetEntity="Comment", mappedBy="user")
    //  * @Exclude
    //  * @var Comment[]
    //  */
    // protected $comment;

    // /**
    //  * @ORM\OneToMany(targetEntity="Fav", mappedBy="user")
    //  * @var Fav[]
    //  * @Exclude
    //  */
    // protected $fav;

    /**
     * @Expose
     * @ORM\Column(type="string")
     * @ORM\Column(unique=true)
     */
    protected $username;

    /**
     * @Expose
     * @ORM\Column(type="text", nullable=true)
     */
    protected $bio;

    /**
     * @Expose
     * @ORM\Column(type="string", nullable=false)
     * @ORM\Column(unique=true)
     */
    protected $email;

    /**
     * @Expose
     * @ORM\Column(type="string", nullable=false)
     */
    protected $password;

    /**
     * @Expose
     * @ORM\Column(type="string", nullable=true)
     */
    protected $phone;

    /**
     * @Expose
     * @ORM\Column(type="string", nullable=true)
     */
    protected $address;

    /**
     * @Expose
     * @ORM\Column(type="string", nullable=true)
     */
    protected $city;

    /**
     * @Expose
     * @ORM\Column(type="string", nullable=true)
     */
    protected $zipcode;

    /**
     * @Expose
     * @ORM\Column(type="string", nullable=true)
     */
    protected $country;

    /**
     * @var \DateTime
     * @Expose
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var \DateTime
     * @Expose
     * @ORM\Column(name="last_modification", type="datetime", nullable=true)
     */
    public $modificationDate;

    public function getRoles() { return ['ROLE_USER']; }

    public function eraseCredentials() {}

    public function getSalt() {}

    public function getCreated()
    {
        return $this->created;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function getBio()
    {
        return $this->bio;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function getZipcode()
    {
        return $this->zipcode;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getModificationDate()
    {
        return $this->modificationDate;
    }

    // public function getMessage()
    // {
    //     return $this->message;
    // }

    // public function getComment()
    // {
    //     return $this->comment;
    // }

    // public function getFav()
    // {
    //     return $this->fav;
    // }

    public function getPassword()
    {
        return $this->password;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    public function setBio($bio)
    {
        $this->bio = $bio;
        return $this;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;
        return $this;
    }

    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function setCreated($created)
    {
        $this->created = $created;
        return $this;
    }

    public function setModificationDate($modificationDate)
    {
        $this->modificationDate = $modificationDate;
        return $this;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }
}