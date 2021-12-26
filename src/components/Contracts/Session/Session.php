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
 * @copyright   Copyright (c) 2019 - 2021 Alexander Campo <jalexcam@gmail.com>
 * @license     https://opensource.org/licenses/BSD-3-Clause New BSD license or see https://lenevor.com/license or see /license.md
 */

namespace Syscodes\Components\Contracts\Session;

/**
 * Expected behavior of a session container used with Lenevor.
 * 
 * @author Alexander Campo <jalexcam@gmail.com>
 */
interface Session
{
    /**
     * Get the name of the session.
     * 
     * @return string
     */
    public function getName(): string;

    /**
     * Start the session.
     * 
     * @return bool
     */
    public function start();

    /**
     * Get all of the session data.
     * 
     * @return array
     */
    public function all(): array;

    /**
     * Get the current session ID.
     * 
     * @return string
     */
    public function getId(): string;

    /**
     * Set the session ID.
     * 
     * @param  string  $id
     * 
     * @return void
     */
    public function setId($id): void;

    /**
     * Determine if this is a valid session ID.
     * 
     * @param  string  $id
     * 
     * @return bool
     */
    public function isValidId($id): bool;

    /**
     * Save the session data to storage.
     * 
     * @return void
     */
    public function save();

    /**
     * Remove one or many items from the session.
     * 
     * @param  string|array  $keys
     * 
     * @return void
     */
    public function pull($keys);

    /**
     * Push a value onto a session array.
     * 
     * @param  string  $key
     * @param  mixed  $value
     * 
     * @return void
     */
    public function push($key, $value);

    /**
     * Checks if an a key is present and not null.
     * 
     * @param  string|array  $key
     * 
     * @return bool
     */
    public function has($key): bool;

    /**
     * Get an key from the session.
     * 
     * @param  string  $key
     * @param  mixed  $default
     * 
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Put a key / value pair or array of key / value pairs in the session.
     * 
     * @param  string|array  $key
     * @param  mixed  $value
     * 
     * @return mixed
     */
    public function put($key, $value = null);

    /**
     * Remove an key from the session.
     * 
     * @param  string  $key
     * 
     * @return mixed
     */
    public function remove($key);

    /**
     * Flash a key / value pair to the session.
     * 
     * @param  string  $key
     * @param  mixed  $value
     * 
     * @return void
     */
    public function flash(string $key, $value = true);

    /**
     * Remove all of the keys from the session.
     * 
     * @return void
     */
    public function flush();

    /**
     * Get the CSRF token value.
     * 
     * @return string
     */
    public function token();

    /**
     * Regenerate the CSRF token value.
     * 
     * @return void
     */
    public function regenerateToken();

    /**
     * Generate a new session identifier.
     * 
     * @param  bool  $destroy
     * 
     * @return void
     */
    public function regenerate($destroy = false);

    /**
     * Generate a new session ID for the session.
     * 
     * @param  bool  $destroy
     * 
     * @return bool
     */
    public function migrate($destroy = false): bool;

    /**
     * Determine if the session has been started.
     * 
     * @return bool
     */
    public function isStarted(): bool;
}