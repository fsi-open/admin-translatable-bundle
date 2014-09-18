<?php

namespace FSi\FixturesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FSi\DoctrineExtensions\Translatable\Mapping\Annotation as Translatable;

/**
 * @ORM\Entity
 */
class EventTranslation
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
     *          targetEntity="\FSi\FixturesBundle\Entity\Event",
     *          inversedBy="translations"
     * )
     * @ORM\JoinColumn(
     *          name="events",
     *          referencedColumnName="id"
     * )
     *
     * @var \FSi\FixturesBundle\Entity\Event
     */
    private $event;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \FSi\FixturesBundle\Entity\Event $events
     */
    public function setHeader(Event $events)
    {
        $this->event = $events;
    }

    /**
     * @return \FSi\FixturesBundle\Entity\Event
     */
    public function getHeader()
    {
        return $this->event;
    }

    /**
     * @param $name
     * @return \FSi\FixturesBundle\Entity\EventTranslation
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
     * @return \FSi\FixturesBundle\Entity\EventTranslation
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
     * @return Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param Event $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }
}
