# Adaptive Entity Manager Bundle

## Introduction
This bundle provides flexible entity management for Symfony applications, integrating with Doctrine ORM to handle entities dynamically from YAML configurations. It allows for automated loading of entity definitions and supports multiple EntityManagers for better adaptability.

## Installation
To install the bundle, use Composer:

```bash
composer require kabiroman/adaptive-entity-manager-bundle
```

Add the bundle to your `config/bundles.php`:

```php
return [
    // ...
    Kabiroman\AdaptiveEntityManagerBundle\AdaptiveEntityManagerBundle::class => ['all' => true],
];
```

## Configuration
Configure the bundle in `config/packages/adaptive_entity_manager.yaml` or via auto-loaded YAML files in `config/packages/adaptive_entities/`. Example:

```yaml
adaptive_entity_manager:
    entities_dir: '%kernel.project_dir%/src/Entity/AdaptiveManager'
    entities_namespace: 'App\Entity\AdaptiveManager\'
```

### Configuring Adaptive Entity Metadata
Adaptive entities allow dynamic definition of entity metadata via YAML files. Each YAML file in the `config/packages/adaptive_entities/` directory should define the structure of an entity, including fields, types, and other metadata.

#### Example YAML for an Entity
```yaml
# config/packages/adaptive_entities/user.yaml
App\Entity\User:
  id:
    id:
      column: id
      type: integer
      nullable: false
  fields:
    login:
      column: login
      type: string
      nullable: false
  hasOne:
    role:
      targetEntity: App\Entity\User\Role
      joinColumn:
        name: role_id
        referencedColumnName: id
  hasMany:
    posts:
      targetEntity: App\Entity\User\Post
      mappedBy: author
      fetch: LAZY
  lifecycleCallbacks:
    prePersist:
      - setCreatedAt
```
For automated entity loading, place individual YAML files in `config/packages/adaptive_entities/`.

## Registering the entity data adapter

This section covers how to properly register and configure the data adapter for entities, including custom adapters, to integrate with your Doctrine setup via the EntityDataAdapterProvider.

### Steps to Register Custom Adapters:
1. Create a custom adapter class that implements the required interface (e.g., based on EntityDataAdapterProvider).
2. Register your custom adapter as a service in Symfony's services.yaml or via autowiring, tagging it appropriately for the provider.
3. In your configuration (e.g., config/packages/adaptive_entity_manager.yaml), reference the custom adapter in the EntityDataAdapterProvider setup.
4. Test the custom adapter by injecting it into your code and verifying entity operations without altering database schemas.

### Example Service Registration in services.yaml:
```yaml
services:
    App\CustomAdapter:
        class: App\CustomEntityDataAdapter
        tags: ['adaptive_entity_manager.adapter']  # Assuming a tag for the provider
        arguments: ['@doctrine']  # Inject dependencies as needed
```

## Usage
Use the service in your controllers or services:

```php
use Kabiroman\AdaptiveEntityManagerBundle\Service\AdaptiveEntityManagerFactory;

// Get the entity manager
$entityManager = $this->container->get('adaptive_entity_manager.entity_manager');
// Perform operations as needed
```

## Changelog
See [CHANGELOG.md](CHANGELOG.md) for detailed changes.

## Contributing
Contributions are welcome! Please submit pull requests or issues via the repository.

## License
This bundle is open-source and licensed under the MIT License.
