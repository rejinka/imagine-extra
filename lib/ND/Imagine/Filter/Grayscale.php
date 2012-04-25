<?php

namespace ND\Imagine\Filter;

use \Imagine\Exception\InvalidArgumentException,
    \Imagine\Filter\FilterInterface,
    \Imagine\Image\Color,
    \Imagine\Image\ImageInterface,
    \Imagine\Image\Point;

/**
 * The Grayscale filter calculates the gray-value based on RGB.
 */
final class Grayscale extends OnPixelBased implements FilterInterface
{
	public function __construct()
	{
		parent::__construct(function (ImageInterface $image, Point $point)
		{
			$color = $image->getColorAt($point);
			$gray  = min(255, round(($color->getRed() + $color->getBlue() + $color->getGreen())/3));

			$image->draw()->dot($point, new Color(array(
				'red'   => $gray,
				'green' => $gray,
				'blue'  => $gray
			)));
		});
	}
}