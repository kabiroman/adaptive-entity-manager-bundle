# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Fixed
- Fixed incorrect imports in AdaptiveEntityManagerFactory
- Updated EntityManagerConfigFactory to support Doctrine ORM 3.x API
- Replaced deprecated annotation driver with AttributeDriver
- Fixed configuration parameters in ORMSetup::createConfiguration

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
- Reworked bundle configuration system
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