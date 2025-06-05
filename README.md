# Adaptive Entity Manager Bundle

## Introduction
This bundle provides flexible entity management for Symfony applications, integrating with Doctrine ORM to handle entities dynamically from YAML configurations. In version 2.0.0, it introduces enhanced support for **multiple EntityManagers**, allowing for more complex and adaptable database interactions. It enables automated loading of entity definitions and supports various EntityManagers for improved flexibility.

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

## Configuration (Version 2.0.0 and above)
With version 2.0.0, the bundle now supports multiple entity managers. You must explicitly define each manager under the `entity_managers` key.

Configure the bundle in `config/packages/adaptive_entity_manager.yaml`. Example:

```yaml
adaptive_entity_manager:
    entity_managers:
        default:
            entities_dir: '%kernel.project_dir%/src/Entity/Adaptive'
            entities_namespace: 'App\Entity\Adaptive\'
            connection: default # Assuming 'default' is configured in doctrine.yaml
        custom:
            entities_dir: '%kernel.project_dir%/src/Entity/Adaptive/Custom'
            entities_namespace: 'App\Entity\Adaptive\Custom\'
            connection: custom # Assuming 'custom' is configured in doctrine.yaml
```

### Important Note on Entity Metadata Paths (Version 2.0.0+)
In versions prior to 2.0.0, the `entities_dir` was a top-level configuration. With the introduction of multiple entity managers in 2.0.0, the `entities_dir` (and `entities_namespace`) are now configured *per entity manager* within the `entity_managers` section. This allows each manager to define its own specific directory for YAML entity metadata files and their corresponding namespace.

### Entity Metadata Paths and Automatic Discovery

#### Hardcoded Automatic Discovery Path:
The bundle includes an **automatic discovery** mechanism that searches for entity YAML files in a hardcoded directory: `%kernel.project_dir%/config/aem/entities/`.

    *   For each defined entity manager (e.g., `default`, `custom`), the bundle will look for YAML files named `[manager_name].*.yaml` within this hardcoded directory. For instance, for the `default` manager, it will search for `default.user.yaml`, `default.product.yaml`, etc.
    *   Entities found via this automatic discovery are merged with entities configured via the `entities_dir` for that specific manager.


### Configuring Adaptive Entity Metadata
Adaptive entities allow dynamic definition of entity metadata via YAML files. Each YAML file in the `entities_dir` for a specific manager should define the structure of an entity, including fields, types, and other metadata.

#### Example YAML for an Entity
```yaml
# user.yaml (For automatic discovery, name it `default.user.yaml` and place it in `%kernel.project_dir%/config/aem/entities/`.)
App\Entity\User:
  dataAdapterClass: App\DataAdapter\UserDataAdapter
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
For automated entity loading, place individual YAML files in the configured `entities_dir` for each manager.

## Registering the entity data adapter

This section covers how to properly register and configure the data adapter for entities, including custom adapters, to integrate with your Doctrine setup via the EntityDataAdapterProvider.

### Steps to Register Custom Adapters:
1. Create a custom adapter class that implements the required interface (e.g., based on Kabiroman\AEM\DataAdapter\EntityDataAdapter).
2. Register your custom adapter as a service in Symfony's services.yaml or via autowiring, tagging it appropriately for the provider.
3. In your configuration (e.g., config/packages/adaptive_entities/user.yaml), reference the custom adapter in the EntityDataAdapter setup.
4. Test the custom adapter by injecting it into your code and verifying entity operations without altering database schemas.

### Example Service Registration in services.yaml:
```yaml
services:
    App\CustomAdapter:
        class: App\CustomEntityDataAdapter
        tags: ['adaptive_entity_manager.adapter']  # Assuming a tag for the provider
        arguments: ['@doctrine']  # Inject dependencies as needed
```

## Usage (Version 2.0.0 and above)
With the introduction of multiple entity managers, you now interact with a `ManagerRegistry` to retrieve specific entity managers by name.

```php
use Kabiroman\AdaptiveEntityManagerBundle\Service\ManagerRegistryInterface;
use App\Entity\Adaptive\User; // Example entity, adjust namespace as per your config

