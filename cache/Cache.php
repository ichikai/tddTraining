<?php

namespace cache;

class Cache
{
    /**
     * @var array
     */
    private $cacheData = [];

    /**
     * @param string $name
     * @param mixed $value
     */
    public function put($name, $value)
    {
        if (!$this->validateName($name)) {
            throw new \RuntimeException("name should be string.");
        }

        $this->cacheData[$name] = $value;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function get($name)
    {
        if (!$this->validateName($name)) {
            throw new \RuntimeException("name should be string.");
        }

        if (!array_key_exists($name, $this->cacheData)) {
            return  null;
        }

        return $this->cacheData[$name];
    }

    /**
     * @param string $name
     * @return bool
     */
    public function remove($name)
    {
        if (!$this->validateName($name)) {
            throw new \RuntimeException("name should be string.");
        }

        if (!array_key_exists($name, $this->cacheData)) {
            return  false;
        }
        unset($this->cacheData[$name]);

        return true;
    }

    /**
     * @param mixed $name
     * @return bool
     */
    public function validateName($name)
    {
        if (is_string($name)) {
            return true;
        }

        return false;
    }
}
