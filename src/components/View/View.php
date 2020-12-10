<?php 

/**
 * Lenevor Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file license.md.
 * It is also available through the world-wide-web at this URL:
 * https://lenevor.com/license
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@Lenevor.com so we can send you a copy immediately.
 *
 * @package     Lenevor
 * @subpackage  Base
 * @author      Javier Alexander Campo M. <jalexcam@gmail.com>
 * @link        https://lenevor.com 
 * @copyright   Copyright (c) 2019-2020 Lenevor Framework 
 * @license     https://lenevor.com/license or see /license.md or see https://opensource.org/licenses/BSD-3-Clause New BSD license
 * @since       0.6.0
 */

namespace Syscodes\View;

use Throwable;
use ArrayAccess;
use Traversable;
use Syscodes\Support\Str;
use BadMethodCallException;
use InvalidArgumentException;
use Syscodes\Collections\Arr;
use Syscodes\Contracts\View\Engine;
use Syscodes\Contracts\Support\Webable;
use Syscodes\Contracts\Support\Arrayable;
use Syscodes\Contracts\Support\Renderable;
use Syscodes\Contracts\View\View as ViewContract;

/**
 * This class control the views.
 * 
 * @author Javier Alexander Campo M. <jalexcam@gmail.com>
 */
class View implements ArrayAccess, Webable, ViewContract
{
	/**
	 * Array of local variables.
	 *
	 * @var array $data
	 */
	protected $data = [];

	/**
	 * The engine implementation.
	 * 
	 * @var \Syscodes\Contracts\View\Engine $engine
	 */
	protected $engine;

	/**
	 * The view factory instance.
	 * 
	 * @var \Syscodes\View\factory $factory
	 */
	protected $factory;

	/**
	 * The path to the view file.
	 * 
	 * @var string $path
	 */
	protected $path;

	/**
	 * Get the name of the view.
	 *
	 * @var string $view
	 */
	protected $view;

	/**
	 * Constructor: Create a new view instance.
	 * 
	 * @param  \Syscodes\View\factory  $factory
	 * @param  \Syscodes\Contracts\View\Engine  $engine
	 * @param  string  $view
	 * @param  string  $path
	 * @param  array  $data
	 *
	 * @return void
	 *
	 * @throws \InvalidArgumentException
	 */
	public function __construct(Factory $factory, Engine $engine, $view, $path, $data = [])
	{
		$this->factory = $factory;
		$this->engine  = $engine;
		$this->view    = $view;
		$this->path    = $path;
		$this->data    = $data instanceof Arrayable ? $data->toArray() : (array) $data;
	}

	/**
	 * Get the string contents of the view.
	 *
	 * @example View::render();
	 *
	 * @param  \Callable|null  $callback  (null by default)
	 * 
	 * @return array|string
	 * 
	 * @throws \Throwable
	 */
	public function render(Callable $callback = null)
	{
		try
		{
			$contents = $this->renderContents();

			$response = isset($callback) ? $callback($this, $contents) : null;

			$this->factory->flushStateIfDoneRendering();

			return ! is_null($response) ? $response : $contents;
		}
		catch(Throwable $e)
		{
			$this->factory->flushState();

			throw $e;
		}
	}

	/**
	 * Get the contents of the view instance.
	 * 
	 * @return void
	 */
	protected function renderContents()
	{
		$this->factory->increment();

		$contents = $this->getContents();

		$this->factory->decrement();

		return $contents;
	}

	/**
	 * Get the evaluated contents of the view.
	 * 
	 * @return void
	 */
	protected function getContents()
	{
		return $this->engine->get($this->path, $this->getArrayData());
	}

	/**
	 * The view data will be extracted.
	 * 
	 * @return array
	 */
	public function getArrayData()
	{
		$data = array_merge($this->factory->getShared(), $this->data);
		
		return array_map(function ($value) {
			return ($value instanceof Renderable) ? $value->render() : $value;
		}, $data);
	}

	/**
	 * Get the sections of the rendered view.
	 * 
	 * @return array
	 * 
	 * @throws \Throwable
	 */
	public function renderSections()
	{
		return $this->render(function () {
			return $this->factory->getSections();
		});
	}

	/**
	 * Add a piece of data to the view.
	 *
	 * @example $view->assign($content, $data);
	 *
	 * @param  string|array  $key
	 * @param  mixed  $value  (null by default)
	 *
	 * @return $this
	 */
	public function assign($key, $value = null)
	{
		if (is_array($key)) 
		{
			$this->data = array_merge($this->data, $key);
		} 
		else 
		{
			$this->data = [$key => $value];
		}

		return $this;
	}

	/**
	 * Assigns a value by reference. The benefit of binding is that values can be altered
	 * without re-setting them. It is also possible to bind variables before they have values. 
	 * Assigned values will be available as a variable within the view file:
	 *     
	 * @example $view->bind('ref', $bar);
	 *
	 * @param  string  $key  Variable name
	 * @param  mixed  $value  Referenced variable
	 *
	 * @return $this
	 */
	public function bind($key, & $value) 
	{
		$this->data[$key] =& $value;

		return $this;
	}

	/**
	 * Get the array of view data.
	 * 
	 * @return array
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * Get the name of the view.
	 * 
	 * @return string
	 */
	public function getView()
	{
		return $this->view;
	}

	/**
	 * Get the path to the view file.
	 * 
	 * @return string
	 */
	public function getPath()
	{
		return $this->path;
	}

