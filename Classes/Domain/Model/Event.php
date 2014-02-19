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
class Tx_Logging_Domain_Model_Event extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * @var int
     */
    protected $crdate;

    /**
     * @var int
     */
    protected $sessionId;

    /**
     * @var int
     */
    protected $beUser;

    /**
     * @var Tx_Logging_Domain_Model_User
     */
    protected $feUser;

    /**
     * @var Tx_Logging_Domain_Model_Page
     */
    protected $siteId;

    /**
     * @var string
     */
    protected $action;

    /**
     * @var string
     */
    protected $tablename;

    /**
     * @var int
     */
    protected $recordId;

    /**
     * @var string
     */
    protected $note;

    /**
     * @var string
     */
    protected $httpReferer;

    /**
     * @var string
     */
    protected $params;

    /**
     * @var string
     */
    protected $ipAddress;

    /**
     * @return string
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * @return int $crdate
     */
    public function getCrdate()
    {
        return $this->crdate;
    }

    /**
     * @return string $day
     */
    public function getDayCompacted()
    {
        return date("d-m-Y", $this->getCrdate());
    }

    /**
     * @return int $sessionId
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * @return int $beUser
     */
    public function getBeUser()
    {
        return $this->beUser;
    }

    /**
     * @return Tx_Logging_Domain_Model_User $feUser
     */
    public function getFeUser()
    {
        if ($this->feUser instanceof Tx_Logging_Domain_Model_User) {
            return $this->feUser;
        } else {
            return false;
        }
    }

    /**
     * @return Tx_Logging_Domain_Model_Page $siteId
     */
    public function getSiteId()
    {
        return $this->siteId;
    }

    /**
     * @return string $action
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return string $tablename
     */
    public function getTablename()
    {
        return $this->tablename;
    }

    /**
     * @return string $recordId
     */
    public function getRecordId()
    {
        return $this->recordId;
    }

    /**
     * @return string $note
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @return string $httpReferer
     */
    public function getHttpReferer()
    {
        return $this->httpReferer;
    }

    /**
     * @return string
     */
    public function getHttpRefererCropped()
    {
        $parts = explode('?', $this->httpReferer);
        return $parts[0];
    }

    /**
     * @return string $params
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @return string $params
     */
    public function getParamsArray()
    {
        return unserialize($this->params);
    }

    /**
     * @return string $params
     */
    public function getParamsHtml()
    {
        return \TYPO3\CMS\Core\Utility\DebugUtility::viewArray($this->getParamsArray());
        //return print_r($this->getParamsArray(), true);
    }

    /**
     * @return bool
     */
    public function getIsView()
    {
        return ($this->getAction() == 'view');
    }

    /**
     * @return bool
     */
    public function getIsDownload()
    {
        return ($this->getAction() == 'download');
    }

    /**
     * @return bool
     */
    public function getIsExport()
    {
        return ($this->getAction() == 'export');
    }

    /**
     * @return bool
     */
    public function getIsModify()
    {
        return ($this->getAction() == 'modify');
    }

    /**
     * @return bool
     */
    public function getIsSearch()
    {
        return ($this->getAction() == 'search');
    }

    public function getDocumentTitle()
    {
        if ($this->getTablename() == 'dam') {

            $dam_record = tx_dam_db::getDataWhere(
                'file_name',
                array('uid=' . $this->getRecordId())
            );
            return $dam_record[0]['file_name'];
        }
    }
}
