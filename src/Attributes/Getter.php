<?php

namespace iggyvolz\phombok\Attributes;

use iggyvolz\phombok\Transformer;
use PhpParser\Builder\Method;
use PhpParser\Node;
use PhpParser\NodeDumper;
use PhpParser\NodeVisitor;
use PhpParser\NodeVisitorAbstract;
use Reflector;

#[\Attribute]
final readonly class Getter implements NodeVisitor
{

    public function beforeTraverse(array $nodes): null
    {
        return null;
    }

    /*
     * 0: Stmt_Property(
                            attrGroups: array(
                                0: AttributeGroup(
                                    attrs: array(
                                        0: Attribute(
                                            name: Name(
                                                name: Getter
                                            )
                                            args: array(
                                            )
                                        )
                                    )
                                )
                            )
                            flags: PRIVATE (4)
                            type: Identifier(
                                name: intg
                            )
                            props: array(
                                0: PropertyItem(
                                    name: VarLikeIdentifier(
                                        name: foo
                                    )
                                    default: Scalar_Int(
                                        value: 1
                                    )
                                )
                            )
                        )
     */
    public function enterNode(Node $node): ?array
    {
        if ($node instanceof Node\Stmt\Property) {
            $ret = [$node];
            foreach ($node->props as $property) {
                $name = $property->name;
                $method = new Method("get" . ucfirst($name));
                $method->setReturnType($node->type?->name);
                $method->addStmt(new Node\Stmt\Return_(new Node\Expr\PropertyFetch(new Node\Expr\Variable("this"), new Node\Identifier($name))));
                $ret [] = $method->getNode();
            }
            return $ret;
        }
        return null;
    }

    public function leaveNode(Node $node): null
    {
        return null;
    }

    public function afterTraverse(array $nodes): null
    {
        return null;
    }
}