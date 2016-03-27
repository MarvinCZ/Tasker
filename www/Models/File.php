<?php

namespace Models;

use Models\Base\File as BaseFile;
use Propel\Runtime\Propel;
use Propel\Runtime\Connection\ConnectionInterface;

/**
 * Skeleton subclass for representing a row from the 'file' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class File extends BaseFile
{
	public function delete(ConnectionInterface $con = null){
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(Map\NoteTableMap::DATABASE_NAME);
        }

		unlink($this->getPath());

        parent::delete($con);
	}

    public function setName($v){
        parent::setName(htmlspecialchars($v, ENT_QUOTES, 'UTF-8'));
    }

    public function setPath($v){
        parent::setPath(htmlspecialchars($v, ENT_QUOTES, 'UTF-8'));
    }
}
