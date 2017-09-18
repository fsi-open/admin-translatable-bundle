# CHANGELOG for version 3.0

## Symfony3 support

As of this version, both Symfony 2 and 3 are suported.

## Dropped support for PHP below 7.1

To be able to fully utilize new functionality introduced in 7.1, we have decided 
to only support PHP versions equal or higher to it.

## Incompatibility with FSi Open Resource Repository 1.x

Since in branch 2.x of that repository the `FSi\Bundle\ResourceRepositoryBundle\Repository\MapBuilder`
class' interface has changed, `FSi\Bundle\AdminTranslatableBundle\Repository\TranslatableMapBuilder`
class was updated to mirror these changes. That makes it incompatible with 1.x branch.
