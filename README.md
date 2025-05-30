# AdaptiveEntityManagerBundle

Symfony bundle для интеграции пакета `kabiroman/adaptive-entity-manager`.

## Установка

```bash
composer require kabiroman/adaptive-entity-manager-bundle
```

## Конфигурация

Создайте файл конфигурации `config/packages/adaptive_entity_manager.yaml`:

```yaml
adaptive_entity_manager:
    entities:
        user:  # Пример конфигурации
            class: App\Entity\User
            fields:
                name:
                    type: string
                    options:
                        maxLength: 255
                email:
                    type: string
                    options:
                        maxLength: 255
                age:
                    type: integer
```

## Использование

Вы можете использовать сервис бандла в ваших контроллерах или сервисах:

```php
use Kabiroman\AdaptiveEntityManagerBundle\Service\AdaptiveEntityManagerService;

class YourController
{
    public function __construct(
        private AdaptiveEntityManagerService $adaptiveEntityManager
    ) {}

    public function someAction()
    {
        // Создание новой сущности
        $entity = $this->adaptiveEntityManager->create(User::class, [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'age' => 30
        ]);

        // Обновление существующей сущности
        $this->adaptiveEntityManager->update($entity, [
            'name' => 'Jane Doe'
        ]);

        // Сохранение изменений
        $this->adaptiveEntityManager->save($entity);

        // Получение экземпляра AdaptiveEntityManager
        $manager = $this->adaptiveEntityManager->getManager();
    }
}
```

## Возможности

- Интеграция с Symfony Dependency Injection
- Типобезопасное управление сущностями
- Автоматическая регистрация сущностей на основе конфигурации
- Простой интерфейс сервиса

## Лицензия

Этот бандл распространяется под лицензией MIT. 