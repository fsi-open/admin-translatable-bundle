<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi;

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

use function sprintf;

class AppKernel extends Kernel
{
    public function registerBundles(): array
    {
        return [
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
            new \Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new \Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new \FSi\Bundle\DataSourceBundle\DataSourceBundle(),
            new \FSi\Bundle\DataGridBundle\DataGridBundle(),
            new \FSi\Bundle\AdminBundle\FSiAdminBundle(),
            new \FSi\Bundle\DoctrineExtensionsBundle\FSiDoctrineExtensionsBundle(),
            new \Knp\Bundle\GaufretteBundle\KnpGaufretteBundle(),
            new \FSi\Bundle\ResourceRepositoryBundle\FSiResourceRepositoryBundle(),
            new \FSi\Bundle\AdminTranslatableBundle\FSiAdminTranslatableBundle(),
            new \FOS\CKEditorBundle\FOSCKEditorBundle(),

            new \FSi\FixturesBundle\FSiFixturesBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(sprintf('%s/../config/config.yml', __DIR__));
    }

    public function getCacheDir(): string
    {
        return $this->getProjectDir() . '/var/cache';
    }

    public function getLogDir(): string
    {
        return $this->getProjectDir() . '/var/logs';
    }
}
