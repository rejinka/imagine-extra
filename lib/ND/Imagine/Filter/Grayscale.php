<?php

namespace ND\Imagine\Filter;

use \Imagine\Exception\InvalidArgumentException,
    \Imagine\Filter\FilterInterface,
    \Imagine\Image\Color,
    \Imagine\Image\ImageInterface,
    \Imagine\Image\Point;

/**
 * The Grayscale filter calculates the gray-value based on RGB. Furthermore you can adjust the brightness of the
 * result.
 */
final class Grayscale extends OnPixelBased implements FilterInterface
{
	/**
	 * @param int $brightness the brightnes of the resulting image
	 * @throws \Imagine\Exception\InvalidArgumentException if $brightness < 0
	 */
	public function __construct($brightness = 1)
	{
	   	if ($brightness < 0)
			throw new InvalidArgumentException('Brightness has to be positive');

		parent::__construct(function (ImageInterface $image, Point $point) use ($brightness)
		{
			$color = $image->getColorAt($point);
			$gray  = round(($color->getRed() + $color->getBlue() + $color->getGreen())/3) * $brightness;

			if (255 < $gray)
				$gray = 255;

			$image->draw()->dot($point, new Color(array(
				'red'   => $gray,
				'green' => $gray,
				'blue'  => $gray
			)));
		});
	}
}