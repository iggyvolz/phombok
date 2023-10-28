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
(new MyBasicClass())->getFoo() === 1;

```