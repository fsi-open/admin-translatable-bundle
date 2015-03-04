<?php
/**
 * (c) Omino s.c.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminTranslatableBundle\Form;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use FSi\DoctrineExtensions\Translatable\Mapping\ClassMetadata;
use FSi\DoctrineExtensions\Translatable\TranslatableListener;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

class TranslatableExtension extends AbstractTypeExtension
{
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    /**
     * @var TranslatableListener
     */
    private $translatableListener;

    /**
     * @param ManagerRegistry $managerRegistry
     * @param TranslatableListener $translatableListener
     */
    public function __construct(
        ManagerRegistry $managerRegistry,
        TranslatableListener $translatableListener
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->translatableListener = $translatableListener;
    }

    /**
     * @inheritdoc
     */
    public function getExtendedType()
    {
        return 'form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $propertyPath = $form->getPropertyPath();
        if (!$propertyPath) {
            return;
        }

        for ($parent = $form; $parent !== null; $parent = $parent->getParent()) {
            if ($parent->getConfig()->getInheritData()) {
                continue;
            }

            $class = $parent->getConfig()->getDataClass();
            if (!$class) {
                continue;
            }

            if ($this->isClassTranslatable($class)) {
                $view->vars['translatable'] = false;

                $translatableProperties = $this->getTranslatableMetadata($class)->getTranslatableProperties();
                foreach ($translatableProperties as $translationProperties) {
                    if (isset($translationProperties[(string) $propertyPath])) {
                        $view->vars['translatable'] = true;
                        break 2;
                    }
                }
            }
        }
    }

    /**
     * @param string $class
     * @return bool
     */
    private function isClassTranslatable($class)
    {
        if (null === $this->getManagerForClass($class)) {
            return false;
        }

        $translatableMetadata = $this->getTranslatableMetadata($class);
        if (null === $translatableMetadata) {
            return false;
        }

        return $translatableMetadata->hasTranslatableProperties();
    }

    /**
     * @param string $class
     * @return ObjectManager|null
     */
    private function getManagerForClass($class)
    {
        return $this->managerRegistry->getManagerForClass($class);
    }

    /**
     * @param string $class
     * @return ClassMetadata
     */
    private function getTranslatableMetadata($class)
    {
        return $this->translatableListener->getExtendedMetadata(
            $this->getManagerForClass($class),
            $class
        );
    }
}
