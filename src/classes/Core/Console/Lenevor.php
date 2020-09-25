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

namespace Syscodes\Core\Console;

use Closure;
use Exception;
use Throwable;
use ReflectionClass;
use Syscodes\Support\Str;
use Syscodes\Support\Finder;
use Syscodes\Collections\Arr;
use Syscodes\Contracts\Core\Application;
use Syscodes\Contracts\Events\Dispatcher;
use Syscodes\Contracts\Debug\ExceptionHandler;
use Syscodes\Console\Application as LenevorConsole;
use Syscodes\Debug\FatalExceptions\FatalThrowableError;

/**
 * The Lenevor class is the heart of the system when use 
 * the console of commands in framework.
 *
 * @author Javier Alexander Campo M. <jalexcam@gmail.com>
 */
class Lenevor extends LenevorConsole
{

}