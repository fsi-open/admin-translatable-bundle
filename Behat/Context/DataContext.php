<?php

namespace FSi\Bundle\AdminTranslatableBundle\Behat\Context;

use Behat\Behat\Context\BehatContext;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Doctrine\ORM\Tools\SchemaTool;
use FSi\FixturesBundle\Entity\Comment;
use FSi\FixturesBundle\Entity\Event;
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
     * @Given /^there are (\d+) events in each locale/
     */
    public function thereAreEventsInEachLocale($amount)
    {
        $locales = $this->kernel->getContainer()->getParameter('fsi_admin_translatable.locales');

        for ($eventNumber = 1; $eventNumber <= $amount; $eventNumber++) {
            $this->addEvent($eventNumber, $locales);
        }
    }

    private function addEvent($id, $locales)
    {
        $event = new Event();

        foreach ($locales as $locale) {
            $event->setLocale($locale);
            $event->setName(sprintf('Name %s %d', $locale, $id));
            $this->getDoctrine()->getManager()->persist($event);
            $this->getDoctrine()->getManager()->flush();
        }
    }

    /**
     * @Given /^default translatable locale is "([^"]*)"$/
     */
    public function defaultTranslatableLocaleIs($defaultLocale)
    {
        $this->kernel
            ->getContainer()
            ->get('fsi_doctrine_extensions.listener.translatable')
            ->setDefaultLocale($defaultLocale);
    }

    /**
     * @Given /^I add new event with name "([^"]*)" in "([^"]*)" locale$/
     */
    public function iAddNewEventWithNameInLocale($eventName, $locale)
    {
        $event = new Event();
        $event->setLocale($locale);
        $event->setName($eventName);
        $this->getDoctrine()->getManager()->persist($event);
        $this->getDoctrine()->getManager()->flush();
    }

    /**
     * @Given /^I add new comment with text "([^"]*)" to the news with name "([^"]*)" in "([^"]*)" locale$/
     */
    public function iAddNewCommentWithTextToTheNewsWithNameInLocale($commentText, $eventName, $locale)
    {
        $comment = new Comment();
        $comment->setText($commentText);
        $comment->setLocale($locale);
        $event = $this->getDoctrine()->getManager()
            ->getRepository('FSi\FixturesBundle\Entity\EventTranslation')
            ->findOneBy(array('name' => $eventName))
            ->getEvent();
        $comment->setEvent($event);
        $this->getDoctrine()->getManager()->persist($comment);
        $this->getDoctrine()->getManager()->flush();
    }
}
