<?php

namespace Models;

use Models\Base\UserQuery as BaseUserQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'user' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class UserQuery extends BaseUserQuery
{
	/**
	 * Filter the query on the password column
	 * @param  string password value to use as filter.
	 * @param  string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 * @return $this|UserQuery The current query, for fluid interface
	 */
	public function filterByPassword($password = null, $comparsion = null){
		return parent::filterByPassword(sha1($password), $comparsion);
	}
}
