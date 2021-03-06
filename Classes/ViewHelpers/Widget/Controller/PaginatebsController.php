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
 * Paginate controller to create the pagination.
 * Extended version from fluid core
 *
 * @package TYPO3
 * @subpackage tx_logging
 */
class Tx_Logging_ViewHelpers_Widget_Controller_PaginatebsController extends \TYPO3\CMS\Fluid\Core\Widget\AbstractWidgetController
{

    /**
     * @var array
     */
    protected $configuration = array(
        'itemsPerPage' => 10,
        'insertAbove' => false,
        'insertBelow' => true,
        'maximumNumberOfLinks' => 99,
        'templatePath' => '',
        'addQueryStringMethod' => ''
    );

    /**
     * @var Tx_Extbase_Persistence_QueryResultInterface
     */
    protected $objects;

    /**
     * @var integer
     */
    protected $currentPage = 1;

    /**
     * @var string
     */
    protected $templatePath = '';

    /**
     * @var integer
     */
    protected $numberOfPages = 1;

    /**
     * @var integer
     */
    protected $maximumNumberOfLinks = 99;

    /** @var integer */
    protected $initialOffset = 0;
    /** @var integer */
    protected $initialLimit = 0;

    /**
     * Initialize the action and get correct configuration
     *
     * @return void
     */
    public function initializeAction()
    {
        $this->objects = $this->widgetConfiguration['objects'];
        $this->configuration = t3lib_div::array_merge_recursive_overrule(
            $this->configuration,
            (array) $this->widgetConfiguration['configuration'],
            true
        );
        $this->numberOfPages = intval(ceil(count($this->objects) / (integer) $this->configuration['itemsPerPage']));
        $this->maximumNumberOfLinks = (integer) $this->configuration['maximumNumberOfLinks'];
        $this->templatePath = t3lib_div::getFileAbsFileName($this->configuration['templatePath']);

        if (isset($this->widgetConfiguration['initial']['offset'])) {
            $this->initialOffset = (int) $this->widgetConfiguration['initial']['offset'];
        }
        if (isset($this->widgetConfiguration['initial']['limit'])) {
            $this->initialLimit = (int) $this->widgetConfiguration['initial']['limit'];
        }
    }

    /**
     * Main action
     *
     * @param integer $currentPage
     * @return void
     */
    public function indexAction($currentPage = 1)
    {
        // set current page
        $this->currentPage = (integer) $currentPage;
        if ($this->currentPage < 1) {
            $this->currentPage = 1;
        }

        if ($this->currentPage > $this->numberOfPages) {
            // set $modifiedObjects to NULL if the page does not exist
            $modifiedObjects = null;
        } else {
            // modify query
            $itemsPerPage = (integer) $this->configuration['itemsPerPage'];
            $query = $this->objects->getQuery();

            if ($this->currentPage === $this->numberOfPages && $this->initialLimit > 0) {
                $difference = $this->initialLimit - ((integer) ($itemsPerPage * ($this->currentPage - 1)));
                if ($difference > 0) {
                    $query->setLimit($difference);
                } else {
                    $query->setLimit($itemsPerPage);
                }
            } else {
                $query->setLimit($itemsPerPage);
            }
            if ($this->currentPage > 1) {
                $query->setOffset((integer) ($itemsPerPage * ($this->currentPage - 1)));
            }
            $modifiedObjects = $query->execute();
        }

        $this->view->assign(
            'contentArguments',
            array(
                $this->widgetConfiguration['as'] => $modifiedObjects
            )
        );
        $this->view->assign('configuration', $this->configuration);
        $this->view->assign('pagination', $this->buildPagination());

        if (!empty($this->templatePath)) {
            $this->view->setTemplatePathAndFilename($this->templatePath);
        }
    }

    /**
     * Returns an array with the keys "pages", "current", "numberOfPages", "nextPage" & "previousPage"
     *
     * @return array
     */
    protected function buildPagination()
    {
        $this->calculateDisplayRange();
        $pages = array();
        for ($i = $this->displayRangeStart; $i <= $this->displayRangeEnd; $i++) {
            $pages[] = array('number' => $i, 'isCurrent' => $i === $this->currentPage);
        }
        $pagination = array(
            'pages' => $pages,
            'current' => $this->currentPage,
            'numberOfPages' => $this->numberOfPages,
            'displayRangeStart' => $this->displayRangeStart,
            'displayRangeEnd' => $this->displayRangeEnd,
            'hasLessPages' => $this->displayRangeStart > 2,
            'hasMorePages' => $this->displayRangeEnd + 1 < $this->numberOfPages
        );
        if ($this->currentPage < $this->numberOfPages) {
            $pagination['nextPage'] = $this->currentPage + 1;
        }
        if ($this->currentPage > 1) {
            $pagination['previousPage'] = $this->currentPage - 1;
        }
        return $pagination;
    }

    /**
     * If a certain number of links should be displayed, adjust before and after
     * amounts accordingly.
     *
     * @return void
     */
    protected function calculateDisplayRange()
    {
        $maximumNumberOfLinks = $this->maximumNumberOfLinks;
        if ($maximumNumberOfLinks > $this->numberOfPages) {
            $maximumNumberOfLinks = $this->numberOfPages;
        }
        $delta = floor($maximumNumberOfLinks / 2);
        $this->displayRangeStart = $this->currentPage - $delta;
        $this->displayRangeEnd = $this->currentPage + $delta - ($maximumNumberOfLinks % 2 === 0 ? 1 : 0);
        if ($this->displayRangeStart < 1) {
            $this->displayRangeEnd -= $this->displayRangeStart - 1;
        }
        if ($this->displayRangeEnd > $this->numberOfPages) {
            $this->displayRangeStart -= $this->displayRangeEnd - $this->numberOfPages;
        }
        $this->displayRangeStart = (integer) max($this->displayRangeStart, 1);
        $this->displayRangeEnd = (integer) min($this->displayRangeEnd, $this->numberOfPages);
    }
}
