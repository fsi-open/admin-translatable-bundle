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

/**
 * @ORM\Entity(repositoryClass="FSi\DoctrineExtensions\Translatable\Entity\Repository\TranslatableRepository")
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
     * @ORM\ManyToOne(targetEntity="\FSi\FixturesBundle\Entity\Event", inversedBy="comments")
     * @ORM\JoinColumn(name="events", referencedColumnName="id")
     * @var \FSi\FixturesBundle\Entity\Event
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
     * @param \FSi\FixturesBundle\Entity\Event $event
     */
    public function setEvent(Event $events)
    {
        $this->events = $events;
    }

    /**
     * @return \FSi\FixturesBundle\Entity\Event
     */
    public function getEvent()
    {
        return $this->events;
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
