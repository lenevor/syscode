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
 * @link        https://lenevor.com
 * @copyright   Copyright (c) 2019 - 2022 Alexander Campo <jalexcam@gmail.com>
 * @license     https://opensource.org/licenses/BSD-3-Clause New BSD license or see https://lenevor.com/license or see /license.md
 */

namespace Syscodes\Components\Http\Contributors;

use Countable;
use Traversable;
use ArrayIterator;
use IteratorAggregate;

/**
 * Headers class is a container for HTTP headers.
 * 
 * @author Alexander Campo <jalexcam@gmail.com>
 */
class Headers implements IteratorAggregate, Countable
{
	protected const STRING_UPPER = '_ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	protected const STRING_LOWER = '-abcdefghijklmnopqrstuvwxyz';

	/**
	 * An array of HTTP headers.
	 * 
	 * @var array $herders
	 */
	protected $headers = [];
	
	/**
	 * Specifies the directives for the caching mechanisms in both
	 * the requests and the responses.
	 * 
	 * @var array $cacheControl
	 */
	protected $cacheControl = [];
	
	/**
	 * Constructor. The Headers class instance.
	 * 
	 * @param  array  $headers
	 * 
	 * @return void
	 */
	public function __construct(array $headers = [])
	{
		foreach ($headers as $key => $values) {
			$this->set($key, $values);
		}
	}
	
	/**
	 * Returns all the headers.
	 * 
	 * @return array
	 */
	public function all(): array
	{
		return $this->headers;
	}
	
	/**
	 * Returns the parameter keys.
	 * 
	 * @return array An array of parameter keys
	 */
	public function keys(): array
	{
		return array_keys($this->all());
	}
	
	/**
	 * Replaces the current HTTP headers by a new set.
	 * 
	 * @param  array  $headers
	 * 
	 * @return void
	 */
	public function replace(array $headers = []): void
	{
		$this->headers = [];
		$this->add($headers);
	}
	
	/**
	 * Adds multiple header to the queue.
	 * 
	 * @param  array  $headers  The header name
	 * 
	 * @return mixed
	 */
	public function add(array $headers = [])
	{
		foreach ($headers as $key => $values) {
			$this->set($key, $values);
		}
	}
	
	/**
	 * Gets a header value by name.
	 *
	 * @param  string  $key  The header name, or null for all headers
	 * @param  string|null  $default  The default value
	 * @param  bool  $option  Whether to return the option value or all header values
	 *
	 * @return mixed
	 */
	public function get(string $key, string $default =  null, bool $option = true): ?string
	{
		$key = strtr($key, self::STRING_UPPER, self::STRING_LOWER);
		
		$headers = $this->all();
		
		if ( ! array_key_exists($key, $headers)) {
			if (null === $default) {
				return $option ? null : [];
			}
			
			return $option ? $default : [$default];
		}
		
		if ($option) {
			return count($headers[$key]) ? $headers[$key][0] : $default;
		}
		
		return $headers[$key];
	}

	/**
	 * Sets a header by name.
	 * 
	 * @param  string  $key  The header name
	 * @param  string|string[]|null  $values  The value or an array of values
	 * @param  bool  $replace  If you want to replace the value exists by the header, 
	 * 					       it is not overwritten / overwritten when it is false
	 *
	 * @return self
	 */
	public function set(string $key, $values, bool $replace = true): self
	{
		$key = strtr($key, self::STRING_UPPER, self::STRING_LOWER);

		if (is_array($values)) {
			$values = array_values($values);

			if (true === $replace || ! isset($this->headers[$key])) {
				$this->headers[$key] = $values;
			} else {
				$this->headers[$key] = array_merge($this->headers[$key], $values);
			}
		} else {
			if (true === $replace || ! isset($this->headers[$key])) {
				$this->headers[$key] = [$values];
			} else {
				$this->headers[$key][] = $values;
			}
		}

		return $this;
	}

	/**
	 * Returns true if the HTTP header is defined.
	 * 
	 * @param  string  $key  The HTTP header
	 * 
	 * @return bool  true if the parameter exists, false otherwise
	 */
	public function has(string $key): bool
	{
		return array_key_exists(strtr($key, self::STRING_UPPER, self::STRING_LOWER), $this->all());
	}

	/**
	 * Removes a header.
	 * 
	 * @param  string  $name  The header name
	 * 
	 * @return mixed
	 */
	public function remove(string $key)
	{
		$key = strtr($key, self::STRING_UPPER, self::STRING_LOWER);

		unset($this->headers[$key]);

		if ('cache-control' === $key) {
			$this->cacheControl = [];
		}
	}
	
	/**
	 * Returns an iterator for headers.
	 * 
	 * @return \ArrayIterator An \ArrayIterator instance
	 */
	public function getIterator(): Traversable
	{
		return new ArrayIterator($this->headers);
	}
	
	/**
	 * Returns the number of headers.
	 * 
	 * @return int The number of headers
	 */
	public function count(): int
	{
		return count($this->headers);
	}
	
	/**
	 * Magic method.
	 * 
	 * Returns the headers as a string.
	 * 
	 * @return string The headers
	 */
	public function __toString(): string
	{
		if ( ! $headers = $this->all()) {
			return '';
		}
		
		ksort($headers);
		$max     = max(array_map('strlen', array_keys($headers))) + 1;
		$content = '';
		
		foreach ($headers as $name => $values) {
			$name = ucwords($name, '-');
			
			foreach ($values as $value) {
				$content .= sprintf("%-{$max}s %s\r\n", $name.':', $value);
			}
		}

		return $content;
	}
}