	/**
	 * Set the path to the view file.
	 * 
	 * @param  string  $path
	 * 
	 * @return void
	 */
	public function setPath($path)
	{
		$this->path = $path;
	}

	/**
	 * Get the view factory instance.
	 * 
	 * @return \Syscodes\View\factory
	 */
	public function getFactory()
	{
		return $this->factory;
	}

	/**
	 * Get the view's rendering engine.
	 * 
	 * @return \Syscodes\Contracts\View\Engine
	 */
	public function getEngine()
	{
		return $this->engine;
	}

	/**
	 * Searches for the given variable and returns its value.
	 * Local variables will be returned before global variables.
	 *
	 * @example  $value = $view->get('foo', 'bar');
	 *
	 * If the key is not given or null, the entire data array is returned.
	 *
	 * @param  string  $key      The variable name
	 * @param  mixed   $default  The default value to return 
	 *
	 * @return mixed
	 *
	 * @uses   \Syscodes\Contracts\Core\Lenevor
	 *
	 * @throws \InvalidArgumentException
	 */
	public function &get($key, $default = null)
	{
		if (strpos($key, '.') === false)
		{
			if (array_key_exists($key, $this->data))
			{
				return $this->data[$key];
			}
			else
			{
				throw new InvalidArgumentException(__('view.variableNotSet'));
			}
		}
		else
		{
			return value($default);
		}
	}

	/**
	 * Assigns a variable by name. Assigned values will be available as a
	 * variable within the view file:
	 *
	 * This value can be accessed as $var within the view
	 * @example $view->set(array('food' => 'bread', 'beverage' => 'water'));
	 *
	 * @param  string|array  $key    Variable name
	 * @param  mixed         $value  Value
	 *
	 * @return $this
	 *
	 * @uses   instanceof \Traversable
	 */
	public function set($key, $value = null) 
	{
		if (is_array($key) || $key instanceof Traversable)
		{
			foreach ($key as $name => $value) 
			{
				$this->set($name, $value);
			}
		}
		else
		{
			if (strpos($key, '.') === false)
			{
				$this->data[$key] = $value;
			}
			else
			{
				Arr::set($this->data, $key, $value);
			}
		}

		return $this;
	}

	/*
    |-----------------------------------------------------------------
    | ArrayAccess Methods
    |-----------------------------------------------------------------
    */

	/**
	 * Whether or not an offset exists.
	 * 
	 * @param  string  $offset
	 * 
	 * @return bool
	 */
	public function offsetExists($offset)
	{
		return array_key_exists($offset, $this->data);
	}

	/**
	 * Returns the value at specified offset.
	 * 
	 * @param  string  $offset
	 * 
	 * @return mixed
	 */
	public function offsetGet($offset)
	{
		return $this->data[$offset];
	}

	/**
	 * Assigns a value to the specified offset
	 * 
	 * @param  string  $offset
	 * @param  mixed  $value
	 * 
	 * @return void
	 */
	public function offsetSet($offset, $value)
	{
		$this->assign($offset, $value);
	}

	/**
	 * Unsets an offset.
	 * 
	 * @param  string  $offset
	 * 
	 * @return void
	 */
	public function offsetUnset($offset)
	{
		unset($this->data[$offset]);
	}

	/**
	 * Magic method. Searches for the given variable and returns its value.
	 * Local variables will be returned before global variables.
	 *
	 * @example $value = $view->var;
	 * 
	 * @param  string  $key  Variable name
	 *
	 * @return mixed
	 *
	 * @throws \Syscodes\LenevorException
	 */
	public function &__get($key) 
	{
		return $this->get($key);
	}

	/**
	 * Magic method. Calls [$this->set] with the same parameters.
	 *
	 * @example $view->var = 'something';
	 *
	 * @param  string  $key    Variable name
	 * @param  mixed   $value  Value
	 *
	 * @return void
	 */
	public function __set($key, $value) 
	{
		$this->set($key, $value);
	}

	/**
	 * Magic method. Determines if a variable is set.
	 *
	 * @example isset($view->foo);
	 *
	 * Variables are not considered to be set.
	 *
	 * @param  string  $key  variable name
	 *
	 * @return boolean
	 */
	public function __isset($key) 
	{
		return isset($this->data[$key]);
	}

	/**
	 * Magic method. Unsets a given variable.
	 *
	 * @example unset($view->var);
	 *
	 * @param  string  $key  Variable name
	 *
	 * @return void
	 */
	public function __unset($key) 
	{
		unset($this->data[$key]);
	}

	/**
	 * Magic Method for handling dynamic functions.
	 * 
	 * @param  string  $method
	 * @param  array  $parameters
	 * 
	 * @return mixed
	 * 
	 * @throws \BadMethodCallException;
	 */
	public function __call($method, $parameters)
	{
		if (Str::startsWith($method, 'assign'))
		{
			$name = Str::camelcase(substr($method, 4));

			return $this->assign($name, $parameters[0]);
		}

		throw new BadMethodCallException(sprintf(
			'Method %s::%s does not exist.', static::class, $method)
		);
	}

	/**
	 * Get content as a string of HTML.
	 * 
	 * @return string
	 */
	public function toHtml()
	{
		return $this->render();
	}

	/**
	 * Magic method. Returns the output of [static::render].
	 *
	 * @return string
	 *
	 * @uses   View->render()
	 * 
	 * @throws \Throwable
	 */
	public function __toString() 
	{
		return $this->render();
	}
}