<?php

namespace FSi\FixturesBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FSi\DoctrineExtensions\Translatable\Mapping\Annotation as Translatable;
use FSi\Bundle\DoctrineExtensionsBundle\Validator\Constraints as UploadableAssert;

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
     * @Translatable\Translatable(mappedBy="translations")
     * @var string
     */
    private $description;

    /**
     * @Translatable\Translatable(mappedBy="translations")
     * @UploadableAssert\File()
     * @var \FSi\DoctrineExtensions\Uploadable\File|\SplFileInfo
     */
    protected $agreement;

    /**
     * @ORM\OneToMany(targetEntity="\FSi\FixturesBundle\Entity\Comment", mappedBy="events", cascade="all", orphanRemoval=true)
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $comments;

    /**
     * @Translatable\Translatable(mappedBy="translations")
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $files;

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

    public function getAgreement()
    {
        return $this->agreement;
    }

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
