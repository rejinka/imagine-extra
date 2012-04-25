<?php

namespace ND\Imagine\Filter;

use \Imagine\Exception\InvalidArgumentException,
	\Imagine\Filter\FilterInterface,
    \Imagine\Image\Color,
    \Imagine\Image\ImageInterface,
    \Imagine\Image\Point;


/**
 * The OnPixelBased takes a lambda function, and for each pixel, this function is called with the
 * image  (\Imagine\Image\ImageInterface) and the current point (\Imagine\Image\Point)
 */
class OnPixelBased implements FilterInterface
{
	protected $callback;

	public function __construct($callback)
	{
		if (!is_callable($callback))
			throw new InvalidArgumentException('$callback has to be a lambda function');

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
