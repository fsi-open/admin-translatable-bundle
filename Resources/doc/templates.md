# Custom templates

## Global configuration
You can set templates for admin translatable bundle by setting `fsi_admin_translatable.templates.*` in app configuration.

Available templates keys are:
* `form`
* `list`
* `crud_form`
* `crud_list`
* `display`
* `resource`

Default value for all these options are same as coresponding options from admin-bundle.

Example configuration with custom templates:
```yml
fsi_admin_translatable:
  locales:
    - pl
    - en
  templates:
    form: '@CustomBundle/form.html.twig'
    list: '@CustomBundle/list.html.twig'
    crud_form: '@CustomBundle/crud_form.html.twig'
    crud_list: '@CustomBundle/crud_list.html.twig'
    display: '@CustomBundle/display.html.twig'
    resource: '@CustomBundle/resource.html.twig'
```
## Single element configuration
You can also set custom template for single admin element as [described in admin-bundle documentation](https://github.com/fsi-open/admin-bundle/blob/master/Resources/doc/admin_element_crud.md#5-admin-element-options)
