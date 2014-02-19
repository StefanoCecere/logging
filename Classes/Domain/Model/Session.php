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
class Tx_Logging_Domain_Model_Session extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * @var int
     */
    protected $crdate;

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
    protected $ipAddress;

    /**
     * @return int $crdate
     */
    public function getCrdate()
    {
        return $this->crdate;
    }

    /**
     * @return Tx_Logging_Domain_Model_User $feUser
     */
    public function getFeUser()
    {
        return $this->feUser;
    }

    /**
     * @return Tx_Logging_Domain_Model_Page $siteId
     */
    public function getSiteId()
    {
        return $this->siteId;
    }

    /**
     * @return string $ipAddress
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }
}
