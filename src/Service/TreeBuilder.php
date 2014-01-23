<?php
namespace Werkint\Bundle\SettingsBundle\Service;

class TreeBuilder
{

    protected $repo;

    public function __construct(
        SettingsRepo $repo
    ) {
        $this->repo = $repo;
    }

    public function getTree()
    {
        return $this->tree(
            $this->repo->getRootNodes()
        );
    }

    protected function getTreeNode(Setting $node)
    {
        $ret = [
            'parentType' => null,
            'parentId'   => null,
            'children'   => [],
            'env'        => $node->getEnvironment() ? $node->getEnvironment()->getTitle() : null,
            'class'      => $node->getClass(),
            'title'      => $node->getTitle(),
            'type'       => $node->getType()->getClass(),
            'sid'        => $node->getId(),
            'value'      => $this->repo->keyDecrypt($node->getValue(), $node->getId()),
        ];
        if ($node->getParent()) {
            $ret = array_merge($ret, [
                'parentType' => $node->getParent()->getType()->getClass(),
                'parentId'   => $node->getParent()->getId(),
            ]);
        }
        return $ret;
    }

    protected function sortTree(array &$data)
    {
        usort($data, function ($a, $b) {
            if ($a['type'] < $b['type']) {
                return 1;
            } elseif ($a['type'] > $b['type']) {
                return -1;
            }
            if ($a['class'] < $b['class']) {
                return 1;
            } elseif ($a['class'] > $b['class']) {
                return -1;
            }
            if ($a['env'] < $b['env']) {
                return 1;
            } elseif ($a['env'] > $b['env']) {
                return -1;
            }

            return 0;
        });

        return true;
    }

    protected function tree($nodes)
    {
        $ret = [];
        foreach ($nodes as $node) {
            /** @var Setting $node */
            $obj = $this->getTreeNode($node);

            if ($node->getChildren()) {
                $obj['children'] = $this->tree(
                    $node->getChildren()
                );
            }
            $ret[] = $obj;
        }

        // Сортируем древо
        $this->sortTree($ret);

        return $ret;
    }

}