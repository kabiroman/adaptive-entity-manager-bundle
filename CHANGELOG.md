# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.5.0] - 2025-10-31

### Updated
- **Core Dependency**: Updated `kabiroman/adaptive-entity-manager` to `^1.4.0`
- **Boolean Mapping Support**: Bundle documentation updated to reflect new `values` option in core for bidirectional boolean mapping and criteria handling

### Notes
- No breaking changes. If `values` is not configured in metadata/YAML, behavior remains unchanged.

## [2.4.1] - 2025-06-18

### Updated
- **Core Dependency**: Updated `kabiroman/adaptive-entity-manager` to `^1.3.1`
- **Enhanced DateTime Support**: Includes improved DateTime handling from core package
- **Better Type Safety**: Enhanced identifier and type handling improvements

### Improvements
- **DateTime Immutable**: Full support for `datetime_immutable` field types
- **Flexible Identifiers**: Better identifier resolution in data adapters
- **Type Conversion**: Automatic string-to-DateTime conversion
- **Backward Compatibility**: 100% maintained with existing code

## [2.4.0] - 2024-06-18

### Added

#### 🎉 ValueObject Support
- **Complete ValueObject integration** with Adaptive Entity Manager v1.3.0
- **Configuration option** `enable_value_objects` for enabling/disabling ValueObject support (defaults to true)
- **Automatic ValueObjectConverterRegistry** registration in DI container
- **Built-in ValueObject support** for Email, Money, and UserId types

#### 🔧 Core Enhancements
- **Enhanced Entity Manager creation** with optional ValueObject support
- **Backward compatibility** - existing configurations work without changes
- **Opt-in ValueObject support** - can be disabled per entity manager

#### 📚 Configuration & Testing
- **New configuration option**: `enable_value_objects` (boolean, defaults to true)
- **Comprehensive test suite** with 14 tests covering ValueObject functionality
- **DI container tests** verifying correct service registration
- **Functional tests** ensuring ValueObject support works end-to-end

### Enhanced

#### 🚀 Dependency Injection
- **ValueObjectConverterRegistry** automatically registered as public service
- **Entity Manager services** enhanced with ValueObject support
- **Conditional ValueObject injection** based on configuration

#### 🔄 Backward Compatibility
- **100% Backward Compatible** - existing configurations unchanged
- **Optional ValueObject support** - enabled by default but can be disabled
- **Graceful degradation** - works with or without ValueObject registry

### Technical Details

#### Configuration Example
```yaml
adaptive_entity_manager:
    entity_managers:
        default:
            entities_dir: '%kernel.project_dir%/src/Entity'
            entities_namespace: 'App\Entity\'
            enable_value_objects: true  # Enable ValueObject support (default: true)
```

#### Service Registration
- `adaptive_entity_manager.value_object_registry` - ValueObjectConverterRegistry service
- Enhanced entity manager services with ValueObject support
- Automatic built-in ValueObject converter registration

#### Testing
- **14 comprehensive tests** covering all ValueObject functionality
- **DI container validation** ensuring correct service wiring
- **Functional tests** verifying end-to-end ValueObject behavior
- **Configuration tests** validating default and custom settings

### Dependencies
- **Updated**: `kabiroman/adaptive-entity-manager` to `^1.3` for ValueObject support
- **Added dev dependency**: `symfony/yaml` for test configuration loading

### Migration Guide

For existing projects - **no changes required**! ValueObject support is enabled by default.

To disable ValueObject support:
```yaml
adaptive_entity_manager:
    entity_managers:
        your_manager:
            # ... existing config ...
            enable_value_objects: false
```

To use ValueObjects in your entities:
1. Update entity metadata to specify ValueObject fields
2. Use ValueObject types in entity properties
3. Enjoy automatic conversion during persistence/hydration

---

# Changelog
## [2.3.1] - 2025-06-17

### 📝 Fixed
- **Documentation Update**: Added comprehensive documentation for `metadata_cache` and `use_optimized_metadata` configuration options in README.md
- **Performance Options Documentation**: Added detailed description of PSR-6 cache integration and optimized metadata system configuration
- **Configuration Examples**: Updated configuration examples to include new performance optimization parameters

## [2.3.0] - 2025-06-17

### 🚀 Added
- **PSR-6 Metadata Caching Support**: Added configuration option `metadata_cache` to specify PSR-6 cache service for metadata caching
- **Optimized Metadata System**: Added `use_optimized_metadata` boolean option to enable/disable optimized metadata system for better performance
- **Adaptive Entity Manager v1.2.x Support**: Updated bundle to fully support new features from adaptive-entity-manager v1.2.x

### ⬆️ Updated  
- **Dependencies**: Updated requirement for `kabiroman/adaptive-entity-manager` from `^1.1` to `^1.2`
- **Constructor Arguments**: Updated AdaptiveEntityManager service definition to use new v1.2.x constructor signature with optional PSR-6 caching and optimization flags

### 📋 Configuration Examples
```yaml
adaptive_entity_manager:
    entity_managers:
        default:
            entities_dir: '%kernel.project_dir%/src/Entity'
            entities_namespace: 'App\Entity'
            connection: 'default'
            metadata_cache: 'cache.app'  # PSR-6 cache service ID
            use_optimized_metadata: true # Enable optimized metadata (default: true)
```

## [2.2.3] - 2025-06-05

### 🚀 Added
- **Early validation for entities_dir**:
    - Added validation for `entities_dir` configuration to ensure the specified directory exists.
    - This prevents obscure errors during metadata factory processing by providing clear `RuntimeException` for incorrect paths.

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