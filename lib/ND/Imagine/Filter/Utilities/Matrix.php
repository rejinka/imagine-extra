<?php

namespace ND\Imagine\Filter\Utilities;

use \Imagine\Exception\InvalidArgumentException;


class Matrix
{
    protected $elements = array();

    protected $width;

    protected $height;

    public function __construct($width, $height, $elements = array())
    {
        if ($width < 0)
            throw new InvalidArgumentException('Width must be > 0');

        if ($height < 0)
            throw new InvalidArgumentException('Height must be > 0');

        $this->width  = $width;
        $this->height = $height;

        $this->elements = $elements;
        if ($this->width*$this->height < count($this->elements))
            $this->elements = array_merge(
                $this->elements,
                array_fill(count($this->elements), $width*$height-count($this->elements), 0)
            );
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function setElementAt($x, $y, $value)
    {
         $this->elements[$this->calculatePosition($x, $y)] = $value;
    }

    public function getElementAt($x, $y)
    {
        return $this->elements[$this->calculatePosition($x, $y)];
    }

    protected function calculatePosition($x, $y)
    {
        if (0 > $x || 0 > $y || $this->width <= $x || $this->height <= $y)
            throw new InvalidArgumentException('(' . $x . ', ' . $y . '): Out of range');

        return $x * $this->height + $y;
    }
}
