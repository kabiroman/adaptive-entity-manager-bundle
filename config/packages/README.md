# Конфигурация AdaptiveEntityManagerBundle

## Структура конфигурации

Конфигурация бандла разделена на два файла:

### 1. adaptive_entity_manager.yaml

Основной файл конфигурации, содержащий базовые настройки:

```yaml
adaptive_entity_manager:
    config:
        entity_folder: '%kernel.project_dir%/src/Entity'
        entity_namespace: 'App\Entity\'
        cache_folder: '%kernel.cache_dir%/adaptive-entity-manager'
```

### 2. entities/adaptive_entity_manager_entities.yaml

Файл с конфигурацией сущностей:

```yaml
adaptive_entity_manager:
    entities:
        user:
            class: App\Entity\User
            fields:
                id:
                    type: integer
                    id: true
                    options:
                        nullable: false
                name:
                    type: string
                    options:
                        maxLength: 255
                        nullable: false
```

## Установка

1. Скопируйте файл `adaptive_entity_manager.yaml.dist` в `config/packages/adaptive_entity_manager.yaml`
2. Создайте директорию `config/packages/entities`
3. Скопируйте файл `entities/adaptive_entity_manager_entities.yaml.dist` в `config/packages/entities/adaptive_entity_manager_entities.yaml`
4. Настройте конфигурацию под свои нужды

## Параметры конфигурации

### Основные параметры

- `entity_folder` - путь к директории с сущностями
- `entity_namespace` - namespace для сущностей
- `cache_folder` - путь к директории для кэша

### Параметры сущностей

- `class` - полное имя класса сущности
- `fields` - описание полей сущности
  - `type` - тип поля (integer, string, text, decimal, etc.)
  - `id` - является ли поле идентификатором (true/false)
  - `options` - дополнительные опции поля
    - `nullable` - может ли поле быть null
    - `maxLength` - максимальная длина для строковых полей
    - `precision` - точность для decimal полей
    - `scale` - масштаб для decimal полей 