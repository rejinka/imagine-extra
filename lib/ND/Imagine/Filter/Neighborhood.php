<?php

namespace ND\Imagine\Filter;

use \Imagine\Filter\FilterInterface,
    \Imagine\Image\Color,
    \Imagine\Image\ImageInterface,
    \Imagine\Image\Point;

use \ND\Imagine\Filter\Utilities\Matrix;

/**
 * The Neighborhood filter takes a matrix and calculates the color current pixel based on its neighborhood. For example:
 *
 *           a, b, c
 * Matrix =  d, e, f
 *           g, h, i
 *
 * and color{i, j} the color of the pixel at position (i, j). It calculates the color of pixel (x, y) like that:
 *
 * color (x, y) =   a * color(x-1, y-1) + b * color(x, y-1) + c * color(x+1, y-1)
 *                + d * color(x-1, y)   + e * color(x, y)   + f * color(x+1, y)
 *                + g * color(x-1, y+1) + h * color(x, y+1) + i * color(x+1, y+1)
 */
class Neighborhood implements FilterInterface
{
    /**
     * @var Matrix
     */
    protected $matrix;

	/**
	 * @param \ND\Imagine\Filter\Utilities\Matrix $matrix
	 */
    public function __construct(Matrix $matrix)
    {
        $this->matrix   = $matrix;
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
		// We reduce the usage of methods on the image to dramatically increase the performance of this algorithm.
		// Really... We need that performance...
		// Therefore we first build a matrix, that holds the colors of the image.
		$width  = $image->getSize()->getWidth();
		$height = $image->getSize()->getHeight();
		$byteData = new Matrix($width, $height);

		for ($x = 0; $x < $width; $x++)
			for ($y = 0; $y < $height; $y++)
				$byteData->setElementAt($x, $y, $image->getColorAt(new Point($x, $y)));

        $dHeight = (int) floor(($this->matrix->getHeight()-1)/2);
        $dWidth  = (int) floor(($this->matrix->getWidth()-1)/2);

        for ($y = $dHeight; $y < $height - $dHeight; $y++)
            for ($x = $dWidth; $x < $width - $dWidth; $x++)
            {
                $sumRed   = 0;
                $sumGreen = 0;
                $sumBlue  = 0;

				// calculate new color
                for ($boxX = $x-$dWidth, $matrixX = 0; $boxX <= $x + $dWidth; $boxX++, $matrixX++)
                    for ($boxY = $y-$dHeight, $matrixY = 0; $boxY <= $y + $dHeight; $boxY++, $matrixY++)
                    {
						$sumRed   = $sumRed + $this->matrix->getElementAt($matrixX, $matrixY) *
							$byteData->getElementAt($boxX, $boxY)->getRed();
						$sumGreen = $sumGreen + $this->matrix->getElementAt($matrixX, $matrixY) *
							$byteData->getElementAt($boxX, $boxY)->getGreen();
						$sumBlue  = $sumBlue + $this->matrix->getElementAt($matrixX, $matrixY) *
							$byteData->getElementAt($boxX, $boxY)->getBlue();
                    }

				// set new color - has to be between 0 and 255!
                $image->draw()->dot(new Point($x, $y), new Color(array(
					'red'   => max(0, min(255, $sumRed)),
					'green' => max(0, min(255, $sumGreen)),
					'blue'  => max(0, min(255, $sumBlue))
                )));
            }

        return $image;
    }
}