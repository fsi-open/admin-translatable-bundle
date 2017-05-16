## Translatable Admin Object Class

```php
<?php
// src/FSi/Bundle/DemoBundle/Admin/News

namespace FSi\Bundle\DemoBundle\Admin;

use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableCRUDElement;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use Symfony\Component\Form\FormFactoryInterface;

class News extends TranslatableCRUDElement
{
    /**
     * {@inheritdoc}
     */
    public function getClassName()
    {
        return 'FSi\Bundle\DemoBundle\Entity\News'; // Doctrine class name
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'admin_news'; // id is used in url generation http://domain.com/admin/{locale}/{id}/list
    }

    /**
     * {@inheritdoc}
     */
    protected function initDataSource(DataSourceFactoryInterface $factory)
    {
        /* @var $datasource \FSi\Component\DataSource\DataSource */
        $datasource = $factory->createDataSource('doctrine', [
            'entity' => $this->getClassName()
        ], 'datasource');

        $datasource->setMaxResults(10);

        // Here you can add some fields or filters into datasource
        // To get more information about datasource you should visit https://github.com/fsi-open/datasource

        return $datasource;
    }

    /**
     * {@inheritdoc}
     */
    protected function initDataGrid(DataGridFactoryInterface $factory)
    {
        /* @var $datagrid \FSi\Component\DataGrid\DataGrid */
        $datagrid = $factory->createDataGrid($this->getId() );

        // Here you can add some columns into datagrid
        // To get more information about datagrid you should visit https://github.com/fsi-open/datagrid

        return $datagrid;
    }

    /**
     * {@inheritdoc}
     */
    protected function initForm(FormFactoryInterface $factory, $data = null)
    {
        $form = $factory->create('form', $data, [
            'data_class' => 'FSi\Bundle\DemoBundle\Entity\News' // this option is important during form creation
        ]);

        $form->add('title', 'text');
        $form->add('content', 'text');
        $form->add('createdAt', 'date');

        // Here you should add some fields into form
        // To get more information about Symfony form you should visit http://symfony.com/doc/current/book/forms.html

        return $form;
    }
}
```

## Configure Datagrid

```yaml
# src/FSi/Bundle/DemoBundle/Resources/config/datagrid/admin_news.yml

columns:
    title:
        type: text
        options:
            label: Title
    content:
        type: text
        options:
            label: Content
    created_at:
        type: datetime
        options:
            label: Created At
            datetime_format: 'Y-m-d'
    actions:
        type: action
        options:
            label: Actions
            field_mapping: [ id ]
            actions:
                edit:
                    route_name: "fsi_admin_translatable_crud_edit"
                    additional_parameters: { element: admin_news }
                    parameters_field_mapping: { id: id }

```

## User CRUD service

Every single admin element must be registered as a service with ``admin.element`` tag.
Optionally you can also use tag ``alias`` attribute to assign element to the group.
Group name same as element name is translated so that you can use translation key as a group name (alias)

```xml

<?xml version="1.0" ?>

<container xmlns="http://symfony.com/schema/dic/services"
           xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
           xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">
<services>

    <service id="fsi_demo_bundle.admin.news" class="FSi\Bundle\DemoBundle\Admin\News">
        <tag name="admin.element"/>
    </service>

</services>
</container>

```

## Entity with translatable properties

Read more about [FSi Translatable Doctrine Extension](https://github.com/fsi-open/doctrine-extensions/blob/master/doc/translatable.md)

```php
<?php
// src/FSi/Bundle/DemoBundle/Entity/News

namespace FSi\Bundle\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FSi\DoctrineExtensions\Translatable\Mapping\Annotation as Translatable;

/**
 * @ORM\Entity(repositoryClass="\FSi\DoctrineExtensions\Translatable\Entity\Repository\TranslatableRepository")
 */
class News
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
    private $title;

    /**
     * @Translatable\Translatable(mappedBy="translations")
     * @var string
     */
    private $content;

    /**
     * @ORM\Column(type="date")
     * @var date
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(
     *     targetEntity="NewsTranslation",
     *     mappedBy="news",
     *     indexBy="locale",
     *     cascade={"persist", "remove"},
     *     orphanRemoval=true
     * )
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $translations;

    public function __construct()
    {
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function setTitle($title)
    {
        $this->title = (string) $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setContent($content)
    {
        $this->content = (string) $content;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setLocale($locale)
    {
        $this->locale = (string) $locale;
    }

    public function getLocale()
    {
        return $this->locale;
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
}
```

## Related translatable Entity

```php
<?php
// src/FSi/Bundle/DemoBundle/Entity/NewsTranslation

namespace FSi\Bundle\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FSi\DoctrineExtensions\Translatable\Mapping\Annotation as Translatable;

/**
 * @ORM\Entity
 */
class NewsTranslation
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
     * @ORM\Column(type="string", length=2)
     * @var string
     */
    private $locale;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="News", inversedBy="translations")
     * @ORM\JoinColumn(name="news", referencedColumnName="id", onDelete="CASCADE")
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $news;

    public function setTitle($title)
    {
        $this->title = (string) $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setContent($content)
    {
        $this->content = (string) $content;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setLocale($locale)
    {
        $this->locale = (string) $locale;
    }

    public function getLocale()
    {
        return $this->locale;
    }
}
```
