<?php

namespace cache;

class Fifo
{
    /**
     * @var array
     */
    public $cacheData = [];

    /**
     * @var array
     */
    public $cacheOrder = [];

    /**
     * @var int
     */
    public $saveLimit;

    /**
     * Fifo constructor.
     * @param int $saveLimit
     */
    public function __construct($saveLimit)
    {
        if (!preg_match("/^[1-9][0-9]*$/", $saveLimit)) {
            throw new \RuntimeException("should be natural number");
        }
        $this->saveLimit = $saveLimit;
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function put($name, $value)
    {
        if (!$this->validateName($name)) {
            throw new \RuntimeException("name should be string.");
        }

        // preparation of overwrite
        $existKey = array_search($name, $this->cacheData);
        if ($existKey !== false) {
            unset($this->cacheOrder[$existKey]);
        }

        // delete over limit data
        if ($this->saveLimit == count($this->cacheData)) {
            $deletedName = array_shift($this->cacheOrder);
            unset($this->cacheData[$deletedName]);
        }

        // put
        $this->cacheData[$name] = $value;
        array_push($this->cacheOrder, $name);
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

        if (array_search($name, $this->cacheOrder) !== false) {
            return $this->cacheData[$name];
        }

        return null;
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

        $existKey = array_search($name, $this->cacheOrder);
        if ($existKey === false) {
            return  false;
        }

        unset($this->cacheData[$name]);
        unset($this->cacheOrder[$existKey]);

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
