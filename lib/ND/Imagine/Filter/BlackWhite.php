<?php

namespace ND\Imagine\Filter;

use \Imagine\Exception\InvalidArgumentException,
    \Imagine\Filter\FilterInterface,
    \Imagine\Image\Color,
    \Imagine\Image\ImageInterface,
    \Imagine\Image\Point;


/**
 * The BlackWhite filter calculates the gray-value based on RGB and sets the color of the pixel on either
 * black or white, depending on a border.
 */
final class BlackWhite extends OnPixelBased implements FilterInterface
{
	/**
	 * @param $border If the grayed pixel has a value smaller than $border, the pixel will be whiten. Otherwise the
	 *                color will be black.
	 * @throws \Imagine\Exception\InvalidArgumentException if border < 0 or border > 255
	 */
	public function __construct($border)
	{
		if ($border < 0 || $border > 255)
			throw new InvalidArgumentException('Border has to be between 0 and 255');

		parent::__construct(function (ImageInterface $image, Point $point) use ($border)
		{
			$newColor = $image->getColorAt($point)->getRed() < $border ? 255 : 0;

			$image->draw()->dot($point, new Color(array(
				'red'   => $newColor,
				'green' => $newColor,
				'blue'  => $newColor
			)));
		});
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