<?php

namespace ND\Imagine\Filter;

use \Imagine\Filter\FilterInterface,
    \Imagine\Image\Color,
    \Imagine\Image\ImageInterface,
    \Imagine\Image\Point;

use \ND\Imagine\Filter\Utilities\Matrix;


class Neighborhood implements FilterInterface
{
    /**
     * @var Matrix
     */
    protected $matrix = array();

    public function __construct(Matrix $matrix)
    {
        $this->matrix = $matrix;
    }

    /**
     * Applies scheduled transformation to ImageInterface instance
     * Returns processed ImageInterface instance
     *
     * @param \Imagine\Image\ImageInterface $image
     *
     * @return \Imagine\Image\ImageInterface
     */
    function apply(ImageInterface $image)
    {
        $oldImage = $image->copy();

        $dHeight = (int) floor(($this->matrix->getHeight()-1)/2);
        $dWidth  = (int) floor(($this->matrix->getWidth()-1)/2);

        for ($y = $dHeight; $y < $image->getSize()->getHeight() - $dHeight; $y++)
            for ($x = $dWidth; $x < $image->getSize()->getWidth() - $dWidth; $x++)
            {
                $sumRed   = 0;
                $sumGreen = 0;
                $sumBlue  = 0;

                for ($boxX = $x-$dWidth, $matrixX = 0; $boxX <= $x + $dWidth; $boxX++, $matrixX++)
                    for ($boxY = $y-$dHeight, $matrixY = 0; $boxY <= $y + $dHeight; $boxY++, $matrixY++)
                    {
                            $sumRed   = $sumRed + $this->matrix->getElementAt($matrixX, $matrixY) *
                                $oldImage->getColorAt(new Point($boxX, $boxY))->getRed();
                            $sumGreen = $sumGreen + $this->matrix->getElementAt($matrixX, $matrixY) *
                                $oldImage->getColorAt(new Point($boxX, $boxY))->getGreen();
                            $sumBlue  = $sumBlue + $this->matrix->getElementAt($matrixX, $matrixY) *
                                $oldImage->getColorAt(new Point($boxX, $boxY))->getBlue();
                    }

                if ($sumRed < 0)
                    $sumRed = 0;
                else if (255 < $sumRed)
                    $sumRed = 255;

                if ($sumGreen < 0)
                    $sumGreen = 0;
                else if (255 < $sumGreen)
                    $sumGreen = 255;

                if ($sumBlue < 0)
                    $sumBlue = 0;
                else if (255 < $sumBlue)
                    $sumBlue = 255;

                $image->draw()->dot(new Point($x, $y), new Color(array(
                    'red'   => $sumRed,
                    'green' => $sumGreen,
                    'blue'  => $sumBlue
                )));
            }

        return $image;
    }
}
