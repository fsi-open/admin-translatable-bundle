<?php

namespace FSi\Bundle\AdminTranslatableBundle\Behat\Context;

use Behat\Behat\Context\BehatContext;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Doctrine\ORM\Tools\SchemaTool;
use FSi\FixturesBundle\Entity\Events;
use Symfony\Component\HttpKernel\KernelInterface;

class DataContext extends BehatContext implements KernelAwareInterface
{
    /**
     * @var KernelInterface
     */
    protected $kernel;

    /**
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @BeforeScenario
     */
    public function createDatabase()
    {
        $this->deleteDatabaseIfExist();
        $metadata = $this->getDoctrine()->getManager()->getMetadataFactory()->getAllMetadata();
        $tool = new SchemaTool($this->getDoctrine()->getManager());
        $tool->createSchema($metadata);
    }

    /**
     * @AfterScenario
     */
    public function deleteDatabaseIfExist()
    {
        $dbFilePath = $this->kernel->getRootDir() . '/data.sqlite';

        if (file_exists($dbFilePath)) {
            unlink($dbFilePath);
        }
    }

    /**
     * @return \Doctrine\Bundle\DoctrineBundle\Registry
     */
    protected function getDoctrine()
    {
        return $this->kernel->getContainer()->get('doctrine');
    }


    /**
     * @Given /^there are (\d+) events in each language$/
     */
    public function thereAreEventsInEachLanguage($amount)
    {
        $locales = $this->kernel->getContainer()->getParameter('fsi_admin_translatable.locales');

        for ($eventNumber = 1; $eventNumber <= $amount; $eventNumber++) {
            $this->addEvent($eventNumber, $locales);
        }
    }

    private function addEvent($id, $locales)
    {
        $event = new Events();

        foreach ($locales as $locale) {
            $event->setLocale($locale);
            $event->setName(sprintf('Name %s %d', $locale, $id));
            $this->getDoctrine()->getManager()->persist($event);
            $this->getDoctrine()->getManager()->flush();
        }
    }
}
