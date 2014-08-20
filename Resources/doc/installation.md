# Installation

##1. Composer

Add to composer.json

```
"require": {
        "fsi/admin-translatable-bundle": "dev-master",
        "fsi/doctrine-extensions": "dev-master as 1.0.x-dev",
        "fsi/doctrine-extensions-bundle": "dev-master@dev"
}
```

## 2. Register bundles

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Knp\Bundle\MenuBundle\KnpMenuBundle(),
        new FSi\Bundle\DataSourceBundle\DataSourceBundle(),
        new FSi\Bundle\DataGridBundle\DataGridBundle(),
        new FSi\Bundle\AdminBundle\FSiAdminBundle(),
        new FSi\Bundle\DoctrineExtensionsBundle\FSiDoctrineExtensionsBundle(),
        new FSi\Bundle\AdminTranslatableBundle\FSiAdminTranslatableBundle(),
    );
}
```

## 3. Set route

```
# app/config/routing.yml

admin_translatable:
    resource: "@FSiAdminTranslatableBundle/Resources/config/routing/admin.yml"
    prefix: /admin
```

##4. Configure doctrine extension bundle

```
# app/config/config.yml

fsi_doctrine_extensions:
    orm:
        default:
            translatable: true
```

##5. Enable translations

```
# app/config/config.yml

framework:
    translator:      { fallback: %locale% }
```

##6. Set translatable locales

```
# app/config/config.yml

fsi_admin_translatable:
    locales:
      - pl
      - en
      - de
```

Now you could create [translatable admin elements](admin_element.md)
