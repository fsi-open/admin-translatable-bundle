<?php

namespace FSi\FixturesBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use FSi\DoctrineExtensions\Translatable\Mapping\Annotation as Translatable;
use FSi\Bundle\DoctrineExtensionsBundle\Validator\Constraints as UploadableAssert;

/**
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="\FSi\DoctrineExtensions\Translatable\Entity\Repository\TranslatableRepository")
 */
class Event
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
    private $agreement;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="events", cascade="all", orphanRemoval=true)
     * @var ArrayCollection
     */
    private $comments;

    /**
     * @Translatable\Translatable(mappedBy="translations")
     * @var ArrayCollection
     */
    private $files;

    /**
     * @ORM\OneToMany(
     *      targetEntity="EventTranslation",
     *      mappedBy="event",
     *      indexBy="locale",
     *      orphanRemoval=true
     * )
     * @var ArrayCollection
     */
    private $translations;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->files = new ArrayCollection();
        $this->translations = new ArrayCollection();
    }

    /**
     * @ORM\PostLoad()
     */
    public function postLoad(LifecycleEventArgs $eventArgs)
    {
        $this->files = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }

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

    public function setLocale($locale)
    {
        $this->locale = (string) $locale;
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function getComments()
    {
        return $this->comments;
    }

    public function addComment(Comment $comment)
    {
        $this->comments->add($comment);
        $comment->setEvent($this);
    }

    public function removeComment(Comment $comment)
    {
        $this->comments->removeElement($comment);
        $comment->setEvent(null);
    }

    public function getTranslations()
    {
        return $this->translations;
    }

    public function hasTranslation($locale)
    {
        return isset($this->translations[$locale]);
    }

    public function getTranslation($locale)
    {
        return $this->hasTranslation($locale) ? $this->translations[$locale] : null;
    }

    public function getFiles()
    {
        return $this->files;
    }

    public function addFile(File $file)
    {
        if (!$this->files->contains($file)) {
            $this->files->add($file);
        }
    }

    public function removeFile(File $file)
    {
        $this->files->removeElement($file);
    }
}