// In your controller or service, inject ManagerRegistryInterface
class MyService
{
    private $managerRegistry;

    public function __construct(ManagerRegistryInterface $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    public function doSomething()
    {
        // Get the default entity manager
        $defaultManager = $this->managerRegistry->getManager('adaptive_entity_manager.default_entity_manager');
        $userRepository = $defaultManager->getRepository(User::class);
        $user = $userRepository->find(1);

        // Or get a custom entity manager
        $customManager = $this->managerRegistry->getManager('adaptive_entity_manager.custom_entity_manager');
        // ... use customManager

        // Example of persisting a new entity with the default manager
        $newUser = new User();
        // Set properties of newUser
        $defaultManager->persist($newUser);
        $defaultManager->flush();
    }
}
```

### Event Handling (Version 2.1.0 and above)

With the introduction of PSR-14 event support, the bundle now dispatches various lifecycle events, allowing you to react to different operations. These events are native Symfony events and can be subscribed to using Symfony's event dispatcher mechanism.

### UnitOfWork Events (Version 2.2.0 and above)
In addition to `ManagerRegisteredEvent`, the bundle also dispatches events related to the `UnitOfWork` lifecycle. These events wrap the core `UnitOfWork` events from `kabiroman/adaptive-entity-manager` and are dispatched as native Symfony events, allowing you to hook into the persist, update, and remove operations.

-   `PrePersistEntityEvent`: Dispatched before an entity is persisted.
-   `PostPersistEntityEvent`: Dispatched after an entity has been persisted.
-   `PreUpdateEntityEvent`: Dispatched before an entity is updated.
-   `PostUpdateEntityEvent`: Dispatched after an entity has been updated.
-   `PreRemoveEntityEvent`: Dispatched before an entity is removed.
-   `PostRemoveEntityEvent`: Dispatched after an entity has been removed.

To subscribe to these events, you can create a Symfony event subscriber. The events are `Symfony\Contracts\EventDispatcher\Event` objects and can be stopped using `StoppableEventInterface`.

### Breaking Changes in 2.0.0
*   **Default `EntityManager` removed:** You must now explicitly specify a manager name when retrieving an `EntityManager`. Direct access to a default `EntityManager` service (e.g., `Kabiroman\AEM\EntityManagerInterface::class`) is no longer supported directly without using the `ManagerRegistry`.

### Migration Guide from 1.x to 2.0.0
1.  **Update Configuration:**
    Your `adaptive_entity_manager` configuration in `config/packages/adaptive_entity_manager.yaml` must be updated to use the `entity_managers` key.

    **Before (1.x):**
    ```yaml
    adaptive_entity_manager:
      entities_dir: '%kernel.project_dir%/src/Entity/Adaptive'
      entities_namespace: 'App\Entity\Adaptive\'
      connection: default
    ```

    **After (2.0.0):**
    ```yaml
    adaptive_entity_manager:
      entity_managers:
        default:
          entities_dir: '%kernel.project_dir%/src/Entity/Adaptive'
          entities_namespace: 'App\Entity\Adaptive\'
          connection: default
    ```

2.  **Replace `EntityManagerInterface` calls:**
    Instead of directly injecting `Kabiroman\AEM\EntityManagerInterface`, you should now inject `Kabiroman\AdaptiveEntityManagerBundle\Service\ManagerRegistryInterface` and retrieve managers by name.

    **Before (1.x):**
    ```php
    use Kabiroman\AEM\EntityManagerInterface;

    // ...
    $manager = $container->get(EntityManagerInterface::class);
    ```

    **After (2.0.0):**
    ```php
    use Kabiroman\AdaptiveEntityManagerBundle\Service\ManagerRegistryInterface;

    // ...
    $defaultManager = $container->get(ManagerRegistryInterface::class)->getManager('adaptive_entity_manager.default_entity_manager');
    $customManager = $container->get(ManagerRegistryInterface::class)->getManager('adaptive_entity_manager.custom_entity_manager');
    ```

## Changelog
See [CHANGELOG.md](CHANGELOG.md) for detailed changes.

## Contributing
Contributions are welcome! Please submit pull requests or issues via the repository.

## License
This bundle is open-source and licensed under the MIT License.
