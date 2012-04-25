<?php

namespace ND\Imagine\Filter;

use \Imagine\Filter\FilterInterface,
    \Imagine\Image\Color,
    \Imagine\Image\ImageInterface,
    \Imagine\Image\Point;


abstract class OnPixelBased
{
	protected $callback;

	public function __construct($callback)
	{
		$this->callback = $callback;
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
		for ($y = 0; $y < $image->getSize()->getHeight(); $y++)
			for ($x = 0; $x < $image->getSize()->getWidth(); $x++)
				call_user_func($this->callback, $image, new Point($x, $y));

		return $image;
	}
}
