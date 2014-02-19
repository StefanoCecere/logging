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
class Tx_Logging_Domain_Repository_SessionRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    /**
     * @param Tx_Logging_Domain_Model_Dto_ReportsDemand $demand
     * @return array|Tx_Logging_Domain_Model_Session
     */
    public function findDemanded(Tx_Logging_Domain_Model_Dto_ReportsDemand $demand)
    {
        $query = $this->createQuery();
        $query->setOrderings(
            array(
                'crdate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING
            )
        );

        $constraints = array();

        if ($demand->getUser()) {
            $constraints[] = $query->logicalOr(
                $query->like('feUser.username', '%' . $demand->getUser() . '%'),
                $query->like('feUser.lastName', '%' . $demand->getUser() . '%')
            );
        }

        if ($demand->getOnlylogged()) {
            $constraints[] = $query->greaterThan('feUser', 0);
        }

        if ($demand->getSite()) {
            $constraints[] = $query->equals('siteId', $demand->getSite());
        }

        if ($demand->getYear() > 0) {
            if ($demand->getMonth() > 0) {
                $tstamp_from = mktime(0, 0, 0, $demand->getMonth(), 0, $demand->getYear());
                $tstamp_to = mktime(0, 0, 0, $demand->getMonth() + 1, 0, $demand->getYear());
            } else {
                $tstamp_from = mktime(0, 0, 0, 0, 0, $demand->getYear());
                $tstamp_to = mktime(0, 0, 0, 0, 0, $demand->getYear() + 1);
            }
            $constraints[] = $query->logicalAnd(
                $query->greaterThanOrEqual('crdate', $tstamp_from),
                $query->lessThan('crdate', $tstamp_to)
            );
        }

        if (count($constraints) > 0) {
            $query->matching($query->logicalAnd($constraints));
        }

        $query->getQuerySettings()->setRespectStoragePage(false);
        $query->getQuerySettings()->setIgnoreEnableFields(true);
        $query->getQuerySettings()->setRespectSysLanguage(false);

        return $query->execute();
    }
}
