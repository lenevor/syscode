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

namespace Syscodes\Components\Support;

use Syscodes\Components\Support\InflectRules\Rules;
use Syscodes\Components\Support\InflectRules\English\Pluralize;
use Syscodes\Components\Support\InflectRules\English\Singularize;
use Syscodes\Components\Support\InflectRules\English\Uncountable;
use Syscodes\Components\Support\InflectRules\English\Irregularize;

/**
 * Allows identify the plural or singular of a word.
 * 
 * @author Alexander Campo <jalexcam@gmail.com>
 */
class Inflector
{
    /**
     * Gets the irregular singular word.
     * 
     * @var array $irregularSingles
     */
    protected $irregularSingles = [];
    
    /**
     * Gets the irregular plural word.
     * 
     * @var array $irregularPlurals
     */
    protected $irregularPlurals = [];
    
    /**
     * Gets a string plural with your rules.
     * 
     * @var array $pluralRules
     */
    protected $pluralRules = [];
    
    /**
     * Gets a string singular with your rules.
     * 
     * @var array $singularRules
     */
    protected $singularRules = [];
    
    /**
     * Get the rules of application.
     * 
     * @var \Syscodes\Components\Support\InflectRules\Rules $aplicator
     */
    protected $aplicator;
    
    /**
     * Constructor. Create a new Injflector instance.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->pluralRules   = Pluralize::all();
        $this->singularRules = Singularize::all();
        $this->aplicator     = new Rules();
        
        foreach (Irregularize::all() as $rule) {
            $this->irregularSingles[$rule[0]] = $rule[1];
            $this->irregularPlurals[$rule[1]] = $rule[0];
        }
        
        foreach (Uncountable::all() as $rule) {
            $this->addUncountableRule($rule);
        }
    }

    /**
     * @param      $text
     * @param      $count
     * @param bool $includeCount
     *
     * @return string
     */
    public function pluralize($text, $count, $includeCount = false)
    {
        return ($includeCount ? $count . " " : "")
            . (intval($count) === 1
                ? $this->singular($text)
                : $this->plural($text)
            );
    }

    /**
     * @param string $text
     *
     * @return string
     */
    public function plural($text)
    {
        $callback = $this->aplicator->replace(
            $this->irregularSingles,
            $this->irregularPlurals,
            $this->pluralRules
        );

        return $callback($text);
    }

    /**
     * @param string $text
     *
     * @return bool
     */
    public function isPlural($text)
    {
        return !$this->isSingular($text);
    }

    /**
     * @param string $text
     *
     * @return string
     */
    public function singular($text)
    {
        $callback = $this->aplicator->replace(
            $this->irregularPlurals,
            $this->irregularSingles,
            $this->singularRules
        );

        return $callback($text);
    }

    /**
     * @param string $text
     *
     * @return bool
     */
    public function isSingular($text)
    {
        $callback = $this->aplicator->checkWord(
            $this->irregularPlurals,
            $this->irregularSingles,
            $this->singularRules
        );

        return $callback($text);
    }

    /**
     * @see \plejus\PhpPluralize\Rules\PluralizationRule
     *
     * @param string $rule        Regex string to find
     * @param string $replacement Replacement with regex match
     */
    public function addPluralRule($rule, $replacement)
    {
        $this->pluralRules[] = [$rule, $replacement];
    }

    /**
     * @see \plejus\PhpPluralize\Rules\SingularizationRule
     *
     * @param string $rule        Regex string to find
     * @param string $replacement Replacement with regex match
     */
    public function addSingularRule($rule, $replacement)
    {
        $this->singularRules[] = [$rule, $replacement];
    }

    /**
     * @param string $single
     * @param string $plural
     */
    public function addIrregularRule($single, $plural)
    {
        $this->irregularSingles[$single] = strtolower($plural);
        $this->irregularPlurals[$plural] = strtolower($single);
    }

    /**
     * @param string $rule Uncountable word or Regex string
     */
    public function addUncountableRule($rule)
    {
        if (substr($rule, 0, 1) === '/') {
            $this->pluralRules[]   = [$rule, '$0'];
            $this->singularRules[] = [$rule, '$0'];
        } else {
            $this->aplicator->addUncountable($rule);
        }
    }
}