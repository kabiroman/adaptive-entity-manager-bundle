# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

# Changelog
## [2.2.2] - 2025-06-05

### 🔥 Fixed
- Hotfix for adding event classes.

## [2.2.1] - 2025-06-05

### ⬆️ Updated
- Updated `README.md` to clarify UnitOfWork events documentation and remove outdated references.

## [2.2.0] - 2025-06-04

### 🚀 Added
- **UnitOfWork Events Integration**:
    - Created Symfony wrapper events (`PrePersistEntityEvent`, `PostPersistEntityEvent`, `PreUpdateEntityEvent`, `PostUpdateEntityEvent`, `PreRemoveEntityEvent`, `PostRemoveEntityEvent`) to wrap original `UnitOfWork` events.
    - Implemented `Kabiroman\AdaptiveEntityManagerBundle\EventSubscriber\UnitOfWorkEventSubscriber` to listen to `UnitOfWork` events and dispatch the new Symfony wrapper events.

### 🔄 Changed
- **Event System Refinement**:
    - `Kabiroman\AdaptiveEntityManagerBundle\Event\ManagerRegisteredEvent` now directly extends `Symfony\Contracts\EventDispatcher\Event`, making it a native Symfony event.

### 🗑️ Removed
- Removed `Psr\Log\LoggerInterface` dependency and all logging calls from `Kabiroman\AdaptiveEntityManagerBundle\EventSubscriber\UnitOfWorkEventSubscriber`.
- Removed `Kabiroman\AdaptiveEntityManagerBundle\EventSubscriber\ManagerRegistrySubscriber` as its logging functionality was deemed redundant.
- Removed `Kabiroman\AdaptiveEntityManagerBundle\Event\ManagerRegisteredSymfonyEvent` as `ManagerRegisteredEvent` became a native Symfony event.

## [2.1.0] - 2025-06-04

### 🚀 Added
- **Symfony Event Dispatcher Integration**:
    - `ManagerRegistry` now dispatches events using `Psr\EventDispatcher\EventDispatcherInterface`.
    - Introduced `Kabiroman\AdaptiveEntityManagerBundle\Event\SymfonyEventDispatcherAdapter` to bridge PSR-14 and Symfony's Event Dispatcher.
    - Added `Kabiroman\AdaptiveEntityManagerBundle\Event\ManagerRegisteredEvent` dispatched when an entity manager is registered.

### ⬆️ Updated
- Updated `composer.json` with new dependencies:
    - `symfony/event-dispatcher` for Symfony event integration.
    - `psr/event-dispatcher` for PSR-14 compatibility.
    - `psr/log` for PSR-3 logger interface.
    - `symfony/monolog-bundle` (dev dependency) for logger implementation.
- Fixed dependency injection for `ManagerRegistry` in `AdaptiveEntityManagerExtension` to correctly provide `EventDispatcherInterface`.

## [2.0.0] - 2025-06-04

### 🚀 Added
- **Multiple EntityManagers Support**:
    - New `ManagerRegistry` service for managing multiple managers.
    - Configuration via `entity_managers` section in YAML.
    - `ManagerRegistry::getManager(string $name)` method to access managers by name.
- **Additional Options**:
    - Custom `naming_strategy` and `filters` per manager.
    - Automatic service registration via Symfony DI (tags).

### ⚠️ Breaking Changes
- Default `EntityManager` removed — now you must specify a manager name.

### 🔄 Changed
- Configuration:
  ```yaml
  # Before:
  adaptive_entity_manager:
    entities_dir: '%kernel.project_dir%/src/Entity/Adaptive'
    entities_namespace: 'App\Entity\Adaptive\'
    connection: default

  # After:
  adaptive_entity_manager:
    entity_managers:
      default:
        entities_dir: '%kernel.project_dir%/src/Entity/Adaptive'
        entities_namespace: 'App\Entity\Adaptive\'
        connection: default
      custom:
        entities_dir: '%kernel.project_dir%/src/Entity/Adaptive/Custom'
        entities_namespace: 'App\Entity\Adaptive\Custom'
        connection: custom
  ```

### 🛠️ Migration Guide
1. **Update config** (see above).
2. **Replace calls**:
   ```php
   // Before:
   use Kabiroman\AEM\EntityManagerInterface;

   $manager = $container->get(EntityManagerInterface::class);

   // After:
   use Kabiroman\AdaptiveEntityManagerBundle\Service\ManagerRegistryInterface;

   $defaultManager = $container->get(ManagerRegistryInterface::class)->getManager('adaptive_entity_manager.default_entity_manager');
   $customManager = $container->get(ManagerRegistryInterface::class)->getManager('adaptive_entity_manager.custom_entity_manager');
   ```

## [1.0.1] - 2025-06-04

### Removed
- Removed dependencies from Doctrine in composer.json to eliminate unnecessary ORM integrations.
- Deleted the file src/Factory/EntityManagerConfigFactory.php as part of cleanup.
- Deleted the directory src/Factory to remove related factory logic.

## [1.0.0] - 2025-06-03

### Added
- Initial release of the bundle with core functionality.

### Changed
- Updated dependencies and configurations for stability.

## [0.4.0] - 2025-06-02

### Changed
- Initialized the array `$config['entities'] = [];` to ensure proper handling of entities configuration.
- Changed the directory path for loading YAML files from `./config/packages/entities/` to `./config/packages/adaptive_entities/` for better organization.
- Modified the configuration merging logic in the load method to merge the entire content of the YAML file directly.
- Added a new parameter `$container->setParameter('adaptive_entity_manager.entities', $config['entities']);` to pass the updated configuration.

## [0.3.0] - 2025-05-31

### Added
- Abstract class `AbstractDoctrineEntityDataAdapter` for basic entity adapter implementation
- Abstract class `AbstractDoctrineEntityDataAdapterProvider` for creating adapter providers
- Class `AdapterRegistry` for managing multiple adapter providers
- Compiler pass `EntityAdapterPass` for collecting tagged adapter providers
- Service tag `adaptive_entity_manager.adapter_provider` for registering providers

### Changed
- Moved adapter logic to abstract classes for extensibility
- Changed service structure to support multiple adapter providers
- Updated service configuration to use adapter registry

### Removed
- Removed class `BundleEntityDataAdapter` (replaced with `AbstractDoctrineEntityDataAdapter`)
- Removed class `BundleEntityDataAdapterProvider` (replaced with `AbstractDoctrineEntityDataAdapterProvider`)
- Removed concrete adapter implementations from bundle (should be in project now)

## [0.2.0] - 2025-05-30

### Added
- Multiple EntityManager support
- Configurable entity storage paths
- Ability to use any existing EntityManager
- Flexible entity namespace configuration
- Zero configuration with sensible defaults
- Compiler pass for EntityManager integration
- Synthetic service for EntityManager injection

### Changed
- Rewritten bundle configuration system
- Simplified integration with existing EntityManagers
- Updated documentation with examples for different databases
- Optimized service structure
- Improved error handling and validation
- Enhanced English documentation

### Removed
- Removed dependency on symfony/expression-language
- Removed redundant entity configuration from yaml
- Removed hard coupling to specific EntityManager
- Removed unused configuration options
- Removed legacy entity loading system

## [0.1.0] - 2025-05-30

### Added
- Basic bundle structure
- Symfony DI integration
- Doctrine ORM support
- Basic YAML configuration
- Entity management services
- Initial documentation 