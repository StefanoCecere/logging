<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Stefano Cecere <stefano.cecere@krur.com>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
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
 * @package tx_logging
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_Logging_Domain_Model_Dto_ReportsDemand extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * @var string
     */
    protected $user;

    /**
     * @var boolean
     */
    protected $onlylogged;

    /**
     * @var string
     */
    protected $action;

    /**
     * @var int
     */
    protected $site;

    /**
     * @var int
     */
    protected $year;

    /**
     * @var int
     */
    protected $month;

    /**
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param $user
     * @return void
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return boolean
     */
    public function getOnlylogged()
    {
        return ($this->onlylogged == 1);
    }

    /**
     * @param boolean $onlylogged
     * @return void
     */
    public function setOnlylogged($onlylogged)
    {
        $this->onlylogged = $onlylogged;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param $action
     * @return void
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * @return array
     */
    public function getSites()
    {
        $sites = array();
        // TODO: return query of rootpages
        // $sites[] = array('uid' => 0, 'title' => 'sitename');

        return $sites;
    }

    /**
     * @return int
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param $site
     * @return void
     */
    public function setSite($site)
    {
        $this->site = $site;
    }

    /**
     * @return array
     */
    public function getYears()
    {
        $currentYear = date("Y");
        $years = array();
        for ($y = $currentYear; $y >= 2014; $y--) {
            $years[] = $y;
        }
        return $years;
    }

    /**
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param $year
     * @return void
     */
    public function setYear($year)
    {
        $this->year = $year;
    }

    /**
     * @return array
     */
    public function getMonths()
    {
        $months = array();
        $months[] = array('uid' => 0, 'title' => 'Tutti');
        $months[] = array('uid' => 1, 'title' => 'Gennaio');
        $months[] = array('uid' => 2, 'title' => 'Febbraio');
        $months[] = array('uid' => 3, 'title' => 'Marzo');
        $months[] = array('uid' => 4, 'title' => 'Aprile');
        $months[] = array('uid' => 5, 'title' => 'Maggio');
        $months[] = array('uid' => 6, 'title' => 'Giugno');
        $months[] = array('uid' => 7, 'title' => 'Luglio');
        $months[] = array('uid' => 8, 'title' => 'Agosto');
        $months[] = array('uid' => 9, 'title' => 'Settembre');
        $months[] = array('uid' => 10, 'title' => 'Ottobre');
        $months[] = array('uid' => 11, 'title' => 'Novembre');
        $months[] = array('uid' => 12, 'title' => 'Dicembre');

        return $months;
    }

    /**
     * @return int
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @param $month
     * @return void
     */
    public function setMonth($month)
    {
        $this->month = $month;
    }
}
