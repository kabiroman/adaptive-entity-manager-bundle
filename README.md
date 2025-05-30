# AdaptiveEntityManagerBundle

Symfony bundle for integrating `kabiroman/adaptive-entity-manager` with support for multiple EntityManagers.

## Installation

```bash
composer require kabiroman/adaptive-entity-manager-bundle
```

## Configuration

### Basic Configuration

Create configuration file `config/packages/adaptive_entity_manager.yaml`:

```yaml
adaptive_entity_manager:
    # EntityManager service ID to use (optional, defaults to doctrine.orm.default_entity_manager)
    entity_manager: 'doctrine.orm.default_entity_manager'
    
    # Directory for bundle entities (optional)
    entities_dir: '%kernel.project_dir%/src/Entity/AdaptiveManager'
    
    # Namespace for bundle entities (optional)
    entities_namespace: 'App\Entity\AdaptiveManager'
```

### Configuration Examples

#### Using with Multiple Databases

##### MySQL Configuration:

```yaml
# config/packages/doctrine.yaml
doctrine:
    dbal:
        connections:
            mysql:
                url: '%env(resolve:MYSQL_URL)%'
                driver: pdo_mysql
    orm:
        entity_managers:
            mysql:
                connection: mysql
                # ... other configuration

# config/packages/adaptive_entity_manager.yaml
adaptive_entity_manager:
    entity_manager: 'doctrine.orm.mysql_entity_manager'
    entities_dir: '%kernel.project_dir%/src/Entity/Mysql/AdaptiveManager'
    entities_namespace: 'App\Entity\Mysql\AdaptiveManager'
```

##### PostgreSQL Configuration:

```yaml
# config/packages/doctrine.yaml
doctrine:
    dbal:
        connections:
            pgsql:
                url: '%env(resolve:DATABASE_URL)%'
                driver: pdo_pgsql
    orm:
        entity_managers:
            default:
                connection: pgsql
                # ... other configuration

# config/packages/adaptive_entity_manager.yaml
adaptive_entity_manager:
    entity_manager: 'doctrine.orm.default_entity_manager'
    entities_dir: '%kernel.project_dir%/src/Entity/Postgres/AdaptiveManager'
    entities_namespace: 'App\Entity\Postgres\AdaptiveManager'
```

## Usage

### Entity Configuration

The bundle uses PHP 8 attributes for entity mapping. Create your entities like this:

```php
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'your_table')]
class YourEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $name;

    // ... getters and setters
}
```

### In Services

```php
use Kabiroman\AEM\AdaptiveEntityManager;

class YourService
{
    public function __construct(
        private AdaptiveEntityManager $adaptiveEntityManager
    ) {}

    public function someMethod(): void
    {
        // Create new entity
        $entity = $this->adaptiveEntityManager->create('App\Entity\YourEntity', [
            'field1' => 'value1',
            'field2' => 'value2'
        ]);

        // Save entity
        $this->adaptiveEntityManager->save($entity);

        // Find entity
        $entity = $this->adaptiveEntityManager->find('App\Entity\YourEntity', 1);

        // Update entity
        $this->adaptiveEntityManager->update($entity, [
            'field1' => 'new value'
        ]);
    }
}
```

### In Controllers

```php
use Kabiroman\AEM\AdaptiveEntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class YourController extends AbstractController
{
    public function __construct(
        private AdaptiveEntityManager $adaptiveEntityManager
    ) {}

    public function action(): Response
    {
        $entity = $this->adaptiveEntityManager->find('App\Entity\YourEntity', 1);
        // ...
    }
}
```

## Features

- Flexible integration with any Doctrine EntityManager
- Support for multiple databases
- Configurable entity locations
- Simple entity management interface
- Automatic Symfony DI integration
- Zero configuration with sensible defaults
- Clean and maintainable codebase
- Full support for Doctrine ORM 3.x with PHP 8 attributes

## Requirements

- PHP 8.1 or higher
- Symfony 6.0 or higher
- Doctrine ORM 3.0 or higher

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This bundle is released under the MIT license. 