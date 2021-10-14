<?php

namespace App\Game;

class Piece implements PieceInterface
{
    protected $key;
    protected $value1;
    protected $value2;
    protected $available = true;

    /**
     * @param array $values
     * @param bool $available
     */
    public function __construct(array $values)
    {
        $this->setValues($values);
        $this->setKey($values[0] . $values[1]);
    }

    /**
     * @param array $values
     */
    public function setValues(array $values)
    {
        $this->value1 = $values[0];
        $this->value2 = $values[1];
    }
    /**
     * @return int
     */
    public function getValues(): array
    {
        return [$this->value1, $this->value2];
    }

    /**
     * @return bool
     */
    public function isAvailable(): bool
    {
        return $this->available;
    }

    /**
     * @return $this
     */
    public function setUnavailable(): Piece
    {
        $this->available = false;
        return $this;
    }

    /**
     * @return int
     */
    public function getKey(): int
    {
        return intval($this->key);
    }

    /**
     * @param string $key
     * @return string
     */
    public function setKey(string $key): string
    {
        $this->key = $key;
        return $this->key;
    }

    /**
     * @return $this
     */
    public function reverseValues(): Piece
    {
        $this->setValues(array_reverse($this->getValues()));
        $this->setKey($this->value1 . $this->value2);

        return $this;
    }
}