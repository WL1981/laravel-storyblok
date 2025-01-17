<?php


namespace Riclep\Storyblok;

use Exception;
use Illuminate\Support\Str;
use Riclep\Storyblok\Traits\HasMeta;

abstract class Field
{
	use HasMeta;

	/**
	 * @var array|string the content of the field
	 */
	protected $content;

	/**
	 * @var Block reference to the parent block
	 */
	protected $block;


	/**
	 * Key/value pairs of additional content you want the
	 * field to have access to. Pass anything you like
	 *
	 * @var array
	 */
	public $with;

	/**
	 * Creates the new field taking it’s content and a reference
	 * to the parent Block
	 *
	 * @param $content
	 * @param $block
	 */
	public function __construct($content, $block)
	{
		$this->content = $content;
		$this->block = $block;

		if (method_exists($this, 'init')) {
			$this->init();
		}
	}

	/**
	 * Returns the content of the Field
	 *
	 * @return array|string
	 */
	public function content() {
		return $this->content;
	}

	/**
	 * Returns the Block this Field belongs to
	 *
	 * @return Block
	 */
	public function block() {
		return $this->block;
	}

	/**
	 * Checks if the requested key is in the Field’s content
	 *
	 * @param $key
	 * @return bool
	 */
	public function has($key) {
		return array_key_exists($key, $this->content);
	}


	/**
	 * Allows key/value pairs to be passed into the Field such as CSS
	 * classes when rendering __toString or another content.
	 * Example: {{ $field->with(['classes' => 'my-class']) }}
	 *
	 * @param $with
	 * @return Field
	 */
	public function with($with) {
		$this->with = $with;

		return $this;
	}

	/**
	 * Magic accessor to pull content from the content. Works just like
	 * Laravel’s model accessors.
	 *
	 * @param $key
	 * @return false|mixed|string
	 */
	public function __get($key) {
		$accessor = 'get' . Str::studly($key) . 'Attribute';

		if (method_exists($this, $accessor)) {
			return $this->$accessor();
		}

		try {
			if ($this->has($key)) {
				return $this->content[$key];
			}

			return false;
		} catch (Exception $e) {
			return 'Caught exception: ' .  $e->getMessage();
		}
	}

	/**
	 * Prints the Field as a string
	 *
	 * @return string
	 */
	abstract public function __toString();
}