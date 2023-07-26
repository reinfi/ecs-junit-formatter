# JUnit Formatter for easy-coding-standard

1. [Installation](#installation)
2. [Usage](#usage)

### Installation

1. Install with composer: `composer require reinfi/ecs-junit-formatter`.
2. Register formatter in your `ecs.php`

```php
return static function (ECSConfig $config): void {
    [...]

    $configurator->tag(JUnitOutputFormatter::class, OutputFormatterInterface::class);
};
```

### Usage

Use console argument to specify output.

```
php vendor/bin/ecs --output-format=junit
```
