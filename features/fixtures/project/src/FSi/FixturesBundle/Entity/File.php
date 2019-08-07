<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\FixturesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FSi\Bundle\DoctrineExtensionsBundle\Validator\Constraints as UploadableAssert;
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
     * @UploadableAssert\File()
     * @var \FSi\DoctrineExtensions\Uploadable\File|\SplFileInfo
     */
    private $file;

    /**
     * @ORM\Column(length=255, nullable=true)
     * @Uploadable\Uploadable(targetField="file")
     * @var string
     */
    private $fileKey;

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
     * @return \FSi\DoctrineExtensions\Uploadable\File|\SplFileInfo
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param \FSi\DoctrineExtensions\Uploadable\File|\SplFileInfo $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return string
     */
    public function getFileKey()
    {
        return $this->fileKey;
    }

    /**
     * @param string $fileKey
     */
    public function setFileKey($fileKey)
    {
        $this->fileKey = $fileKey;
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
