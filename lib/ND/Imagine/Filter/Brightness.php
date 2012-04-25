<?php

namespace ND\Imagine\Filter;

use \Imagine\Exception\InvalidArgumentException,
    \Imagine\Filter\FilterInterface,
    \Imagine\Image\Color,
    \Imagine\Image\ImageInterface,
    \Imagine\Image\Point;

/**
 * The Brightness filter adjusts the brightness of the image.
 */
final class Brightness extends OnPixelBased implements FilterInterface
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

			$image->draw()->dot($point, new Color(array(
				'red'   => min(255, $color->getRed() * $brightness),
				'green' => min(255, $color->getGreen() * $brightness),
				'blue'  => min(255, $color->getBlue() * $brightness)
			)));
		});
	}
}