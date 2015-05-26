<?php

namespace FSi\FixturesBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FSi\DoctrineExtensions\Translatable\Mapping\Annotation as Translatable;
use FSi\DoctrineExtensions\Uploadable\Mapping\Annotation as Uploadable;

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
     * @ORM\Column(type="text", nullable=true)
     * @var string
     */
    private $description;

    /**
     * @ORM\Column(length=255, nullable=true)
     * @Uploadable\Uploadable(targetField="agreement")
     */
    protected $agreementKey;

    /**
     * @var \FSi\DoctrineExtensions\Uploadable\File|\SplFileInfo
     */
    protected $agreement;

    /**
     * @ORM\OneToMany(
     *     targetEntity="\FSi\FixturesBundle\Entity\File",
     *     mappedBy="eventTranslation",
     *     cascade="all",
     *     orphanRemoval=true
     * )
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $files;

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

    public function __construct()
    {
        $this->files = new ArrayCollection();
    }

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
     */
    public function setName($name)
    {
        $this->name = (string)$name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getAgreementKey()
    {
        return $this->agreementKey;
    }

    /**
     * @param mixed $agreementKey
     */
    public function setAgreementKey($agreementKey)
    {
        $this->agreementKey = $agreementKey;
    }

    /**
     * @return \FSi\DoctrineExtensions\Uploadable\File|\SplFileInfo
     */
    public function getAgreement()
    {
        return $this->agreement;
    }

    /**
     * @param \FSi\DoctrineExtensions\Uploadable\File|\SplFileInfo $agreement
     */
    public function setAgreement($agreement)
    {
        $this->agreement = $agreement;
    }

    /**
     * @param $locale
     */
    public function setLocale($locale)
    {
        $this->locale = (string)$locale;
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

    /**
     * @return ArrayCollection
     */
    public function getFiles()
    {
        return $this->files;
    }

    public function addFile(File $file)
    {
        if ($this->files->contains($file)) {
            $this->files->add($file);
            $file->setEventTranslation($this);
        }
    }

    public function removeFile(File $file)
    {
        $this->files->removeElement($file);
        $file->setEventTranslation(null);
    }
}
