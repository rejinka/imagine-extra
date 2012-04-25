<?php

namespace ND\Imagine\Filter;

use \Imagine\Filter\FilterInterface,
    \Imagine\Image\Color,
    \Imagine\Image\ImageInterface,
    \Imagine\Image\Point;

/**
 * The Negation filter negates every color of every pixel. Negation means calculating 255 - color.
 */
final class Negation extends OnPixelBased implements FilterInterface
{
	public function __construct()
	{
		parent::__construct(function(ImageInterface $image, Point $point)
		{
			$color = $image->getColorAt($point);
			$image->draw()->dot($point, new Color(array(
				'red'   => 255 - $color->getRed(),
				'green' => 255 - $color->getGreen(),
				'blue'  => 255 - $color->getBlue()
			)));
		});
	}
}
