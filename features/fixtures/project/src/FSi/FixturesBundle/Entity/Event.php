<?php

namespace FSi\FixturesBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FSi\DoctrineExtensions\Translatable\Mapping\Annotation as Translatable;

/**
 * @ORM\Entity(repositoryClass="\FSi\DoctrineExtensions\Translatable\Entity\Repository\TranslatableRepository")
 */
class Event
{
    /**
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer $id
     */
    private $id;

    /**
     * @Translatable\Locale
     * @var string
     */
    private $locale;

    /**
     * @Translatable\Translatable(mappedBy="translations")
     * @var string
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="\FSi\FixturesBundle\Entity\Comment", mappedBy="events", cascade="all", orphanRemoval=true)
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $comments;

    /**
     * @ORM\OneToMany(
     *          targetEntity="\FSi\FixturesBundle\Entity\EventTranslation",
     *          mappedBy="event",
     *          indexBy="locale",
     *          orphanRemoval=true
     * )
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $translations;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->translations = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $locale
     * @return \FSi\FixturesBundle\Entity\Events
     */
    public function setLocale($locale)
    {
        $this->locale = (string)$locale;
        return $this;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @return ArrayCollection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param \FSi\FixturesBundle\Entity\Comment $comment
     */
    public function addComment(Comment $comment)
    {
        $this->comments->add($comment);
        $comment->setEvent($this);
    }

    /**
     * @param \FSi\FixturesBundle\Entity\Comment $comment
     */
    public function removeComment(Comment $comment)
    {
        $this->comments->removeElement($comment);
        $comment->setEvent(null);
    }

    /**
     * @return ArrayCollection
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * @param string $locale
     * @return bool
     */
    public function hasTranslation($locale)
    {
        return isset($this->translations[$locale]);
    }

    /**
     * @param string $locale
     * @return null|string
     */
    public function getTranslation($locale)
    {
        if ($this->hasTranslation($locale)) {
            return $this->translations[$locale];
        } else {
            return null;
        }
    }
}
