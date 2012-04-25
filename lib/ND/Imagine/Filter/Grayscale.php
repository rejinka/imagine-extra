<?php

namespace ND\Imagine\Filter;

use \Imagine\Filter\FilterInterface,
    \Imagine\Image\Color,
    \Imagine\Image\ImageInterface,
    \Imagine\Image\Point;


final class Grayscale extends OnPixelBased implements FilterInterface
{
	public function __construct()
	{
		parent::__construct(function (ImageInterface $image, Point $point)
		{
			$color = $image->getColorAt($point);
			$gray  = round(($color->getRed() + $color->getBlue() + $color->getGreen())/3);
			$image->draw()->dot($point, new Color(array(
				'red'   => $gray,
				'green' => $gray,
				'blue'  => $gray
			)));
		});
	}
}