<?php
namespace Werkint\Bundle\SettingsBundle\Service;

use Symfony\Component\Yaml\Escaper;

class Compiler
{
    protected $data;

    public function __construct(
        SettingsData $data
    ) {
        $this->data = $data;
    }

    protected $parameters = [];


    /**
     * Компилирует все настройки для всех окружений
     */
    public function compile(
        $directory,
        $environment
    ) {
        $envs = $this->data->getEnvironments();
        foreach ($envs as $env) {
            /** @var Environment $env */
            $filename = $directory . '/' . $environment . '_' . $env->getClass() . '.yml';
            if (file_exists($filename)) {
                unlink($filename);
            }
            $this->compileEnv($env, $filename);
        }
    }

    // -- Helpers ---------------------------------------

    /**
     * Компилирует все настройки заданного окружения
     * @param Environment $env
     * @param string      $filename
     */
    protected function compileEnv(Environment $env, $filename)
    {
        $this->parameters = [];
        $nodes = $this->data->getRootNodes();
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
     * @param Setting     $setting
     * @param Environment $env
     * @return bool
     */
    protected function checkEnv(Setting $setting, Environment $env)
    {
        if ($setting->getEnvironment()) {
            if ($setting->getEnvironment()->getClass() != $env->getClass()) {
                return false;
            }
        }
        return true;
    }

    /**
     * Компилирует настройку в строку yaml вместе с дочерними.
     * @param Environment $env
     * @param Setting     $setting
     * @param Setting     $parent
     * @param string      $tab
     * @return string
     */
    protected function compileNode(
        Environment $env,
        Setting $setting,
        Setting $parent = null,
        $tab = ''
    ) {
        if (!$this->checkEnv($setting, $env)) {
            return null;
        }

        $val = $this->data->keyDecrypt(
            $setting->getValue(), $setting->getId()
        );
        if ($setting->getParameter()) {
            $this->parameters[$setting->getParameter()] = $val;
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
