<?php

namespace ND\Imagine\Filter;

use \Imagine\Exception\InvalidArgumentException,
    \Imagine\Filter\FilterInterface,
    \Imagine\Image\Color,
    \Imagine\Image\ImageInterface,
    \Imagine\Image\Point;

use \ND\Imagine\Filter\Utilities\Matrix;


class Borderdetection extends Neighborhood implements FilterInterface
{
    const VARIANT_ONE   = 0;
    const VARIANT_TWO   = 1;
    const VARIANT_THREE = 2;

    public function __construct($variant = self::VARIANT_ONE)
    {
        if (self::VARIANT_ONE === $variant)
            $matrix = new Matrix(3, 3, array(
                0,  1, 0,
                1, -4, 1,
                0,  1, 0
            ));
        else if (self::VARIANT_TWO === $variant)
            $matrix = new Matrix(3, 3, array(
                1,  1, 1,
                1, -8, 1,
                1,  1, 1
            ));
        else if (self::VARIANT_THREE === $variant)
            $matrix = new Matrix(3, 3, array(
                -1, 2, -1,
                2, -4,  2,
                -1, 2, -1
            ));
        else
            throw new InvalidArgumentException('Variant ' . $variant . ' unknown');

        parent::__construct($matrix);
    }

    /**
     * Applies scheduled transformation to ImageInterface instance
     * Returns processed ImageInterface instance
     *
     * @param \Imagine\Image\ImageInterface $image
     *
     * @return \Imagine\Image\ImageInterface
     */
    public function apply(ImageInterface $image)
    {
        $grayscale = new Grayscale();
        return parent::apply($grayscale->apply($image));
    }
}