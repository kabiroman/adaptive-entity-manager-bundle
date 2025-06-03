# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

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