<?php

namespace Models;

use Propel\Runtime\ActiveQuery\Criteria;
use Models\Base\NotificationQuery as BaseNotificationQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'notification' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class NotificationQuery extends BaseNotificationQuery
{
	/**
	 * Returns a new NotificationQuery object joined with origin.
     * @param  string $modelAlias The alias of a model in the query
     * @param  Criteria $criteria Optional Criteria to build the query from
     * @return ChildNotificationQuery
     */
	public static function create($modelAlias = null, Criteria $criteria = null){
		return parent::create($modelAlias, $criteria)->leftJoin('Notification.Note')->leftJoin('Notification.User')->with('Note')->with('User');
	}
}
