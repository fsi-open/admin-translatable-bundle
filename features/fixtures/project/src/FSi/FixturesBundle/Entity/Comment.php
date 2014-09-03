<?php

namespace FSi\FixturesBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FSi\DoctrineExtensions\Translatable\Mapping\Annotation as Translatable;

/**
 * @ORM\Entity
 */
class Comment
{
    /**
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer $id
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="\FSi\FixturesBundle\Entity\Events", inversedBy="comments")
     * @ORM\JoinColumn(name="events", referencedColumnName="id")
     * @var \FSi\FixturesBundle\Entity\Events
     */
    private $events;

    /**
     * @Translatable\Translatable(mappedBy="translations")
     * @var string
     */
    private $text;

    /**
     * @Translatable\Locale
     * @var string
     */
    private $locale;

    /**
     * @ORM\OneToMany(
     *          targetEntity="\FSi\FixturesBundle\Entity\CommentTranslation",
     *          mappedBy="comment",
     *          indexBy="locale"
     * )
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $translations;

    public function __construct()
    {
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
    public function setText($name)
    {
        $this->text = $name;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param \FSi\FixturesBundle\Entity\Events $event
     */
    public function setEvent(Events $events)
    {
        $this->events = $events;
    }

    /**
     * @return \FSi\FixturesBundle\Entity\Events
     */
    public function getEvent()
    {
        return $this->events;
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
