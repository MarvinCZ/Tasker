<?php

namespace Models;

use Models\Base\CategoryQuery as BaseCategoryQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'category' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class CategoryQuery extends BaseCategoryQuery
{

	/**
	 * Select only that notes, which can user access with givel level of access
	 * @param  Models\User user
	 * @param  integer level of access (0 - read, 1 - 0 + write, 2 - 1 + manage, 3 - owner)
	 * @return Models\NoteQuery this query
	 */
	public function filterCategoriesForUser($user, $rights = 0){
		if($rights > 0)
			return $this->useUserCategoryQuery()->filterByUser($user)->filterByRights(array('min' => $rights))->endUse();
		return $this->useUserCategoryQuery()->filterByUser($user)->endUse();
	}
}
