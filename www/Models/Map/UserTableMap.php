<?php

namespace Models\Map;

use Models\User;
use Models\UserQuery;
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
 * This class defines the structure of the 'user' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class UserTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Models.Map.UserTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'user';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Models\\User';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Models.User';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 13;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 13;

    /**
     * the column name for the id field
     */
    const COL_ID = 'user.id';

    /**
     * the column name for the nick field
     */
    const COL_NICK = 'user.nick';

    /**
     * the column name for the email field
     */
    const COL_EMAIL = 'user.email';

    /**
     * the column name for the rights field
     */
    const COL_RIGHTS = 'user.rights';

    /**
     * the column name for the email_confirmed_at field
     */
    const COL_EMAIL_CONFIRMED_AT = 'user.email_confirmed_at';

    /**
     * the column name for the password field
     */
    const COL_PASSWORD = 'user.password';

    /**
     * the column name for the password_reset_token field
     */
    const COL_PASSWORD_RESET_TOKEN = 'user.password_reset_token';

    /**
     * the column name for the signin_count field
     */
    const COL_SIGNIN_COUNT = 'user.signin_count';

    /**
     * the column name for the email_confirm_token field
     */
    const COL_EMAIL_CONFIRM_TOKEN = 'user.email_confirm_token';

    /**
     * the column name for the avatar_path field
     */
    const COL_AVATAR_PATH = 'user.avatar_path';

    /**
     * the column name for the last_signin_at field
     */
    const COL_LAST_SIGNIN_AT = 'user.last_signin_at';

    /**
     * the column name for the created_at field
     */
    const COL_CREATED_AT = 'user.created_at';

    /**
     * the column name for the updated_at field
     */
    const COL_UPDATED_AT = 'user.updated_at';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'Nick', 'Email', 'Rights', 'EmailConfirmedAt', 'Password', 'PasswordResetToken', 'SigninCount', 'EmailConfirmToken', 'AvatarPath', 'LastSigninAt', 'CreatedAt', 'UpdatedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'nick', 'email', 'rights', 'emailConfirmedAt', 'password', 'passwordResetToken', 'signinCount', 'emailConfirmToken', 'avatarPath', 'lastSigninAt', 'createdAt', 'updatedAt', ),
        self::TYPE_COLNAME       => array(UserTableMap::COL_ID, UserTableMap::COL_NICK, UserTableMap::COL_EMAIL, UserTableMap::COL_RIGHTS, UserTableMap::COL_EMAIL_CONFIRMED_AT, UserTableMap::COL_PASSWORD, UserTableMap::COL_PASSWORD_RESET_TOKEN, UserTableMap::COL_SIGNIN_COUNT, UserTableMap::COL_EMAIL_CONFIRM_TOKEN, UserTableMap::COL_AVATAR_PATH, UserTableMap::COL_LAST_SIGNIN_AT, UserTableMap::COL_CREATED_AT, UserTableMap::COL_UPDATED_AT, ),
        self::TYPE_FIELDNAME     => array('id', 'nick', 'email', 'rights', 'email_confirmed_at', 'password', 'password_reset_token', 'signin_count', 'email_confirm_token', 'avatar_path', 'last_signin_at', 'created_at', 'updated_at', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Nick' => 1, 'Email' => 2, 'Rights' => 3, 'EmailConfirmedAt' => 4, 'Password' => 5, 'PasswordResetToken' => 6, 'SigninCount' => 7, 'EmailConfirmToken' => 8, 'AvatarPath' => 9, 'LastSigninAt' => 10, 'CreatedAt' => 11, 'UpdatedAt' => 12, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'nick' => 1, 'email' => 2, 'rights' => 3, 'emailConfirmedAt' => 4, 'password' => 5, 'passwordResetToken' => 6, 'signinCount' => 7, 'emailConfirmToken' => 8, 'avatarPath' => 9, 'lastSigninAt' => 10, 'createdAt' => 11, 'updatedAt' => 12, ),
        self::TYPE_COLNAME       => array(UserTableMap::COL_ID => 0, UserTableMap::COL_NICK => 1, UserTableMap::COL_EMAIL => 2, UserTableMap::COL_RIGHTS => 3, UserTableMap::COL_EMAIL_CONFIRMED_AT => 4, UserTableMap::COL_PASSWORD => 5, UserTableMap::COL_PASSWORD_RESET_TOKEN => 6, UserTableMap::COL_SIGNIN_COUNT => 7, UserTableMap::COL_EMAIL_CONFIRM_TOKEN => 8, UserTableMap::COL_AVATAR_PATH => 9, UserTableMap::COL_LAST_SIGNIN_AT => 10, UserTableMap::COL_CREATED_AT => 11, UserTableMap::COL_UPDATED_AT => 12, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'nick' => 1, 'email' => 2, 'rights' => 3, 'email_confirmed_at' => 4, 'password' => 5, 'password_reset_token' => 6, 'signin_count' => 7, 'email_confirm_token' => 8, 'avatar_path' => 9, 'last_signin_at' => 10, 'created_at' => 11, 'updated_at' => 12, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, )
    );

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
        $this->setName('user');
        $this->setPhpName('User');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Models\\User');
        $this->setPackage('Models');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('nick', 'Nick', 'VARCHAR', true, 50, null);
        $this->addColumn('email', 'Email', 'VARCHAR', true, 100, null);
        $this->addColumn('rights', 'Rights', 'INTEGER', false, null, null);
        $this->addColumn('email_confirmed_at', 'EmailConfirmedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('password', 'Password', 'VARCHAR', true, 50, null);
        $this->addColumn('password_reset_token', 'PasswordResetToken', 'VARCHAR', false, 50, null);
        $this->addColumn('signin_count', 'SigninCount', 'INTEGER', false, null, null);
        $this->addColumn('email_confirm_token', 'EmailConfirmToken', 'VARCHAR', false, 50, null);
        $this->addColumn('avatar_path', 'AvatarPath', 'VARCHAR', false, 255, null);
        $this->addColumn('last_signin_at', 'LastSigninAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Note', '\\Models\\Note', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, 'Notes', false);
        $this->addRelation('Category', '\\Models\\Category', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, 'Categories', false);
        $this->addRelation('NotificationRelatedByUserId', '\\Models\\Notification', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, 'NotificationsRelatedByUserId', false);
        $this->addRelation('NotificationRelatedByOriginTypeOriginId', '\\Models\\Notification', RelationMap::ONE_TO_MANY, array (
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
), null, null, 'NotificationsRelatedByOriginTypeOriginId', true);
        $this->addRelation('Comment', '\\Models\\Comment', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, 'Comments', false);
        $this->addRelation('Identity', '\\Models\\Identity', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, 'Identities', false);
        $this->addRelation('UserGroup', '\\Models\\UserGroup', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, 'UserGroups', false);
        $this->addRelation('Shared', '\\Models\\Shared', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':to_type',
    1 => 'user',
  ),
  1 =>
  array (
    0 => ':to_id',
    1 => ':id',
  ),
), null, null, 'Shareds', true);
        $this->addRelation('Group', '\\Models\\Group', RelationMap::MANY_TO_MANY, array(), null, null, 'Groups');
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
        return $withPrefix ? UserTableMap::CLASS_DEFAULT : UserTableMap::OM_CLASS;
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
     * @return array           (User object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = UserTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = UserTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + UserTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = UserTableMap::OM_CLASS;
            /** @var User $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            UserTableMap::addInstanceToPool($obj, $key);
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
            $key = UserTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = UserTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var User $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                UserTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(UserTableMap::COL_ID);
            $criteria->addSelectColumn(UserTableMap::COL_NICK);
            $criteria->addSelectColumn(UserTableMap::COL_EMAIL);
            $criteria->addSelectColumn(UserTableMap::COL_RIGHTS);
            $criteria->addSelectColumn(UserTableMap::COL_EMAIL_CONFIRMED_AT);
            $criteria->addSelectColumn(UserTableMap::COL_PASSWORD);
            $criteria->addSelectColumn(UserTableMap::COL_PASSWORD_RESET_TOKEN);
            $criteria->addSelectColumn(UserTableMap::COL_SIGNIN_COUNT);
            $criteria->addSelectColumn(UserTableMap::COL_EMAIL_CONFIRM_TOKEN);
            $criteria->addSelectColumn(UserTableMap::COL_AVATAR_PATH);
            $criteria->addSelectColumn(UserTableMap::COL_LAST_SIGNIN_AT);
            $criteria->addSelectColumn(UserTableMap::COL_CREATED_AT);
            $criteria->addSelectColumn(UserTableMap::COL_UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.nick');
            $criteria->addSelectColumn($alias . '.email');
            $criteria->addSelectColumn($alias . '.rights');
            $criteria->addSelectColumn($alias . '.email_confirmed_at');
            $criteria->addSelectColumn($alias . '.password');
            $criteria->addSelectColumn($alias . '.password_reset_token');
            $criteria->addSelectColumn($alias . '.signin_count');
            $criteria->addSelectColumn($alias . '.email_confirm_token');
            $criteria->addSelectColumn($alias . '.avatar_path');
            $criteria->addSelectColumn($alias . '.last_signin_at');
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
        return Propel::getServiceContainer()->getDatabaseMap(UserTableMap::DATABASE_NAME)->getTable(UserTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(UserTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(UserTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new UserTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a User or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or User object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Models\User) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(UserTableMap::DATABASE_NAME);
            $criteria->add(UserTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = UserQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            UserTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                UserTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the user table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return UserQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a User or Criteria object.
     *
     * @param mixed               $criteria Criteria or User object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from User object
        }

        if ($criteria->containsKey(UserTableMap::COL_ID) && $criteria->keyContainsValue(UserTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.UserTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = UserQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // UserTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
UserTableMap::buildTableMap();
