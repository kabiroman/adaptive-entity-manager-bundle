# AdaptiveEntityManagerBundle

Symfony bundle for integrating [kabiroman/adaptive-entity-manager](https://github.com/kabiroman/adaptive-entity-manager) with Symfony projects. This bundle allows you to adapt your business entities to different storage systems.

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

## Usage Example

Let's say you have a business entity:

```php
namespace App\Entity;

class User
{
    private ?int $id = null;
    private string $firstName;
    private string $lastName;
    private string $email;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }
}
```

To store this entity in a different system (like Bitrix), create an adapter that implements EntityDataAdapter interface:

1. Create an adapter for your entity:

```php
namespace App\EntityAdapter;

use App\Entity\User;
use Kabiroman\AEM\DataAdapter\EntityDataAdapter;

class UserEntityAdapter implements EntityDataAdapter
{
    /**
     * Insert data into storage and return identifier
     */
    public function insert(array $row): array
    {
        // Convert data to storage format
        $storageData = [
            'LOGIN' => $row['email'],
            'NAME' => $row['firstName'],
            'LAST_NAME' => $row['lastName'],
        ];

        // Here would be actual storage logic, e.g. Bitrix API call
        $id = /* storage insert operation */;

        return ['ID' => $id];
    }

    /**
     * Update data in storage
     */
    public function update(array $identifier, array $row): void
    {
        // Convert data to storage format
        $storageData = [
            'ID' => $identifier['ID'],
            'LOGIN' => $row['email'] ?? null,
            'NAME' => $row['firstName'] ?? null,
            'LAST_NAME' => $row['lastName'] ?? null,
        ];

        // Here would be actual storage logic, e.g. Bitrix API call
        /* storage update operation */
    }

    /**
     * Delete data from storage
     */
    public function delete(array $identifier): void
    {
        // Here would be actual storage logic, e.g. Bitrix API call
        /* storage delete operation using $identifier['ID'] */
    }

    /**
     * Refresh data from storage
     */
    public function refresh(array $identifier): array
    {
        // Here would be actual storage logic, e.g. Bitrix API call
        $storageData = /* storage load operation using $identifier['ID'] */;

        // Convert storage format back to entity format
        return [
            'id' => $storageData['ID'],
            'email' => $storageData['LOGIN'],
            'firstName' => $storageData['NAME'],
            'lastName' => $storageData['LAST_NAME'],
        ];
    }

    /**
     * Load single entity data by identifier
     */
    public function loadById(array $identifier): ?array
    {
        // Here would be actual storage logic, e.g. Bitrix API call
        $storageData = /* storage load operation using $identifier['ID'] */;
        
        if (!$storageData) {
            return null;
        }

        // Convert storage format back to entity format
        return [
            'id' => $storageData['ID'],
            'email' => $storageData['LOGIN'],
            'firstName' => $storageData['NAME'],
            'lastName' => $storageData['LAST_NAME'],
        ];
    }

    /**
     * Load collection of entities
     */
    public function loadAll(
        array $criteria = [],
        ?array $orderBy = null,
        ?int $limit = null,
        ?int $offset = null
    ): array {
        // Convert criteria to storage format if needed
        $storageCriteria = [];
        foreach ($criteria as $field => $value) {
            // Map field names to storage format
            $storageField = match ($field) {
                'email' => 'LOGIN',
                'firstName' => 'NAME',
                'lastName' => 'LAST_NAME',
                default => $field
            };
            $storageCriteria[$storageField] = $value;
        }

        // Here would be actual storage logic, e.g. Bitrix API call
        $storageData = /* storage load operation with criteria, order, limit, offset */;

        // Convert each row from storage format to entity format
        return array_map(
            fn (array $row) => [
                'id' => $row['ID'],
                'email' => $row['LOGIN'],
                'firstName' => $row['NAME'],
                'lastName' => $row['LAST_NAME'],
            ],
            $storageData
        );
    }
}
```

2. Create an adapter provider:

```php
namespace App\EntityAdapter;

use App\Entity\User;
use Kabiroman\AEM\DataAdapter\EntityDataAdapter;
use Kabiroman\AEM\DataAdapter\EntityDataAdapterProvider;

class StorageEntityAdapterProvider implements EntityDataAdapterProvider
{
    private array $adapters = [];

    public function getAdapter(string $entityClass): EntityDataAdapter
    {
        if (!isset($this->adapters[$entityClass])) {
            $this->adapters[$entityClass] = match ($entityClass) {
                User::class => new UserEntityAdapter(),
                // Add other entity adapters...
                default => throw new \RuntimeException("No adapter for $entityClass")
            };
        }

        return $this->adapters[$entityClass];
    }
}
```

3. Register your adapter provider:

```yaml
# config/services.yaml
services:
    App\EntityAdapter\StorageEntityAdapterProvider:
        tags:
            - { name: 'adaptive_entity_manager.adapter_provider' }
```

4. Use AdaptiveEntityManager in your application:

```php
namespace App\Controller;

use App\Entity\User;
use Kabiroman\AEM\AdaptiveEntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{
    public function __construct(
        private readonly AdaptiveEntityManager $adaptiveEntityManager
    ) {}

    public function createAction(): Response
    {
        // Create user in storage
        $identifier = $this->adaptiveEntityManager->insert(User::class, [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'john@example.com'
        ]);

        // Load user data from storage
        $userData = $this->adaptiveEntityManager->loadById(User::class, $identifier);

        return $this->json($userData);
    }

    public function listAction(): Response
    {
        // Load users with criteria
        $users = $this->adaptiveEntityManager->loadAll(
            User::class,
            ['lastName' => 'Doe'],
            ['firstName' => 'ASC'],
            10,
            0
        );

        return $this->json($users);
    }
}
```

The AdaptiveEntityManager will:
1. Use the registered adapter to convert between your entity objects and storage format
2. Handle all storage operations through the configured system
3. Keep your business entities clean and storage-agnostic
4. Provide a consistent interface for working with different storage systems

## License

MIT License. See [LICENSE](LICENSE) file for details. 