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

namespace Syscodes\Components\View\Transpilers\Concerns;

use Syscodes\Components\View\Factory as ViewFactory;

/**
 * Trait TranspilesLayouts.
 * 
 * @author Alexander Campo <jalexcam@gmail.com>
 */
trait TranspilesLayouts
{
    /**
     * Transpile the extends statements into valid PHP.
     * 
     * @param  string  $expression
     * 
     * @return string
     */
    protected function transpileExtends($expression)
    {
        $expression = $this->stripParentheses($expression);

        $data = "<?php echo \$__env->make({$expression}, \Syscodes\Components\Collections\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>";

        $this->footer[] = $data;

        return '';
    }

    /**
     * Transpile the section statements into valid PHP.
     * 
     * @param  string  $expression
     * 
     * @return string
     */
    protected function transpileSection($expression)
    {
        return "<?php \$__env->startSection{$expression}; ?>";
    }

    /**
     * Transpile the give statements into valid PHP.
     * 
     * @param  string  $expression
     * 
     * @return string
     */
    protected function transpileGive($expression)
    {
        return "<?php echo \$__env->giveContent{$expression}; ?>";
    }

    /**
     * Replace the @parent directive to a placeholder.
     * 
     * @return string
     */
    protected function transpileParent()
    {
        return ViewFactory::parent();
    }

    /**
     * Transpile the append statements into valid PHP.
     * 
     * @return string
     */
    protected function transpileAppend()
    {
        return '<?php $__env->appendSection(); ?>';
    }

    /**
     * Transpile the show statements into valid PHP.
     * 
     * @return string
     */
    protected function transpileShow()
    {
        return '<?php echo $__env->showSection(); ?>';
    }

    /**
     * Transpile the end-section statements into valid PHP.
     * 
     * @return string
     */
    protected function transpileStop()
    {
        return '<?php $__env->stopSection(); ?>';
    }
}