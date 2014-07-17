## Translatable Admin Object Class

```php
<?php
// src/FSi/Bundle/DemoBundle/Admin/User

namespace FSi\Bundle\DemoBundle\Admin;

use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableCRUDElement;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use Symfony\Component\Form\FormFactoryInterface;

class User extends TranslatableCRUDElement
{
    /**
     * {@inheritdoc}
     */
    public function getClassName()
    {
        return 'FSiDemoBundle:User'; // Doctrine class name
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'admin_user'; // id is used in url generation http://domain.com/admin/{locale}/{id}/list
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'User'; // names are translated in twig so you can use translation key as name
    }

    /**
     * {@inheritdoc}
     */
    protected function initDataSource(DataSourceFactoryInterface $factory)
    {
        /* @var $datasource \FSi\Component\DataSource\DataSource */
        $datasource = $factory->createDataSource('doctrine', array(
            'entity' => $this->getClassName()
        ), 'datasource');

        $datasource->setMaxResults(10);;

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
        $form = $factory->create('form', $data, array(
            'data_class' => 'FSi\Bundle\DemoBundle\Entity\User' // this option is important for create form
        ));

        $form->add('email', 'email');
        $form->add('username', 'text');

        // Here you should add some fields into form
        // To get more information about Symfony form you should visit http://symfony.com/doc/current/book/forms.html

        return $form;
    }
}
```

## Configure Datagrid

```
# src/FSi/Bundle/DemoBundle/Resources/config/datagrid/admin_user.yml

columns:
    email:
        type: email
        options:
            label: Email address
    username:
        type: text
        options:
            label: Username
    actions:
        type: action
        options:
            label: Actions
            field_mapping: [ id ]
            actions:
                edit:
                    route_name: "fsi_admin_translatable_crud_edit"
                    additional_parameters: { element: admin_user }
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

    <service id="fsi_demo_bundle.admin.user" class="FSi\Bundle\DemoBundle\Admin\User">
        <tag name="admin.element"/>
    </service>

</services>
</container>

```

## Entity with translatable properties

Read more about [FSi Translatable Doctrine Extension](https://github.com/fsi-open/doctrine-extensions/blob/master/doc/translatable.md)

```php
<?php
// src/FSi/Bundle/DemoBundle/Entity/User

namespace FSi\Bundle\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FSi\DoctrineExtensions\Translatable\Mapping\Annotation as Translatable;

/**
 * @ORM\Entity(repositoryClass="\FSi\DoctrineExtensions\Translatable\Entity\Repository\TranslatableRepository")
 */
class User
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
     * @var string
     */
    private $locale;

    /**
     * @Translatable\Translatable(mappedBy="translations")
     * @var string
     */
    private $email;

    /**
     * @Translatable\Translatable(mappedBy="translations")
     * @var string
     */
    private $username;

    /**
     * @ORM\OneToMany(targetEntity="UserTranslation", mappedBy="user", indexBy="locale")
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    private $translations;

    public function __construct()
    {
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function setEmail($email)
    {
        $this->email = (string)$email;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setUsername($username)
    {
        $this->username = (string)$username;
        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setLocale($locale)
    {
        $this->locale = (string)$locale;
        return $this;
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
        if ($this->hasTranslation($locale)) {
            return $this->translations[$locale];
        } else {
            return null;
        }
    }
}
```

##Related translatable Entity

```php
<?php
// src/FSi/Bundle/DemoBundle/Entity/User

namespace FSi\Bundle\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FSi\DoctrineExtensions\Translatable\Mapping\Annotation as Translatable;

/**
 * @ORM\Entity
 */
class UserTranslation
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
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $username;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="translations")
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    private $user;

    public function setEmail($email)
    {
        $this->email = (string)$email;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setUsername($username)
    {
        $this->username = (string)$username;
        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setLocale($locale)
    {
        $this->locale = (string)$locale;
        return $this;
    }

    public function getLocale()
    {
        return $this->locale;
    }
}
```

