<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

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
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer
     */
    private $id;

    /**
     * @Translatable\Locale
     * @ORM\Column(length=2)
     * @var string
     */
    private $locale;

    /**
     * @ORM\Column
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @var string
     */
    private $description;

    /**
     * @ORM\Column(nullable=true)
     * @Uploadable\Uploadable(targetField="agreement")
     */
    private $agreementKey;

    /**
     * @var \FSi\DoctrineExtensions\Uploadable\File|\SplFileInfo
     */
    private $agreement;

    /**
     * @ORM\OneToMany(
     *     targetEntity="File",
     *     mappedBy="eventTranslation",
     *     cascade={"persist", "remove"},
     *     orphanRemoval=true
     * )
     * @var ArrayCollection
     */
    private $files;

    /**
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="translations")
     *
     * @var Event
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
     * @param $name
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
        $this->locale = (string) $locale;
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
        if (!$this->files->contains($file)) {
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
