<?php

use iggyvolz\phombok\PhombokLoader;
use iggyvolz\phombok\test\MyBasicClass;
use Tester\Assert;

require_once __DIR__ . "/../vendor/autoload.php";
(new PhombokLoader())->enable("iggyvolz\\phombok\\test\\");
$myBasicClass = new MyBasicClass();
Assert::same("Hello world!", $myBasicClass->hello());
Assert::same(1, $myBasicClass->getFoo());
