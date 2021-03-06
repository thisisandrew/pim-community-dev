# UPGRADE FROM 1.6 to 1.7

## Disclaimer

> Please check that you're using Akeneo PIM v1.6

> We're assuming that you created your project from the standard distribution

> This documentation helps to migrate projects based on the Community Edition and the Enterprise Edition

> Please perform a backup of your database before proceeding to the migration. You can use tools like [mysqldump](http://dev.mysql.com/doc/refman/5.1/en/mysqldump.html) and [mongodump](http://docs.mongodb.org/manual/reference/program/mongodump/).

> Please perform a backup of your codebase if you don't use a VCS (Version Control System).


## Migrate your system requirements

## Migrate your standard project

## Migrate your custom code

### Global updates for any project

#### Remove deprecated bundles from your AppKernel

Remove "new Oro\Bundle\UIBundle\OroUIBundle()" from your app/AppKernel.php
Remove "new Oro\Bundle\FormBundle\OroFormBundle()" from your app/AppKernel.php

#### Update references to moved `Pim\Bundle\ConnectorBundle\Reader` business classes

In order to be more precise about the roles our existing file iterators have we renamed some existing classed as existing file iterators would only supports only tabular file format like CSV and XLSX.

Please execute the following commands in your project folder to update the references you may have to these classes:
```
    find ./src/ -type f -print0 | xargs -0 sed -i 's/Pim\\Component\\Connector\\Reader\\File\\FileIterator/Pim\\Component\\Connector\\Reader\\File\\FlatFileIterator/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/pim_connector\.reader\.file\.file_iterator\.class/pim_connector\.reader\.file\.flat_file_iterator\.class/g'
```

#### Update references to the standardized `Pim\Component\Catalog\Normalizer\Standard` classes

In order to use the standard format, Structured Normalizers have been replaced by Standard Normalizers.
To call these normalizers via the Symfony Normalizer service, the key `standard` has to be filled. Example:

```
     $this->normalizer->normalize($entity, 'standard');
```

Originally, the Normalizer `Pim\Component\Catalog\Normalizer\Structured\GroupNormalizer` was used to normalize both Groups and Variant Groups.
This normalizer has been split in two distinct normalizers :

* `Pim\Component\Catalog\Normalizer\Standard\GroupNormalizer` class is used to normalize Groups
* `Pim\Component\Catalog\Normalizer\Standard\VariantGroupNormalizer` class is used to normalize Variant Groups

In order to use the good one, a proxy group normalizer `Pim\Component\Catalog\Normalizer\Standard\ProxyGroupNormalizer` has been created.
This proxy normalizer will be used  instead of `Pim\Component\Catalog\Normalizer\Structured\GroupNormalizer`.

The following command helps to migrate references to Normalizer classes or services :
```
    find ./src/ -type f -print0 | xargs -0 sed -i 's/Pim\\Component\\Catalog\\Normalizer\\Structured\\AssociationTypeNormalizer/Pim\\Component\\Catalog\\Normalizer\\Standard\\AssociationTypeNormalizer/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/Pim\\Component\\Catalog\\Normalizer\\Structured\\AttributeGroupNormalizer/Pim\\Component\\Catalog\\Normalizer\\Standard\\AttributeGroupNormalizer/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/Pim\\Component\\Catalog\\Normalizer\\Structured\\AttributeNormalizer/Pim\\Component\\Catalog\\Normalizer\\Standard\\AttributeNormalizer/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/Pim\\Component\\Catalog\\Normalizer\\Structured\\AttributeOptionNormalizer/Pim\\Component\\Catalog\\Normalizer\\Standard\\AttributeOptionNormalizer/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/Pim\\Component\\Catalog\\Normalizer\\Structured\\CategoryNormalizer/Pim\\Component\\Catalog\\Normalizer\\Standard\\CategoryNormalizer/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/Pim\\Component\\Catalog\\Normalizer\\Structured\\ChannelNormalizer/Pim\\Component\\Catalog\\Normalizer\\Standard\\ChannelNormalizer/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/Pim\\Component\\Catalog\\Normalizer\\Structured\\CurrencyNormalizer/Pim\\Component\\Catalog\\Normalizer\\Standard\\CurrencyNormalizer/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/Pim\\Component\\Catalog\\Normalizer\\Structured\\DateTimeNormalizer/Pim\\Component\\Catalog\\Normalizer\\Standard\\DateTimeNormalizer/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/Pim\\Component\\Catalog\\Normalizer\\Structured\\FamilyNormalizer/Pim\\Component\\Catalog\\Normalizer\\Standard\\FamilyNormalizer/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/Pim\\Component\\Catalog\\Normalizer\\Structured\\FileNormalizer/Pim\\Component\\Catalog\\Normalizer\\Standard\\FileNormalizer/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/Pim\\Component\\Catalog\\Normalizer\\Structured\\GroupNormalizer/Pim\\Component\\Catalog\\Normalizer\\Standard\\ProxyGroupNormalizer/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/Pim\\Component\\Catalog\\Normalizer\\Structured\\GroupTypeNormalizer/Pim\\Component\\Catalog\\Normalizer\\Standard\\GroupTypeNormalizer/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/Pim\\Component\\Catalog\\Normalizer\\Structured\\LocaleNormalizer/Pim\\Component\\Catalog\\Normalizer\\Standard\\LocaleNormalizer/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/Pim\\Component\\Catalog\\Normalizer\\Structured\\MetricNormalizer/Pim\\Component\\Catalog\\Normalizer\\Standard\\Product\\MetricNormalizer/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/Pim\\Component\\Catalog\\Normalizer\\Structured\\ProductAssociationsNormalizer/Pim\\Component\\Catalog\\Normalizer\\Standard\\Product\\AssociationsNormalizer/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/Pim\\Component\\Catalog\\Normalizer\\Structured\\ProductNormalizer/Pim\\Component\\Catalog\\Normalizer\\Standard\\ProductNormalizer/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/Pim\\Component\\Catalog\\Normalizer\\Structured\\ProductPriceNormalizer/Pim\\Component\\Catalog\\Normalizer\\Standard\\Product\\PriceNormalizer/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/Pim\\Component\\Catalog\\Normalizer\\Structured\\ProductPropertiesNormalizer/Pim\\Component\\Catalog\\Normalizer\\Standard\\Product\\PropertiesNormalizer/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/Pim\\Component\\Catalog\\Normalizer\\Structured\\ProductValueNormalizer/Pim\\Component\\Catalog\\Normalizer\\Standard\\Product\\ProductValueNormalizer/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/Pim\\Component\\Catalog\\Normalizer\\Structured\\ProductValuesNormalizer/Pim\\Component\\Catalog\\Normalizer\\Standard\\Product\\ProductValuesNormalizer/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/Pim\\Component\\Catalog\\Normalizer\\Structured\\TranslationNormalizer/Pim\\Component\\Catalog\\Normalizer\\Standard\\TranslationNormalizer/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/Pim\\Component\\Comment\\Normalizer\\Structured\\CommentNormalizer/Pim\\Component\\Comment\\Normalizer\\Standard\\CommentNormalizer/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/pim_serializer\.normalizer\.association_type/pim_catalog\.normalizer\.standard\.association_type/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/pim_serializer\.normalizer\.attribute/pim_catalog\.normalizer\.standard\.attribute/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/pim_serializer\.normalizer\.attribute_group/pim_catalog\.normalizer\.standard\.attribute_group/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/pim_serializer\.normalizer\.attribute_option/pim_catalog\.normalizer\.standard\.attribute_option/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/pim_serializer\.normalizer\.category/pim_catalog\.normalizer\.standard\.category/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/pim_serializer\.normalizer\.channel/pim_catalog\.normalizer\.standard\.channel/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/pim_serializer\.normalizer\.datetime/pim_catalog\.normalizer\.standard\.datetime/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/pim_serializer\.normalizer\.family/pim_catalog\.normalizer\.standard\.family/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/pim_serializer\.normalizer\.group/pim_catalog\.normalizer\.standard\.proxy_group/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/pim_serializer\.normalizer\.product/pim_catalog\.normalizer\.standard\.product/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/pim_serializer\.normalizer\.product_properties/pim_catalog\.normalizer\.standard\.product\.properties/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/pim_serializer\.normalizer\.product_associations/pim_catalog\.normalizer\.standard\.product\.associations/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/pim_serializer\.normalizer\.product_values/pim_catalog\.normalizer\.standard\.product\.product_values/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/pim_serializer\.normalizer\.product_value/pim_catalog\.normalizer\.standard\.product\.product_value/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/pim_serializer\.normalizer\.product_price/pim_catalog\.normalizer\.standard\.product\.price/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/pim_serializer\.normalizer\.metric/pim_catalog\.normalizer\.standard\.product\.metric/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/pim_serializer\.normalizer\.file/pim_catalog\.normalizer\.standard\.file/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/pim_serializer\.normalizer\.currency/pim_catalog\.normalizer\.standard\.currency/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/pim_serializer\.normalizer\.group_type/pim_catalog\.normalizer\.standard\.group_type/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/pim_serializer\.normalizer\.locale/pim_catalog\.normalizer\.standard\.locale/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/pim_serializer\.normalizer\.label_translation/pim_catalog\.normalizer\.standard\.translation/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/pim_serializer\.normalizer\.comment/pim_comment\.normalizer\.standard\.comment/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/Pim\\Component\\Catalog\\Denormalizer\\Structured/Pim\\Component\\Catalog\\Denormalizer\\Standard/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/Pim\\Component\\ReferenceData\\Denormalizer\\Structured/Pim\\Component\\ReferenceData\\Denormalizer\\Standard/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/Akeneo\\Component\\Batch\\Normalizer\\Structured\\JobInstanceNormalizer/Akeneo\\Component\\Batch\\Normalizer\\Standard\\JobInstanceNormalizer/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/pim_serializer\.normalizer\.job_instance/pim_catalog\.normalizer\.standard\.job_instance/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/pim_connector\.array_converter\.structured\.job_instance/pim_connector\.array_converter\.standard\.job_instance/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/pim_serializer\.denormalizer\.product_values/pim_catalog\.denormalizer\.standard\.product_values/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/pim_serializer\.denormalizer\.product_value/pim_catalog\.denormalizer\.standard\.product_value/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/pim_serializer\.denormalizer\.base_value/pim_catalog\.denormalizer\.standard\.base_value/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/pim_serializer\.denormalizer\.attribute_option/pim_catalog\.denormalizer\.standard\.attribute_option/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/pim_serializer\.denormalizer\.attribute_options/pim_catalog\.denormalizer\.standard\.attribute_options/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/pim_serializer\.denormalizer\.prices/pim_catalog\.denormalizer\.standard\.prices/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/pim_serializer\.denormalizer\.metric/pim_catalog\.denormalizer\.standard\.metric/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/pim_serializer\.denormalizer\.number/pim_catalog\.denormalizer\.standard\.number/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/pim_serializer\.denormalizer\.datetime/pim_catalog\.denormalizer\.standard\.datetime/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/pim_serializer\.denormalizer\.file/pim_catalog\.denormalizer\.standard\.file/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/pim_serializer\.denormalizer\.boolean/pim_catalog\.denormalizer\.standard\.boolean/g'
    find ./src/ -type f -print0 | xargs -0 sed -i 's/pim_user_user_rest_get/pim_user_user_rest_get_current/g'
```

#### Versioning

Previously, to normalize an entity for versioning, formats allowed were `flat` and `csv`. To avoid confusion, only `flat` format will be allowed.
