<?php
namespace Werkint\Bundle\SettingsBundle\Service;

use Werkint\Bundle\SettingsBundle\Entity\SettingInterface;

/**
 * Settings.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class Settings
{
    protected $repo;
    protected $parameters;
    protected $directory;
    protected $environment;

    public function __construct(
        SettingInterface $repo,
        array $parameters,
        $directory,
        $environment
    ) {
        $this->repo = $repo;
        $this->parameters = $parameters;
        $this->directory = $directory;
        $this->environment = explode('_', $environment)[0];
    }

    public function compile()
    {
        $compiler = new Compiler(
            $this->repo
        );
        $compiler->compile(
            $this->directory,
            $this->environment
        );
    }

    /**
     * @return array
     */
    public function getTree()
    {
        $tree = new TreeBuilder(
            $this->repo
        );

        return $tree->getTree();
    }

    /**
     * @param $pathin
     * @return string|array
     * @throws \Exception
     */
    public function get($pathin)
    {
        $path = explode('.', $pathin);
        $param = $this->parameters;
        while (($chunk = array_shift($path)) !== null) {
            if (!isset($param[$chunk])) {
                throw new \Exception('Путь в настройках не найден: ' . $pathin);
            }
            $param = & $param[$chunk];
        }

        return $param;
    }

}
