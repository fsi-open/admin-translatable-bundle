# Installation

##  Install Resource Repository Bundle

**This element type requires fsi/resource-repository-bundle.**
**You can read more about it [here](https://github.com/fsi-open/resource-repository-bundle)**

Add to your `composer.json` following lines

```json
"require": {
    "fsi/resource-repository-bundle" : "^1.1@dev" // or ^2.0@dev if you are using Symfony 3+
}
```

Update `AppKernel.php`

```php
public function registerBundles()
{
    $bundles = array(
        new Knp\Bundle\GaufretteBundle\KnpGaufretteBundle(),
        new FSi\Bundle\ResourceRepositoryBundle\FSiResourceRepositoryBundle(),
        // Admin Translatable Bundle
        ...
    );
}
```

## Create Resource entity

```php

<?php

namespace FSi\Bundle\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FSi\Bundle\ResourceRepositoryBundle\Model\Resource as BaseResource;

/**
 * @ORM\Entity(repositoryClass="FSi\Bundle\ResourceRepositoryBundle\Entity\ResourceRepository")
 * @ORM\Table(name="fsi_resource")
 */
class Resource extends BaseResource
{
}
```

## Modify app/config/config.yml

```yaml
# app/config/config.yml

fsi_resource_repository:
    resource_class: FSi\Bundle\DemoBundle\Entity\Resource
```

## Update database with following console command

```bash
$ bin/console doctrine:schema:update --force
```

# Create admin object class

```php
<?php

namespace FSi\Bundle\DemoBundle\Admin;

use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableResourceElement;

class MainPage extends TranslatableResourceElement
{
    /**
     * {@inheritdoc}
     */
    public function getKey()
    {
        return 'resources.main_page'; // must be a group type key
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'main_page';
    }

    /**
     * {@inheritdoc}
     */
    public function getClassName()
    {
        return 'FSi\Bundle\DemoBundle\Entity\Resource';
    }
}
```

# Configuration

## Resource Map

Let's assume we have following configuration in ``resource_map.yml``

Each field in translatable resource has ``translatable`` option. The default is ``false``

If you want the field to be translatable, you need to enable ``translatable`` option for this field.

```yml
resources:
    type: group
    main_page:
        type: group
        title:
            type: text
            form_options:
                label: Main page title
        content:
            translatable: true
            type: text
            form_options:
                label: Main page content
```

Now you can see that ``content`` field will be translated, but ``title`` will not.

## Main page resource service

Every single admin element must be registered as a service with ``admin.element`` tag.
Optionally you can also use tag ``alias`` attribute to assign element to the group.
Group name as element name is translated so that you can use translation key as a group name (alias)

```xml

<?xml version="1.0" ?>

<container xmlns="http://symfony.com/schema/dic/services"
           xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
           xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">
<services>

    <service id="fsi_demo_bundle.admin.main_page" class="FSi\Bundle\DemoBundle\Admin\MainPage">
        <tag name="admin.element"/>
    </service>

</services>
</container>

```
