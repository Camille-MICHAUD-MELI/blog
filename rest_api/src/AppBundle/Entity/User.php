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
 * @ORM\Entity
 * @ORM\Table(name="users",
 *      uniqueConstraints={@ORM\UniqueConstraint(name="users_pseudo_unique",columns={"pseudo"})}
 * )
 * @UniqueEntity("pseudo")
 * @ORM\HasLifecycleCallbacks 
 * @UniqueEntity("email")
 */
class User extends \FOS\UserBundle\Model\User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    protected $admin;

    /**
     * @ORM\OneToMany(targetEntity="Message", mappedBy="user")
     * @var Message[]
     */
    protected $message;

    /**
     *  
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="user")
     * @var Comment[]
     */
    protected $comment;
    
    /**
     * @ORM\OneToMany(targetEntity="Fav", mappedBy="user")
     * @var Fav[]
     */
    protected $fav;

    /**
     * @ORM\Column(type="string")
     * @ORM\Column(unique=true)
     */
    protected $pseudo;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $bio;
    
    /**
     * @ORM\Column(type="string", nullable=false)
     * @ORM\Column(unique=true)
     */
    protected $email;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $phone;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $address;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $city;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $zipcode;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $country;

    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var \DateTime
     * @ORM\Column(name="last_modification", type="datetime", nullable=true)
     */
    public $modificationDate;

    public function getCreated()
    {
        return $this->created;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getPseudo()
    {
        return $this->pseudo;
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

    public function getMessage()
    {
        return $this->message;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function getFav()
    {
        return $this->fav;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setPseudo($pseudo)
    {
        $this->pseudo = $pseudo;
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

    public function setAdmin($admin)
    {
        $this->admin = $admin;
        return $this;
    }
}