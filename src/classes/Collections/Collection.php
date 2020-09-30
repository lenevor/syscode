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
 * @since       0.7.2
 */

namespace Syscodes\Collections;

use Closure;
use Countable;
use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;

/**
 * Allows provide a way for working with arrays of data.
 * 
 * @author Javier Alexander Campo M. <jalexcam@gmail.com>
 */
class Collection implements ArrayAccess, IteratorAggregate, Countable
{
    /**
     * The items contained in the collection.
     * 
     * @var array $items
     */
    protected $items = [];

    /**
     * Constructor. Create a new Collection instance.
     * 
     * @param  mixed  $items
     * 
     * @return void
     */
    public function __construct($items = [])
    {
        $this->items = $this->getArrayItems($items);
    }

    /**
     * Get all of the items in the collection.
     * 
     * @return array
     */
    public function all()
    {
        return $this->items;
    }

    /**
     * Diff the collection with the given items.
     * 
     * @param  mixed  $items
     * 
     * @return static
     */
    public function diff($items)
    {
        return new static(array_diff($this->items, $this->getArrayItems($items)));
    }

    /**
     * Execute a callback over each item.
     * 
     * @param  \Closure  $callback
     * 
     * @return $this
     */
    public function each(Closure $callback)
    {
        array_map($callback, $this->items);

        return $this;
    }

    /**
     * Run a map over each of the items.
     * 
     * @param  \Callable  $callback
     * 
     * @return static
     */
    public function map(Closure $callback)
    {
        $keys = array_keys($this->items);

        $items = array_map($callback, $this->items, $keys);

        return new static(array_combine($keys, $items));
    }

     /**
     * Reset the keys of the collection.
     * 
     * @return static
     */
    public function keys()
    {
        return new static(array_keys($this->items));
    }

    /**
     * Push an item onto the end of the collection.
     * 
     * @param  mixed  $values  [optional]
     * 
     * @return $this
     */
    public function push(...$values)
    {
        foreach ($values as $value)
        {
            $this->items[] = $value;
        }

        return $this;
    }

    /**
     * Return only unique items from the collection array.
     * 
     * @return static
     */
    public function unique()
    {
        return new static(array_unique($this->items));
    }

    /**
     * Add an item in the collection.
     * 
     * @param  mixed  $item
     * 
     * @return $this
     */
    public function add($item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Reset the keys on the underlying array.
     * 
     * @return static
     */
    public function values()
    {
        return new static(array_values($this->items));
    }

    /**
     * Results array of items from Collection.
     * 
     * @param  \Syscodes\Collections\Collection|array  $items
     * 
     * @return array
     */
    private function getArrayItems($items)
    {
        if (is_array($items))
        {
            return $items;
        }
        elseif ($items instanceof Collection)
        {
            return $items->all();
        }

        return (array) $items;
    }

    /*
    |-----------------------------------------------------------------
    | ArrayIterator Methods
    |-----------------------------------------------------------------
    */

    /**
     * Get an iterator for the items.
     * 
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }

    /*
    |-----------------------------------------------------------------
    | Countable Methods
    |-----------------------------------------------------------------
    */

    /**
     * Count the number of items in the collection.
     * 
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }

    /*
    |-----------------------------------------------------------------
    | ArrayAccess Methods
    |-----------------------------------------------------------------
    */

    /**
     * Determine if a given offset exists.
     * 
     * @param  string  $offset
     * 
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }

    /**
     * Get the value at a given offset.
     * 
     * @param  string  $offset
     * 
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->items[$offset];
    }

    /**
     * Set the value at a given offset.
     * 
     * @param  string  $offset
     * @param  mixed  $value
     * 
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset))
        {
            $this->items[] = $value;
        }
        else
        {
            $this->items[$offset] = $value;
        }
    }

    /**
     * Unset the value at a given offset.
     * 
     * @param  string  $offset
     * 
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }
}