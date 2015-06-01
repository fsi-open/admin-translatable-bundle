<?php

namespace FSi\Bundle\AdminTranslatableBundle\Behat\Context;

use Behat\Behat\Context\BehatContext;
use Behat\Behat\Exception\BehaviorException;
use Behat\Gherkin\Node\TableNode;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Doctrine\ORM\Tools\SchemaTool;
use FSi\FixturesBundle\Entity\Comment;
use FSi\FixturesBundle\Entity\Event;
use FSi\FixturesBundle\Entity\File;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

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
        $tool = new SchemaTool($this->getDoctrineManager());
        $tool->createSchema($metadata);
    }

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

    protected function getDoctrineManager()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @Given /^there are (\d+) events in each locale/
     */
    public function thereAreEventsInEachLocale($amount)
    {
        $locales = $this->kernel->getContainer()->getParameter('fsi_admin_translatable.locales');

        for ($i = 1; $i <= $amount; $i++) {
            $this->addEvent($i, $locales);
        }
    }

    /**
     * @Given /^there are (\d+) comments in each locale/
     */
    public function thereAreCommentsInEachLocale($amount)
    {
        $locales = $this->kernel->getContainer()->getParameter('fsi_admin_translatable.locales');

        for ($i = 1; $i <= $amount; $i++) {
            $this->addComment($i, $locales);
        }
    }

    private function addEvent($id, $locales)
    {
        $event = new Event();
        $manager = $this->getDoctrineManager();

        foreach ($locales as $locale) {
            $event->setLocale($locale);
            $event->setName(sprintf('Name %s %d', $locale, $id));
            $manager->persist($event);
            $manager->flush();
        }
    }

    private function addComment($id, $locales)
    {
        $manager = $this->getDoctrineManager();

        $comment = new Comment();

        foreach ($locales as $locale) {
            $comment->setLocale($locale);
            $comment->setText(sprintf('Comment text %s %d', $locale, $id));
            $manager->persist($comment);
            $manager->flush();
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
        $event->setAgreement(new \SplFileInfo(__DIR__ . '/../../features/fixtures/test_file.txt'));

        $manager = $this->getDoctrineManager();
        $manager->persist($event);
        $manager->flush();
        $manager->clear();
    }

    /**
     * @Given /^I add new comment with text "([^"]*)" to the event with name "([^"]*)" in "([^"]*)" locale$/
     */
    public function iAddNewCommentWithTextToTheEventWithNameInLocale($commentText, $eventName, $locale)
    {
        $manager = $this->getDoctrineManager();
        $event = $manager->getRepository('FSi\FixturesBundle\Entity\EventTranslation')
            ->findOneBy(array('name' => $eventName))
            ->getEvent();

        $comment = new Comment();
        $comment->setText($commentText);
        $comment->setLocale($locale);
        $comment->setEvent($event);

        $manager->persist($comment);
        $manager->flush();
    }

    /**
     * @Given /^I add new event with following values:$/
     */
    public function iAddNewEventWithFollowingValues(TableNode $values)
    {
        $manager = $this->getDoctrineManager();
        $event = new Event();
        $propertyAccess = PropertyAccess::createPropertyAccessor();

        foreach ($values->getHash() as $value) {
            $event->setLocale($value['locale']);
            $propertyAccess->setValue($event, $value['field'], $value['value']);

            $manager->persist($event);
            $manager->flush();
        }
    }

    /**
     * @Given /^I add new file to the event with name "([^"]*)" in "([^"]*)" locale$/
     */
    public function iAddNewFileToTheNewsWithNameInLocale($eventName, $locale)
    {
        $manager = $this->getDoctrineManager();
        $eventTranslation = $manager->getRepository('FSi\FixturesBundle\Entity\EventTranslation')
            ->findOneBy(array('name' => $eventName, 'locale' => $locale));

        $file = new File();
        $file->setFile(new \SplFileInfo(__DIR__ . '/../../features/fixtures/test_file.txt'));
        $file->setEventTranslation($eventTranslation);

        $manager->persist($file);
        $manager->flush();
    }

    /**
     * @Then /^It should be saved comment entity with text "([^"]*)"$/
     */
    public function itShouldBeSavedCommentEntityWithText($text)
    {
        $manager = $this->getDoctrineManager();
        $comment = $manager->getRepository('FSi\FixturesBundle\Entity\CommentTranslation')->findOneBy(array('text' => $text));

        if (null === $comment) {
            throw new BehaviorException(sprintf('Unable to find comment entity with text %s', $text));
        }
    }
}
