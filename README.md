README
======

 - [What is the Entity Tracker Bundle?](#what-is-the-entity-tracker-bundle)
 - [Requirements](#requirements)
 - [Installation](#installation)

### Documentation
   - [How does it work?](#how-does-it-work)
   - [Setup](#setup)
      - [Register The Bundle in your AppKernel](#register-the-bundle-in-your-appkernel)
      - [Configuration Overview](#configuration-overview)
         - [Blamable Component](#configuring-the-blamable-component)
         - [Mutation Component](#configuring-the-mutation-component)
         - [Revision Component](#configuring-the-revision-component)

What is the Entity Tracker Bundle?
---------------------------
The Entity Tracker Bundle is a bundle used to configure the following components:
  - [hostnet/entity-tracker-component](https://github.com/hostnet/entity-tracker-component)
  - [hostnet/entity-blamable-component](https://github.com/hostnet/entity-blamable-component)
  - [hostnet/entity-mutation-component](https://github.com/hostnet/entity-mutation-component)
  - [hostnet/entity-revision-component](https://github.com/hostnet/entity-revision-component)

It will let you configure those components using the config.yaml

> Note: Only the Entity Tracker Component is mandatory, as this bundle configures the services. The other components only need to be configured if they are present.

Requirements
------------
The tracker bundle requires at least php 7.3 and runs on Doctrine2 and Symfony2. For specific requirements, please check [composer.json](../master/composer.json)

Installation
------------

Installing is pretty easy, this package is available on [packagist](https://packagist.org/packages/hostnet/entity-tracker-bundle). You can register the package locked to a major as we follow [Semantic Versioning 2.0.0](http://semver.org/).

#### Example

```json
    "require" : {
        "hostnet/entity-tracker-bundle" : "^2.0.0"
    }
```
> Note: You can use dev-master if you want the latest changes, but this is not recommended for production code!


Documentation
=============

How does it work?
-----------------

It features a config builder that allows you to config only what you need. If you have the "Blamable" annotation available, it will throw an exception during container compilation if it's not configured. The same goes for all other supported components. If you happen to configure it while it isn't used, it will also let you know.

Setup
-----

#### Register The Bundle in your AppKernel
This bundle is not dependent on others based on compiler passes or configuration.

```php

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            // ...
            new Hostnet\Bundle\EntityTrackerBundle\HostnetEntityTrackerBundle()
            // ...
        ];

        return $bundles;
    }
}

```

#### Configuration Overview
By default, nothing is required. Only when you enable a certain configuration option, it will yell you what's missing.

If you are using entities in your bundle, your bundle should have a dependency on the component using the specific annotation you use. If your bundle is in your application, your application should have a dependency on the component. If you use the [hostnet/entity-plugin-lib](https://github.com/hostnet/entity-plugin-lib), you will have separate packages for entities. You should put your dependencies on that package.

> Note: Based on what is available, the configuration requirements are determined. If one of the bundles or Entity packages has a dependency on one of the components, this bundle will also configure it for you.

The template is as following:
```yaml

# Default configuration for extension with alias: "hostnet_entity_tracker"
hostnet_entity_tracker:

    # Configures and enables the blamable listener
    blamable:

        # Provider implementation of Hostnet\Component\EntityBlamable\Provider\BlamableProviderInterface
        provider: ~ # Required

    # Configures and enables the revision listener
    revision:

        # Factory implementation of Hostnet\Component\EntityRevision\Factory\RevisionFactoryInterface
        factory: ~ # Required

    # Configures and enables the mutation listener
    mutation: ~
```

##### Configuring the Blamable Component
The Blamable Component has 1 required option, the provider. The provider is [the class that implements the BlamableProviderInterface as explained in the documation](https://github.com/hostnet/entity-blamable-component/#creating-a-provider-for-the-username-and-timestamp). The argument passed to that option is the name of the service you are using for it.
There are two ways you can go about using these providers.
The first way is to make use of the [default blamamable provider](https://github.com/hostnet/entity-tracker-bundle/blob/master/src/Services/Blamable/DefaultBlamableProvider.php) that is shipped with this bundle. 
This can be done using the following method:

_config.yaml_
```yaml
hostnet_entity_tracker:
    blamable:
        provider: entity_tracker.provider.blamable
        default_username: "username for example purposes"
```

It is also possible to create your own BlamamableProvider by implementing the BlamableProviderInterface.
If you choose to do this the following method can be used to make use of your custom BlamableProvider.


> Note: The following example is based on the AcmeBlamableProvider from the link above

_services.yaml_
```yaml
services:
    acme.provider.blamable:
        class: Acme\Bundle\AcmeBundle\Service\AcmeBlamableProvider
        arguments:
            - 'username for example purposes'
```

_config.yaml_
```yaml
hostnet_entity_tracker:
    blamable:
        provider: acme.provider.blamable

```

##### Configuring the Mutation Component
The Mutation Component has no required options. All you have to do to enable it, is add "mutation: ~" to the config.

_config.yaml_
```yaml
hostnet_entity_tracker:
    mutation: ~

```

##### Configuring the Revision Component
The Revision Component has 1 required option, the factory. The factory is [the class that implements the RevisionFactoryInterface as explained in the documation](https://github.com/hostnet/entity-revision-component/#creating-the-acmerevisionfactory). The argument passed to that option is the name of the service you are using for it.

> Note: The following example is based on the AcmeRevisionFactory from the link above

_services.yaml_
```yaml
services:
    acme.factory.revision:
        class: Acme\Bundle\AcmeBundle\Service\AcmeBlamableProvider
        arguments:
            - 'author->name for example purposes'
```

_config.yaml_
```yaml
hostnet_entity_tracker:
    revision:
        factory: acme.factory.revision

```
