<?php

namespace FSi\FixturesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FSi\DoctrineExtensions\Translatable\Mapping\Annotation as Translatable;

/**
 * @ORM\Entity
 */
class EventsTranslation
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
     * @ORM\Column(type="string", length=2)
     * @var string
     */
    private $locale;

    /**
     * @ORM\Column(type="text")
     * @var string
     */
    private $name;

    /**
     * @ORM\ManyToOne(
     *          targetEntity="\FSi\FixturesBundle\Entity\Events",
     *          inversedBy="translations"
     * )
     * @ORM\JoinColumn(
     *          name="events",
     *          referencedColumnName="id"
     * )
     *
     * @var \FSi\FixturesBundle\Entity\Events
     */
    private $events;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \FSi\FixturesBundle\Entity\Events $events
     */
    public function setHeader(Events $events)
    {
        $this->events = $events;
    }

    /**
     * @return \FSi\FixturesBundle\Entity\Events
     */
    public function getHeader()
    {
        return $this->events;
    }

    /**
     * @param $name
     * @return \FSi\FixturesBundle\Entity\EventsTranslation
     */
    public function setName($name)
    {
        $this->name = (string)$name;
        return $this;
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
     * @return \FSi\FixturesBundle\Entity\EventsTranslation
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

}
