<?php

namespace iggyvolz\phombok;

use PhpParser\Node;
use PhpParser\Node\Stmt\Use_;
use PhpParser\NodeTraverser;

final class NodeVisitor implements \PhpParser\NodeVisitor
{
    private string $namespace = "";
    /** @var array<string,string> */
    private array $uses = [];

    /**
     * @return int|null|Node|list<Node>
     */
    public function enterNode(Node $node): null|int|Node|array
    {
        if ($node instanceof Node\Stmt\Namespace_) {
            $this->namespace = $node->name?->name ?? "";
        }
        if ($node instanceof Use_ && $node->type === Use_::TYPE_NORMAL) {
            foreach ($node->uses as $use) {
                $key = $use->name->name;
                if (str_starts_with($key, "iggyvolz\\phombok\\Attributes\\")) {
                    $name = $use->alias?->name ?? substr($key, strlen("iggyvolz\\phombok\\Attributes\\"));
                    $this->uses[$name] = $key;
                }
            }
        }
        if ($node instanceof Node\Stmt\Property) {
            foreach ($node->attrGroups as $attrGroup) {
                foreach ($attrGroup->attrs as $attribute) {
                    // TODO check namespace
                    if (array_key_exists($attribute->name->name, $this->uses)) {
                        $class = $this->uses[$attribute->name->name];
                        /** @var Transformer $attributeObj */
                        $attributeObj = new $class; // TODO construct properly
                        $traverser = new NodeTraverser();
                        $traverser->addVisitor($attributeObj);
                        return $traverser->traverse([$node]);
                    }
                }
            }
        }
        return null;
    }

    /**
     * @return int|null|Node|list<Node>
     */
    public function leaveNode(Node $node): int|null|Node|array
    {
        if ($node instanceof Node\Stmt\Namespace_) {
            $this->namespace = "";
        }
        return null;
    }

    /**
     * @param list<Node> $nodes
     * @return null|list<Node>
     */
    public function beforeTraverse(array $nodes): ?array
    {
        return null;
    }

    /**
     * @param list<Node> $nodes
     * @return null|list<Node>
     */
    public function afterTraverse(array $nodes): ?array
    {
        return null;
    }
}