<?php declare(strict_types=1);

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserAboutRepository")
 * @ORM\Table(
 *     name="users_about",
 *     indexes={
 *         @ORM\Index(name="user", columns={"user"}),
 *         @ORM\Index(name="user_item_value", columns={"user","item","value"}),
 *         @ORM\Index(name="item", columns={"item"})
 *     })
 */
class UserAbout
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="bigint", options={"unsigned": true})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="abouts", cascade={"persist","remove"})
     * @ORM\JoinColumn(nullable=false, name="user", referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;

    /**
     * @ORM\Column(type="string", columnDefinition="ENUM('country','firstname','state')")
     */
    private $item;

    /**
     * @ORM\Column(type="string", length=250)
     */
    private $value;

    /**
     * @ORM\Column(type="datetime", columnDefinition="TIMESTAMP")
     */
    private $upDate;


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getItem(): string
    {
        return $this->item;
    }

    /**
     * @param mixed $item
     */
    public function setItem($item): void
    {
        $this->item = $item;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value): void
    {
        $this->value = $value;
    }
}
