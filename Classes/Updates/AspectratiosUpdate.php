<?php

namespace ThomasK\Tkmedia\Updates;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * TexticonContentElement
 */
class AspectratiosUpdate extends \TYPO3\CMS\Install\Updates\AbstractUpdate
{
    /**
     * tx_tkmedia_aspectratio
     * @var string
     */
    protected $title = 'Tkmedia Migrate ratios from older tables to imageratio';

    /**
     * Checks if an update is needed
     *
     * @param string &$description The description for the update
     * @return bool Whether an update is needed (TRUE) or not (FALSE)
     */
    public function checkForUpdate(&$description)
    {
        $oldField = 'tx_tkmedia_aspectratio';
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionByName('Default');
        $statement = $connection->query('SHOW FULL COLUMNS FROM tt_content');
        $fields = [];
        while ($fieldRow = $statement->fetch()) {
            $fields[$fieldRow['Field']] = $fieldRow;
        }

        if ($this->isWizardDone() || !array_key_exists($oldField, $fields)) {
            return false;
        } else {
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content');
            $queryBuilder->getRestrictions()->removeAll()->add(GeneralUtility::makeInstance(DeletedRestriction::class));
            $elementCount = $queryBuilder->count('uid')
                ->from('tt_content')
                ->where(
                    $queryBuilder->expr()->isNotNull('tx_tkmedia_aspectratio')
                )
                ->execute()->fetchColumn(0);

            return (bool)$elementCount;
        }

    }

    /**
     * Performs the database update
     *
     * @param array &$databaseQueries Queries done in this update
     * @param string &$customMessage Custom message
     * @return bool
     */
    public function performUpdate(array &$databaseQueries, &$customMessage)
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tt_content');
        $queryBuilder = $connection->createQueryBuilder();
        $queryBuilder->getRestrictions()->removeAll()->add(GeneralUtility::makeInstance(DeletedRestriction::class));
        $statement = $queryBuilder->select('uid', 'tx_tkmedia_aspectratio', 'imageratio')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->neq('tx_tkmedia_aspectratio', "''")
            )
            ->execute();
        while ($record = $statement->fetch()) {
            $oldRatio = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(':', $record['tx_tkmedia_aspectratio']);
            $newRatio = 0;
            if (count($oldRatio) == 2) {
                $newRatio = round($oldRatio[0] / $oldRatio[1], 8);
            }
            $queryBuilder = $connection->createQueryBuilder();
            $queryBuilder->update('tt_content')
                ->where(
                    $queryBuilder->expr()->eq(
                        'uid',
                        $queryBuilder->createNamedParameter($record['uid'], \PDO::PARAM_INT)
                    )
                )
                ->set('imageratio', $newRatio);
            $databaseQueries[] = $queryBuilder->getSQL();
            $queryBuilder->execute();
        }
        $this->markWizardAsDone();
        return true;
    }
}
