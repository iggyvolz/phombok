## Phombok

PHP library inspired by [Lombok](https://projectlombok.org/).

```php
<?php
use iggyvolz\phombok\Attributes\Getter;

class MyBasicClass
{
    #[Getter]
    private int $foo = 1;
}
```

```injectablephp
(new \iggyvolz\phombok\test\MyBasicClass())->getFoo() === 1;

```