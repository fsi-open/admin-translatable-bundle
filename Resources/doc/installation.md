# Installation

##1. Composer

Add this line to `composer.json`

```json
"require": {
        "fsi/admin-translatable-bundle": "^3.0@dev"
}
```

## 2. Register bundles

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = [
        // ...
        new Knp\Bundle\MenuBundle\KnpMenuBundle(),
        new FSi\Bundle\DataSourceBundle\DataSourceBundle(),
        new FSi\Bundle\DataGridBundle\DataGridBundle(),
        new FSi\Bundle\AdminBundle\FSiAdminBundle(),
        new FSi\Bundle\DoctrineExtensionsBundle\FSiDoctrineExtensionsBundle(),
        new FSi\Bundle\AdminTranslatableBundle\FSiAdminTranslatableBundle(),
    ];
}
```

## 3. Set route

```yaml
# app/config/routing.yml

admin_translatable:
    resource: "@FSiAdminTranslatableBundle/Resources/config/routing/admin.yml"
    prefix: /admin
```

##4. Configure doctrine extension bundle

```yaml
# app/config/config.yml

fsi_doctrine_extensions:
    orm:
        default:
            translatable: true
```

##5. Enable translations

```yaml
# app/config/config.yml

framework:
    translator:      { fallback: %locale% }
```

##6. Set translatable locales

```yaml
# app/config/config.yml

fsi_admin_translatable:
    locales:
      - pl
      - en
      - de
```

Next on, creating [translatable admin elements](admin_element.md).
