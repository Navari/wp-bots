<?php


namespace Navari\Bot\Traits;


use JetBrains\PhpStorm\Pure;

trait ArrayLikeTrait
{
    /**
     * @param mixed $offset
     *
     * @return bool
     */
    #[Pure] public function offsetExists(mixed $offset): bool
    {
        return $this->isMethod($offset, 'get') || \property_exists($this, $offset);
    }

    /**
     * @param $method
     * @param $case
     *
     * @return bool|string
     */
    #[Pure] protected function isMethod($method, $case): bool|string
    {
        $uMethod = $case . \ucfirst($method);
        if (\method_exists($this, $uMethod)) {
            return $uMethod;
        }
        if (\method_exists($this, $method)) {
            return $method;
        }
        return false;
    }

    /**
     * @param mixed $offset
     *
     * @return mixed
     */
    public function offsetGet(mixed $offset): mixed
    {
        if ($run = $this->isMethod($offset, 'get')) {
            return $this->run($run);
        }

        if (\property_exists($this, $offset)) {
            if (isset($this->{$offset})) {
                return $this->{$offset};
            }

            if (isset($this::$offset)) {
                return $this::$offset;
            }
        }

        return null;
    }

    /**
     * @param $method
     *
     * @return mixed
     */
    protected function run($method): mixed
    {
        if (\is_array($method)) {
            $params = $method;
            $method = \array_shift($params);
            if ($params) {
                return \call_user_func_array([$this, $method], $params);
            }
        }
        return $this->$method();
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     *
     * @return void
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($run = $this->isMethod($offset, 'set')) {
            $this->run($run);
        } else {
            $this->{$offset} = $value;
        }
    }

    /**
     * @param mixed $offset
     *
     * @return void
     */
    public function offsetUnset(mixed $offset): void
    {
        if ($run = $this->isMethod($offset, 'unset')) {
            $this->run($run);
        } else {
            $this->{$offset} = null;
        }
    }

}
