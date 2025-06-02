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
    data_adapter_entity_manager: 'doctrine.orm.default_entity_manager'
    entities_dir: '%kernel.project_dir%/src/Entity/AdaptiveManager'
    entities_namespace: 'App\Entity\AdaptiveManager'
```
For automated entity loading, place individual YAML files in `config/packages/adaptive_entities/`.

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
