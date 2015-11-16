<?php

namespace Models\Map;

use Models\Notification;
use Models\NotificationQuery;
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
 * This class defines the structure of the 'notification' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class NotificationTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Models.Map.NotificationTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'notification';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Models\\Notification';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Models.Notification';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 8;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 8;

    /**
     * the column name for the id field
     */
    const COL_ID = 'notification.id';

    /**
     * the column name for the user_id field
     */
    const COL_USER_ID = 'notification.user_id';

    /**
     * the column name for the origin_id field
     */
    const COL_ORIGIN_ID = 'notification.origin_id';

    /**
     * the column name for the origin_type field
     */
    const COL_ORIGIN_TYPE = 'notification.origin_type';

    /**
     * the column name for the type field
     */
    const COL_TYPE = 'notification.type';

    /**
     * the column name for the text field
     */
    const COL_TEXT = 'notification.text';

    /**
     * the column name for the created_at field
     */
    const COL_CREATED_AT = 'notification.created_at';

    /**
     * the column name for the updated_at field
     */
    const COL_UPDATED_AT = 'notification.updated_at';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /** The enumerated values for the type field */
    const COL_TYPE_INFO = 'info';
    const COL_TYPE_WARNING = 'warning';
    const COL_TYPE_REQUEST = 'request';
    const COL_TYPE_SUCCESS = 'success';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'UserId', 'OriginId', 'OriginType', 'Type', 'Text', 'CreatedAt', 'UpdatedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'userId', 'originId', 'originType', 'type', 'text', 'createdAt', 'updatedAt', ),
        self::TYPE_COLNAME       => array(NotificationTableMap::COL_ID, NotificationTableMap::COL_USER_ID, NotificationTableMap::COL_ORIGIN_ID, NotificationTableMap::COL_ORIGIN_TYPE, NotificationTableMap::COL_TYPE, NotificationTableMap::COL_TEXT, NotificationTableMap::COL_CREATED_AT, NotificationTableMap::COL_UPDATED_AT, ),
        self::TYPE_FIELDNAME     => array('id', 'user_id', 'origin_id', 'origin_type', 'type', 'text', 'created_at', 'updated_at', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'UserId' => 1, 'OriginId' => 2, 'OriginType' => 3, 'Type' => 4, 'Text' => 5, 'CreatedAt' => 6, 'UpdatedAt' => 7, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'userId' => 1, 'originId' => 2, 'originType' => 3, 'type' => 4, 'text' => 5, 'createdAt' => 6, 'updatedAt' => 7, ),
        self::TYPE_COLNAME       => array(NotificationTableMap::COL_ID => 0, NotificationTableMap::COL_USER_ID => 1, NotificationTableMap::COL_ORIGIN_ID => 2, NotificationTableMap::COL_ORIGIN_TYPE => 3, NotificationTableMap::COL_TYPE => 4, NotificationTableMap::COL_TEXT => 5, NotificationTableMap::COL_CREATED_AT => 6, NotificationTableMap::COL_UPDATED_AT => 7, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'user_id' => 1, 'origin_id' => 2, 'origin_type' => 3, 'type' => 4, 'text' => 5, 'created_at' => 6, 'updated_at' => 7, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, )
    );

    /** The enumerated values for this table */
    protected static $enumValueSets = array(
                NotificationTableMap::COL_TYPE => array(
                            self::COL_TYPE_INFO,
            self::COL_TYPE_WARNING,
            self::COL_TYPE_REQUEST,
            self::COL_TYPE_SUCCESS,
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
        $this->setName('notification');
        $this->setPhpName('Notification');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Models\\Notification');
        $this->setPackage('Models');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('user_id', 'UserId', 'INTEGER', 'user', 'id', true, null, null);
        $this->addForeignKey('origin_id', 'OriginId', 'INTEGER', 'user', 'id', false, null, null);
        $this->addForeignKey('origin_id', 'OriginId', 'INTEGER', 'note', 'id', false, null, null);
        $this->addForeignKey('origin_type', 'OriginType', 'VARCHAR', 'user', '', false, 55, null);
        $this->addForeignKey('origin_type', 'OriginType', 'VARCHAR', 'note', '', false, 55, null);
        $this->addColumn('type', 'Type', 'ENUM', true, null, 'info');
        $this->getColumn('type')->setValueSet(array (
  0 => 'info',
  1 => 'warning',
  2 => 'request',
  3 => 'success',
));
        $this->addColumn('text', 'Text', 'VARCHAR', false, 50, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('User', '\\Models\\User', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('OriginUser', '\\Models\\User', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':origin_type',
    1 => 'user',
  ),
  1 =>
  array (
    0 => ':origin_id',
    1 => ':id',
  ),
), null, null, null, true);
        $this->addRelation('Note', '\\Models\\Note', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':origin_type',
    1 => 'note',
  ),
  1 =>
  array (
    0 => ':origin_id',
    1 => ':id',
  ),
), null, null, null, true);
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
        return $withPrefix ? NotificationTableMap::CLASS_DEFAULT : NotificationTableMap::OM_CLASS;
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
     * @return array           (Notification object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = NotificationTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = NotificationTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + NotificationTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = NotificationTableMap::OM_CLASS;
            /** @var Notification $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            NotificationTableMap::addInstanceToPool($obj, $key);
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
            $key = NotificationTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = NotificationTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Notification $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                NotificationTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(NotificationTableMap::COL_ID);
            $criteria->addSelectColumn(NotificationTableMap::COL_USER_ID);
            $criteria->addSelectColumn(NotificationTableMap::COL_ORIGIN_ID);
            $criteria->addSelectColumn(NotificationTableMap::COL_ORIGIN_TYPE);
            $criteria->addSelectColumn(NotificationTableMap::COL_TYPE);
            $criteria->addSelectColumn(NotificationTableMap::COL_TEXT);
            $criteria->addSelectColumn(NotificationTableMap::COL_CREATED_AT);
            $criteria->addSelectColumn(NotificationTableMap::COL_UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.user_id');
            $criteria->addSelectColumn($alias . '.origin_id');
            $criteria->addSelectColumn($alias . '.origin_type');
            $criteria->addSelectColumn($alias . '.type');
            $criteria->addSelectColumn($alias . '.text');
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
        return Propel::getServiceContainer()->getDatabaseMap(NotificationTableMap::DATABASE_NAME)->getTable(NotificationTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(NotificationTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(NotificationTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new NotificationTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Notification or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Notification object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(NotificationTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Models\Notification) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(NotificationTableMap::DATABASE_NAME);
            $criteria->add(NotificationTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = NotificationQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            NotificationTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                NotificationTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the notification table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return NotificationQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Notification or Criteria object.
     *
     * @param mixed               $criteria Criteria or Notification object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(NotificationTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Notification object
        }

        if ($criteria->containsKey(NotificationTableMap::COL_ID) && $criteria->keyContainsValue(NotificationTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.NotificationTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = NotificationQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // NotificationTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
NotificationTableMap::buildTableMap();
