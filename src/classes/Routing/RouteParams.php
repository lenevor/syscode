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
 * @since       0.5.0
 */

namespace Syscodes\Routing;

use InvalidArgumentException;

/**
 * It uses an array to count the number of iterations of an item.
 * 
 * @author Javier Alexander Campo M. <jalexcam@gmail.com>
 */
class RouteParams
{
    /**
     * Parameter identifier.
     * 
     * @var array $params
     */
    protected $params = [];

    /**
     * Constructor. The RouteParams class instance.
     * 
     * @param  array  $params
     * 
     * @return void
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    /**
     * Makes count the item content in an array.
     * 
     * @return array|bool
     */
    public function toEachCountItems()
    {
        if (empty($this->params) && ! is_array($this->params))
        {
            throw new InvalidArgumentException(__('route.paramNoExist'));
        }

        foreach ($this->params as $key => $value)
        {
            if ($key === 0) continue;
            
            if (count($value) > 0)
            {
                return $value;
            }
        }

        return false;
    }

    /**
     * List an array all items.
     * 
     * @return array
     */
    public function toArray()
    {
        return $this->params;
    }
} 