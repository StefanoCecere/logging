<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Stefano Cecere <stefano.cecere@krur.com>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * This ViewHelper renders a Pagination of objects.
 *
 * = Examples =
 *
 * <code title="required arguments">
 * <f:widget.paginate objects="{blogs}" as="paginatedBlogs">
 *   // use {paginatedBlogs} as you used {blogs} before, most certainly inside
 *   // a <f:for> loop.
 * </f:widget.paginate>
 * </code>
 *
 * @package TYPO3
 * @subpackage tx_logging
 */
class Tx_Logging_ViewHelpers_Widget_PaginatebsViewHelper extends \TYPO3\CMS\Fluid\Core\Widget\AbstractWidgetViewHelper
{

    /**
     * @var Tx_Logging_ViewHelpers_Widget_Controller_PaginatebsController
     */
    protected $controller;

    /**
     * Inject controller
     *
     * @param Tx_Logging_ViewHelpers_Widget_Controller_PaginatebsController $controller
     * @return void
     */
    public function injectController(Tx_Logging_ViewHelpers_Widget_Controller_PaginatebsController $controller)
    {
        $this->controller = $controller;
    }

    /**
     * Render everything
     *
     * @param \Tx_Extbase_Persistence_QueryResultInterface|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface $objects
     * @param string $as
     * @param mixed $configuration
     * @param array $initial
     * @return string
     */
    public function render(
        \TYPO3\CMS\Extbase\Persistence\QueryResultInterface $objects,
        $as,
        $configuration = array('itemsPerPage' => 10, 'insertAbove' => false, 'insertBelow' => true),
        $initial = array()
    ) {
        return $this->initiateSubRequest();
    }
}
