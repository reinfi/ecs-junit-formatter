# JUnit Formatter for easy-coding-standard

1. [Installation](#installation)
2. [Usage](#usage)

### Installation

1. Install with composer: `composer require reinfi/ecs-junit-formatter`.
2. Register formatter in your `ecs.php`

```php
use Reinfi\EasyCodingStandard\JUnitOutputFormatter;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\Contract\Console\Output\OutputFormatterInterface;

if (isset($ecsConfig) && $ecsConfig instanceof ECSConfig) {
    $ecsConfig->tag(JUnitOutputFormatter::class, OutputFormatterInterface::class);
}

return ECSConfig::configure()
    [...]
    ->withPaths([__DIR__ . '/src']);
```

### Usage

Use console argument to specify output.

```
php vendor/bin/ecs --output-format=junit
```
