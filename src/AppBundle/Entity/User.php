<?php declare(strict_types=1);

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\Table(
 *     name="users",
 *     indexes={
 *         @ORM\Index(name="email_password", columns={"email", "password"}),
 *         @ORM\Index(name="role_reg_date", columns={"role", "reg_date"})
 *     })
 */
class User
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", options={"unsigned": true})
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true, length=100, nullable=false)
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     */
    private $password;

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     */
    private $role;

    /**
     * @ORM\Column(type="datetime", columnDefinition="TIMESTAMP", nullable=false)
     */
    private $regDate;

    /**
     * @ORM\Column(type="datetime", columnDefinition="TIMESTAMP", nullable=false, options={"default": "0000-00-00 00:00:00"})
     */
    private $lastVisit;

    /**
     * @ORM\OneToMany(targetEntity="UserAbout", mappedBy="user", cascade={"persist","remove"}, fetch="EAGER")
     */
    private $abouts;


    public function __construct()
    {
        $this->abouts = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    /**
     * @return mixed
     */
    public function getRegDate()
    {
        return $this->regDate;
    }

    /**
     * @param mixed $regDate
     */
    public function setRegDate($regDate): void
    {
        $this->regDate = $regDate;
    }

    /**
     * @return mixed
     */
    public function getLastVisit()
    {
        return $this->lastVisit;
    }

    /**
     * @param mixed $lastVisit
     */
    public function setLastVisit($lastVisit): void
    {
        $this->lastVisit = $lastVisit;
    }

    /**
     * @return ArrayCollection|UserAbout[]
     */
    public function getAbouts()
    {
        return $this->abouts;
    }
}
