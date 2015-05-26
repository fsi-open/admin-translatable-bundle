<?php

namespace FSi\FixturesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FSi\DoctrineExtensions\Uploadable\Mapping\Annotation as Uploadable;

/**
 * @ORM\Entity
 */
class File
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer $id
     */
    private $id;

    /**
     * @ORM\Column(length=255, nullable=true)
     * @Uploadable\Uploadable(targetField="agreement")
     */
    private $file;

    /**
     * @var EventTranslation
     *
     * @ORM\ManyToOne(targetEntity="EventTranslation", inversedBy="files")
     */
    private $eventTranslation;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return EventTranslation
     */
    public function getEventTranslation()
    {
        return $this->eventTranslation;
    }

    /**
     * @param EventTranslation $eventTranslation
     */
    public function setEventTranslation($eventTranslation)
    {
        $this->eventTranslation = $eventTranslation;
    }
}
