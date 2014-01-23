<?php
namespace Werkint\Bundle\SettingsBundle\Service;

use Werkint\Bundle\SettingsBundle\Entity\Setting;
use Werkint\Bundle\SettingsBundle\Entity\SettingInterface;

/**
 * TreeBuilder.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class TreeBuilder
{
    protected $repo;
    protected $encrypter;

    /**
     * @param SettingInterface $repo
     * @param Encrypter        $encrypter
     */
    public function __construct(
        SettingInterface $repo,
        Encrypter $encrypter
    ) {
        $this->repo = $repo;
        $this->encrypter = $encrypter;
    }

    /**
     * @return array
     */
    public function getTree()
    {
        return $this->tree(
            $this->repo->getRootNodes()
        );
    }

    /**
     * @param Setting $node
     * @return array
     */
    protected function getTreeNode(Setting $node)
    {
        $ret = [
            'parentType' => null,
            'parentId'   => null,
            'children'   => [],
            'env'        => $node->getEnvironment(),
            'class'      => $node->getClass(),
            'title'      => $node->getTitle(),
            'type'       => $node->getType()->getClass(),
            'sid'        => $node->getId(),
            'value'      => $this->encrypter->keyDecrypt($node->getValue(), $node->getId()),
        ];
        if ($node->getParent()) {
            $ret = array_merge($ret, [
                'parentType' => $node->getParent()->getType()->getClass(),
                'parentId'   => $node->getParent()->getId(),
            ]);
        }
        return $ret;
    }

    /**
     * @param array $data
     * @return bool
     */
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

    /**
     * @param $nodes
     * @return array
     */
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

        // Sort the tree
        $this->sortTree($ret);

        return $ret;
    }
}
