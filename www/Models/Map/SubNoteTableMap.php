<?php

namespace Models\Map;

use Models\SubNote;
use Models\SubNoteQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'sub_note' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class SubNoteTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Models.Map.SubNoteTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'sub_note';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Models\\SubNote';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Models.SubNote';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 7;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 7;

    /**
     * the column name for the id field
     */
    const COL_ID = 'sub_note.id';

    /**
     * the column name for the note_id field
     */
    const COL_NOTE_ID = 'sub_note.note_id';

    /**
     * the column name for the text field
     */
    const COL_TEXT = 'sub_note.text';

    /**
     * the column name for the state field
     */
    const COL_STATE = 'sub_note.state';

    /**
     * the column name for the done_at field
     */
    const COL_DONE_AT = 'sub_note.done_at';

    /**
     * the column name for the created_at field
     */
    const COL_CREATED_AT = 'sub_note.created_at';

    /**
     * the column name for the updated_at field
     */
    const COL_UPDATED_AT = 'sub_note.updated_at';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /** The enumerated values for the state field */
    const COL_STATE_OPENED = 'opened';
    const COL_STATE_DONE = 'done';
    const COL_STATE_WIP = 'wip';
    const COL_STATE_CLOSED = 'closed';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'NoteId', 'Text', 'State', 'DoneAt', 'CreatedAt', 'UpdatedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'noteId', 'text', 'state', 'doneAt', 'createdAt', 'updatedAt', ),
        self::TYPE_COLNAME       => array(SubNoteTableMap::COL_ID, SubNoteTableMap::COL_NOTE_ID, SubNoteTableMap::COL_TEXT, SubNoteTableMap::COL_STATE, SubNoteTableMap::COL_DONE_AT, SubNoteTableMap::COL_CREATED_AT, SubNoteTableMap::COL_UPDATED_AT, ),
        self::TYPE_FIELDNAME     => array('id', 'note_id', 'text', 'state', 'done_at', 'created_at', 'updated_at', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'NoteId' => 1, 'Text' => 2, 'State' => 3, 'DoneAt' => 4, 'CreatedAt' => 5, 'UpdatedAt' => 6, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'noteId' => 1, 'text' => 2, 'state' => 3, 'doneAt' => 4, 'createdAt' => 5, 'updatedAt' => 6, ),
        self::TYPE_COLNAME       => array(SubNoteTableMap::COL_ID => 0, SubNoteTableMap::COL_NOTE_ID => 1, SubNoteTableMap::COL_TEXT => 2, SubNoteTableMap::COL_STATE => 3, SubNoteTableMap::COL_DONE_AT => 4, SubNoteTableMap::COL_CREATED_AT => 5, SubNoteTableMap::COL_UPDATED_AT => 6, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'note_id' => 1, 'text' => 2, 'state' => 3, 'done_at' => 4, 'created_at' => 5, 'updated_at' => 6, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
    );

    /** The enumerated values for this table */
    protected static $enumValueSets = array(
                SubNoteTableMap::COL_STATE => array(
                            self::COL_STATE_OPENED,
            self::COL_STATE_DONE,
            self::COL_STATE_WIP,
            self::COL_STATE_CLOSED,
        ),
    );

    /**
     * Gets the list of values for all ENUM columns
     * @return array
     */
    public static function getValueSets()
    {
      return static::$enumValueSets;
    }

    /**
     * Gets the list of values for an ENUM column
     * @param string $colname
     * @return array list of possible values for the column
     */
    public static function getValueSet($colname)
    {
        $valueSets = self::getValueSets();

        return $valueSets[$colname];
    }

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('sub_note');
        $this->setPhpName('SubNote');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Models\\SubNote');
        $this->setPackage('Models');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('note_id', 'NoteId', 'INTEGER', 'note', 'id', true, null, null);
        $this->addColumn('text', 'Text', 'VARCHAR', false, 100, null);
        $this->addColumn('state', 'State', 'ENUM', true, null, 'opened');
        $this->getColumn('state')->setValueSet(array (
  0 => 'opened',
  1 => 'done',
  2 => 'wip',
  3 => 'closed',
));
        $this->addColumn('done_at', 'DoneAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Note', '\\Models\\Note', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':note_id',
    1 => ':id',
  ),
), null, null, null, false);
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'timestampable' => array('create_column' => 'created_at', 'update_column' => 'updated_at', 'disable_created_at' => 'false', 'disable_updated_at' => 'false', ),
        );
    } // getBehaviors()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        return (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
        ];
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? SubNoteTableMap::CLASS_DEFAULT : SubNoteTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array           (SubNote object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = SubNoteTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = SubNoteTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + SubNoteTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = SubNoteTableMap::OM_CLASS;
            /** @var SubNote $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            SubNoteTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = SubNoteTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = SubNoteTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var SubNote $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                SubNoteTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(SubNoteTableMap::COL_ID);
            $criteria->addSelectColumn(SubNoteTableMap::COL_NOTE_ID);
            $criteria->addSelectColumn(SubNoteTableMap::COL_TEXT);
            $criteria->addSelectColumn(SubNoteTableMap::COL_STATE);
            $criteria->addSelectColumn(SubNoteTableMap::COL_DONE_AT);
            $criteria->addSelectColumn(SubNoteTableMap::COL_CREATED_AT);
            $criteria->addSelectColumn(SubNoteTableMap::COL_UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.note_id');
            $criteria->addSelectColumn($alias . '.text');
            $criteria->addSelectColumn($alias . '.state');
            $criteria->addSelectColumn($alias . '.done_at');
            $criteria->addSelectColumn($alias . '.created_at');
            $criteria->addSelectColumn($alias . '.updated_at');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(SubNoteTableMap::DATABASE_NAME)->getTable(SubNoteTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(SubNoteTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(SubNoteTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new SubNoteTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a SubNote or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or SubNote object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SubNoteTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Models\SubNote) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(SubNoteTableMap::DATABASE_NAME);
            $criteria->add(SubNoteTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = SubNoteQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            SubNoteTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                SubNoteTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the sub_note table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return SubNoteQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a SubNote or Criteria object.
     *
     * @param mixed               $criteria Criteria or SubNote object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SubNoteTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from SubNote object
        }

        if ($criteria->containsKey(SubNoteTableMap::COL_ID) && $criteria->keyContainsValue(SubNoteTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.SubNoteTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = SubNoteQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // SubNoteTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
SubNoteTableMap::buildTableMap();
