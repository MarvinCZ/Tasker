<?php

namespace Models;

use Models\Base\Comment as BaseComment;
use Propel\Runtime\Connection\ConnectionInterface;

/**
 * Skeleton subclass for representing a row from the 'comment' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Comment extends BaseComment
{
	public function preSave(ConnectionInterface $con = null)
	{
		return $this->validate();
	}
}
