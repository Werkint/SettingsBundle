<?php
namespace Werkint\Bundle\SettingsBundle\Service;

use Symfony\Component\Yaml\Escaper;
use Werkint\Bundle\SettingsBundle\Entity\Setting;
use Werkint\Bundle\SettingsBundle\Entity\SettingInterface;

/**
 * Compiler.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class Compiler
{
    protected $repo;
    protected $encrypter;
    protected $directory;
    protected $envs;

    /**
     * @param SettingInterface $repo
     * @param Encrypter        $encrypter
     * @param string           $directory
     * @param array            $envs
     */
    public function __construct(
        SettingInterface $repo,
        Encrypter $encrypter,
        $directory,
        array $envs
    ) {
        $this->repo = $repo;
        $this->encrypter = $encrypter;
        $this->directory = $directory;
        $this->envs = $envs;
    }

    /**
     * @param string|null $env
     */
    public function compile($env = null)
    {
        if ($env) {
            $this->compileEnv($env);
        } else {
            foreach ($this->envs as $env) {
                $this->compileEnv($env);
            }
        }
    }

    // -- Helpers ---------------------------------------

    /**
     * Компилирует все настройки заданного окружения
     *
     * @param string $env
     */
    protected function compileEnv($env)
    {
        $filename = $this->directory . '/' . $env . '.yml';
        if (file_exists($filename)) {
            unlink($filename);
        }

        $this->parameters = [];
        $nodes = $this->repo->getRootNodes();
        $data = [];
        foreach ($nodes as $node) {
            /** @var Setting $node */
            $data[] = (string)$this->compileNode($env, $node);
        }
        if (count($this->parameters)) {
            $data[] = 'parameters:';
            foreach ($this->parameters as $key => $value) {
                $data[] = '    ' . $key . ': ' . $value;
            }
        }
        $data = join("\n", $data);
        $data = preg_replace('!\n+!', "\n", $data);
        file_put_contents($filename, $data);
    }

    /**
     * Проверяет, применима ли настройка к окружению
     *
     * @param Setting $setting
     * @param string  $env
     * @return bool
     */
    protected function checkEnv(Setting $setting, $env)
    {
        if ($setting->getEnvironment()) {
            if ($setting->getEnvironment() != $env) {
                return false;
            }
        }
        return true;
    }

    /**
     * Компилирует настройку в строку yaml вместе с дочерними.
     *
     * @param string  $env
     * @param Setting $setting
     * @param Setting $parent
     * @param string  $tab
     * @return string
     */
    protected function compileNode(
        $env,
        Setting $setting,
        Setting $parent = null,
        $tab = ''
    ) {
        if (!$this->checkEnv($setting, $env)) {
            return null;
        }

        $val = $this->encrypter->keyDecrypt(
            $setting->getValue(), $setting->getId()
        );
        if ($setting->getParameter()) {
            $this->parameters[$setting->getParameter()] = $val;
            return null;
        }

        $ret = [];
        foreach ($setting->getChildren() as $child) {
            $ret[] = $this->compileNode($env, $child, $setting, $tab . '    ');
        }

        $return = '';
        if ($parent && $parent->getType()->getClass() == 'array') {
            $return .= $tab . '-';
        } else {
            $return .= $tab . $setting->getClass() . ':';
        }
        if (count($ret)) {
            $return .= $this->compileTitle($setting);
            $return .= "\n";
            foreach ($ret as $str) {
                $return .= $str;
            }
        } else {
            $return .= ' ';
            $return .= $this->compileValue($setting, $val);
            $return .= "\n";
        }

        return $return;
    }

    /**
     * Компилирует подписи к настройкам
     *
     * @param Setting $setting
     * @return string
     */
    protected function compileTitle(Setting $setting)
    {
        if ($setting->getTitle()) {
            return ' # ' . $setting->getTitle();
        }
        return '';
    }

    /**
     * Компилирует значение настройки
     *
     * @param Setting $setting
     * @param string  $val
     * @return string
     */
    protected function compileValue(Setting $setting, $val)
    {
        if ($setting->getType()->getClass() == 'boolean') {
            $val = (int)$val ? 'true' : 'false';
        }
        $strings = ['rawstring', 'string', 'password', 'html'];
        if (in_array($setting->getType()->getClass(), $strings)) {
            if ($setting->getType()->getClass() != 'rawstring') {
                $val = Escaper::escapeWithDoubleQuotes($val);
                $val = str_replace('%', '%%', $val);
            } else {
                $val = '"' . $val . '"';
            }
        }
        return $val . $this->compileTitle($setting);
    }

}
