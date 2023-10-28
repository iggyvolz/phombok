<?php

namespace iggyvolz\phombok;

use Composer\Autoload\ClassLoader;
use PhpParser\NodeDumper;
use PhpParser\NodeTraverser;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter;
use PhpParser\PrettyPrinter\Standard;

final class PhombokLoader
{
    private readonly Parser $parser;
    private readonly PrettyPrinter $printer;
    private readonly NodeDumper $dumper;

    public function __construct()
    {
        spl_autoload_register(self::autoload(...), prepend: true);
        $this->parser = (new ParserFactory())->createForHostVersion();
        $this->printer = new Standard;
        $this->dumper = new NodeDumper;
    }

    public function enable(string $namespace): void
    {
        $this->namespaces[] = $namespace;
    }

    private array $namespaces = [];

    private function shouldAutoload(string $class): bool
    {
        foreach ($this->namespaces as $namespace) {
            if (str_starts_with($class, $namespace)) {
                return true;
            }
        }
        return false;
    }

    private function autoload(string $class): void
    {
        if (self::shouldAutoload($class) && !is_null($file = self::findFile($class))) {

            $traverser = new NodeTraverser();
            $traverser->addVisitor(new NodeVisitor());
            $before = $this->parser->parse(file_get_contents($file));
//            echo $this->dumper->dump($before) . PHP_EOL;
            $after = $traverser->traverse($before);
//            echo $this->dumper->dump($after) . PHP_EOL;
//            echo $this->printer->prettyPrint($after) . PHP_EOL;
            eval($this->printer->prettyPrint($after));
        }
    }

    private function findFile(string $class): ?string
    {
        foreach (ClassLoader::getRegisteredLoaders() as $loader) {
            if (($file = $loader->findFile($class)) !== false) {
                return $file;
            }
        }
        return null;
    }
}