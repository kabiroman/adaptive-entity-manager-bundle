# AdaptiveEntityManagerBundle

Symfony bundle for integrating [kabiroman/adaptive-entity-manager](https://github.com/kabiroman/adaptive-entity-manager) with Symfony projects.

## Installation

```bash
composer require kabiroman/adaptive-entity-manager-bundle
```

## Configuration

```yaml
# config/packages/adaptive_entity_manager.yaml
adaptive_entity_manager:
    entities_dir: '%kernel.project_dir%/src/Entity'
    entities_namespace: 'App\Entity'
```

## Usage

### Basic Usage

```php
use Kabiroman\AEM\AdaptiveEntityManager;

class YourService
{
    public function __construct(
        private readonly AdaptiveEntityManager $adaptiveEntityManager
    ) {}

    public function someMethod(): void
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com'
        ];

        // Create record
        $user = $this->adaptiveEntityManager->insert(User::class, $data);

        // Update record
        $this->adaptiveEntityManager->update(User::class, ['id' => $user['id']], $data);

        // Load record
        $user = $this->adaptiveEntityManager->loadById(User::class, ['id' => 1]);

        // Delete record
        $this->adaptiveEntityManager->delete(User::class, ['id' => 1]);
    }
}
```

### Creating Custom Adapters

The bundle provides abstract classes for creating your own entity adapters:

1. Create an adapter for a specific entity:

```php
namespace App\EntityAdapter;

use Kabiroman\AdaptiveEntityManagerBundle\DataAdapter\AbstractDoctrineEntityDataAdapter;

class UserEntityAdapter extends AbstractDoctrineEntityDataAdapter
{
    protected function convertEntityToArray(object $entity): array
    {
        // Basic conversion
        $data = parent::convertEntityToArray($entity);
        
        // Add custom fields
        $data['fullName'] = $entity->getFirstName() . ' ' . $entity->getLastName();
        
        return $data;
    }

    protected function updateEntityFromArray(object $entity, array $data): void
    {
        // Handle custom fields
        if (isset($data['fullName'])) {
            [$firstName, $lastName] = explode(' ', $data['fullName']);
            $data['firstName'] = $firstName;
            $data['lastName'] = $lastName;
            unset($data['fullName']);
        }

        // Basic update
        parent::updateEntityFromArray($entity, $data);
    }
}
```

2. Create an adapter provider:

```php
namespace App\EntityAdapter;

use App\Entity\User;
use Kabiroman\AdaptiveEntityManagerBundle\DataAdapter\AbstractDoctrineEntityDataAdapterProvider;
use Kabiroman\AEM\DataAdapter\EntityDataAdapter;

class ProjectEntityAdapterProvider extends AbstractDoctrineEntityDataAdapterProvider
{
    protected function createAdapter(string $entityClass): EntityDataAdapter
    {
        return match ($entityClass) {
            User::class => new UserEntityAdapter($this->entityManager, $entityClass),
            default => throw new \RuntimeException("No adapter for $entityClass")
        };
    }
}
```

3. Register the provider in services:

```yaml
# config/services.yaml
services:
    App\EntityAdapter\ProjectEntityAdapterProvider:
        arguments:
            - '@doctrine.orm.entity_manager'
        tags:
            - { name: 'adaptive_entity_manager.adapter_provider' }
```

### How It Works

1. The bundle uses Symfony's tag system to collect all adapter providers
2. Each provider can handle a specific set of entities
3. When requesting an adapter for an entity:
   - AdaptiveEntityManager calls AdapterRegistry
   - The registry iterates through all registered providers
   - The first provider that can handle the entity provides the adapter
   - If no provider can handle the entity, an exception is thrown

This allows you to:
- Keep adapters in your project code, not in the bundle
- Have different adapters for different entities
- Easily add new adapters
- Override entity handling logic for specific cases

## License

MIT License. See [LICENSE](LICENSE) file for details. 