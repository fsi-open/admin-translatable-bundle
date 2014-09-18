<?php

namespace FSi\FixturesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FSi\DoctrineExtensions\Translatable\Mapping\Annotation as Translatable;

/**
 * @ORM\Entity
 */
class CommentTranslation
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
    private $text;

    /**
     * @ORM\ManyToOne(targetEntity="\FSi\FixturesBundle\Entity\Comment", inversedBy="translations")
     * @ORM\JoinColumn(name="comment", referencedColumnName="id")
     *
     * @var \FSi\FixturesBundle\Entity\Comment
     */
    private $comment;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \FSi\FixturesBundle\Entity\Comment $comment
     */
    public function setComment(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return \FSi\FixturesBundle\Entity\Comment
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param $name
     */
    public function setText($name)
    {
        $this->text = (string)$name;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
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

}
