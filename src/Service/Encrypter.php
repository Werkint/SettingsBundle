<?php
namespace Werkint\Bundle\SettingsBundle\Service;

class Encrypter
{
    /**
     * Шифрует параметр, используя id, как параметр ключа
     *
     * @param string $data
     * @param string $tag
     * @return string
     */
    public function keyCrypt($data, $tag = null)
    {
        // TODO: stub method
        return $data;
    }

    /**
     * Дешифрует параметр, используя id, как параметр ключа
     *
     * @param string $data
     * @param string $tag
     * @return string
     */
    public function keyDecrypt($data, $tag = null)
    {
        // TODO: stub method
        return $data;
    }
} 