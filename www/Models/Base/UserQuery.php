<?php

namespace Models\Base;

use \Exception;
use \PDO;
use Models\User as ChildUser;
use Models\UserQuery as ChildUserQuery;
use Models\Map\UserTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'user' table.
 *
 *
 *
 * @method     ChildUserQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildUserQuery orderByNick($order = Criteria::ASC) Order by the nick column
 * @method     ChildUserQuery orderByEmail($order = Criteria::ASC) Order by the email column
 * @method     ChildUserQuery orderByRights($order = Criteria::ASC) Order by the rights column
 * @method     ChildUserQuery orderByEmailConfirmedAt($order = Criteria::ASC) Order by the email_confirmed_at column
 * @method     ChildUserQuery orderByPassword($order = Criteria::ASC) Order by the password column
 * @method     ChildUserQuery orderByPasswordResetToken($order = Criteria::ASC) Order by the password_reset_token column
 * @method     ChildUserQuery orderBySigninCount($order = Criteria::ASC) Order by the signin_count column
 * @method     ChildUserQuery orderByEmailConfirmToken($order = Criteria::ASC) Order by the email_confirm_token column
 * @method     ChildUserQuery orderByAvatarPath($order = Criteria::ASC) Order by the avatar_path column
 * @method     ChildUserQuery orderByLastSigninAt($order = Criteria::ASC) Order by the last_signin_at column
 * @method     ChildUserQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildUserQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildUserQuery groupById() Group by the id column
 * @method     ChildUserQuery groupByNick() Group by the nick column
 * @method     ChildUserQuery groupByEmail() Group by the email column
 * @method     ChildUserQuery groupByRights() Group by the rights column
 * @method     ChildUserQuery groupByEmailConfirmedAt() Group by the email_confirmed_at column
 * @method     ChildUserQuery groupByPassword() Group by the password column
 * @method     ChildUserQuery groupByPasswordResetToken() Group by the password_reset_token column
 * @method     ChildUserQuery groupBySigninCount() Group by the signin_count column
 * @method     ChildUserQuery groupByEmailConfirmToken() Group by the email_confirm_token column
 * @method     ChildUserQuery groupByAvatarPath() Group by the avatar_path column
 * @method     ChildUserQuery groupByLastSigninAt() Group by the last_signin_at column
 * @method     ChildUserQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildUserQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildUserQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildUserQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildUserQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildUserQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildUserQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildUserQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildUserQuery leftJoinNote($relationAlias = null) Adds a LEFT JOIN clause to the query using the Note relation
 * @method     ChildUserQuery rightJoinNote($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Note relation
 * @method     ChildUserQuery innerJoinNote($relationAlias = null) Adds a INNER JOIN clause to the query using the Note relation
 *
 * @method     ChildUserQuery joinWithNote($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Note relation
 *
 * @method     ChildUserQuery leftJoinWithNote() Adds a LEFT JOIN clause and with to the query using the Note relation
 * @method     ChildUserQuery rightJoinWithNote() Adds a RIGHT JOIN clause and with to the query using the Note relation
 * @method     ChildUserQuery innerJoinWithNote() Adds a INNER JOIN clause and with to the query using the Note relation
 *
 * @method     ChildUserQuery leftJoinCategory($relationAlias = null) Adds a LEFT JOIN clause to the query using the Category relation
 * @method     ChildUserQuery rightJoinCategory($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Category relation
 * @method     ChildUserQuery innerJoinCategory($relationAlias = null) Adds a INNER JOIN clause to the query using the Category relation
 *
 * @method     ChildUserQuery joinWithCategory($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Category relation
 *
 * @method     ChildUserQuery leftJoinWithCategory() Adds a LEFT JOIN clause and with to the query using the Category relation
 * @method     ChildUserQuery rightJoinWithCategory() Adds a RIGHT JOIN clause and with to the query using the Category relation
 * @method     ChildUserQuery innerJoinWithCategory() Adds a INNER JOIN clause and with to the query using the Category relation
 *
 * @method     ChildUserQuery leftJoinNotificationRelatedByUserId($relationAlias = null) Adds a LEFT JOIN clause to the query using the NotificationRelatedByUserId relation
 * @method     ChildUserQuery rightJoinNotificationRelatedByUserId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the NotificationRelatedByUserId relation
 * @method     ChildUserQuery innerJoinNotificationRelatedByUserId($relationAlias = null) Adds a INNER JOIN clause to the query using the NotificationRelatedByUserId relation
 *
 * @method     ChildUserQuery joinWithNotificationRelatedByUserId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the NotificationRelatedByUserId relation
 *
 * @method     ChildUserQuery leftJoinWithNotificationRelatedByUserId() Adds a LEFT JOIN clause and with to the query using the NotificationRelatedByUserId relation
 * @method     ChildUserQuery rightJoinWithNotificationRelatedByUserId() Adds a RIGHT JOIN clause and with to the query using the NotificationRelatedByUserId relation
 * @method     ChildUserQuery innerJoinWithNotificationRelatedByUserId() Adds a INNER JOIN clause and with to the query using the NotificationRelatedByUserId relation
 *
 * @method     ChildUserQuery leftJoinNotificationRelatedByOriginTypeOriginId($relationAlias = null) Adds a LEFT JOIN clause to the query using the NotificationRelatedByOriginTypeOriginId relation
 * @method     ChildUserQuery rightJoinNotificationRelatedByOriginTypeOriginId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the NotificationRelatedByOriginTypeOriginId relation
 * @method     ChildUserQuery innerJoinNotificationRelatedByOriginTypeOriginId($relationAlias = null) Adds a INNER JOIN clause to the query using the NotificationRelatedByOriginTypeOriginId relation
 *
 * @method     ChildUserQuery joinWithNotificationRelatedByOriginTypeOriginId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the NotificationRelatedByOriginTypeOriginId relation
 *
 * @method     ChildUserQuery leftJoinWithNotificationRelatedByOriginTypeOriginId() Adds a LEFT JOIN clause and with to the query using the NotificationRelatedByOriginTypeOriginId relation
 * @method     ChildUserQuery rightJoinWithNotificationRelatedByOriginTypeOriginId() Adds a RIGHT JOIN clause and with to the query using the NotificationRelatedByOriginTypeOriginId relation
 * @method     ChildUserQuery innerJoinWithNotificationRelatedByOriginTypeOriginId() Adds a INNER JOIN clause and with to the query using the NotificationRelatedByOriginTypeOriginId relation
 *
 * @method     ChildUserQuery leftJoinComment($relationAlias = null) Adds a LEFT JOIN clause to the query using the Comment relation
 * @method     ChildUserQuery rightJoinComment($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Comment relation
 * @method     ChildUserQuery innerJoinComment($relationAlias = null) Adds a INNER JOIN clause to the query using the Comment relation
 *
 * @method     ChildUserQuery joinWithComment($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Comment relation
 *
 * @method     ChildUserQuery leftJoinWithComment() Adds a LEFT JOIN clause and with to the query using the Comment relation
 * @method     ChildUserQuery rightJoinWithComment() Adds a RIGHT JOIN clause and with to the query using the Comment relation
 * @method     ChildUserQuery innerJoinWithComment() Adds a INNER JOIN clause and with to the query using the Comment relation
 *
 * @method     ChildUserQuery leftJoinIdentity($relationAlias = null) Adds a LEFT JOIN clause to the query using the Identity relation
 * @method     ChildUserQuery rightJoinIdentity($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Identity relation
 * @method     ChildUserQuery innerJoinIdentity($relationAlias = null) Adds a INNER JOIN clause to the query using the Identity relation
 *
 * @method     ChildUserQuery joinWithIdentity($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Identity relation
 *
 * @method     ChildUserQuery leftJoinWithIdentity() Adds a LEFT JOIN clause and with to the query using the Identity relation
 * @method     ChildUserQuery rightJoinWithIdentity() Adds a RIGHT JOIN clause and with to the query using the Identity relation
 * @method     ChildUserQuery innerJoinWithIdentity() Adds a INNER JOIN clause and with to the query using the Identity relation
 *
 * @method     ChildUserQuery leftJoinUserGroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserGroup relation
 * @method     ChildUserQuery rightJoinUserGroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserGroup relation
 * @method     ChildUserQuery innerJoinUserGroup($relationAlias = null) Adds a INNER JOIN clause to the query using the UserGroup relation
 *
 * @method     ChildUserQuery joinWithUserGroup($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the UserGroup relation
 *
 * @method     ChildUserQuery leftJoinWithUserGroup() Adds a LEFT JOIN clause and with to the query using the UserGroup relation
 * @method     ChildUserQuery rightJoinWithUserGroup() Adds a RIGHT JOIN clause and with to the query using the UserGroup relation
 * @method     ChildUserQuery innerJoinWithUserGroup() Adds a INNER JOIN clause and with to the query using the UserGroup relation
 *
 * @method     ChildUserQuery leftJoinShared($relationAlias = null) Adds a LEFT JOIN clause to the query using the Shared relation
 * @method     ChildUserQuery rightJoinShared($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Shared relation
 * @method     ChildUserQuery innerJoinShared($relationAlias = null) Adds a INNER JOIN clause to the query using the Shared relation
 *
 * @method     ChildUserQuery joinWithShared($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Shared relation
 *
 * @method     ChildUserQuery leftJoinWithShared() Adds a LEFT JOIN clause and with to the query using the Shared relation
 * @method     ChildUserQuery rightJoinWithShared() Adds a RIGHT JOIN clause and with to the query using the Shared relation
 * @method     ChildUserQuery innerJoinWithShared() Adds a INNER JOIN clause and with to the query using the Shared relation
 *
 * @method     ChildUserQuery leftJoinUserNote($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserNote relation
 * @method     ChildUserQuery rightJoinUserNote($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserNote relation
 * @method     ChildUserQuery innerJoinUserNote($relationAlias = null) Adds a INNER JOIN clause to the query using the UserNote relation
 *
 * @method     ChildUserQuery joinWithUserNote($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the UserNote relation
 *
 * @method     ChildUserQuery leftJoinWithUserNote() Adds a LEFT JOIN clause and with to the query using the UserNote relation
 * @method     ChildUserQuery rightJoinWithUserNote() Adds a RIGHT JOIN clause and with to the query using the UserNote relation
 * @method     ChildUserQuery innerJoinWithUserNote() Adds a INNER JOIN clause and with to the query using the UserNote relation
 *
 * @method     \Models\NoteQuery|\Models\CategoryQuery|\Models\NotificationQuery|\Models\CommentQuery|\Models\IdentityQuery|\Models\UserGroupQuery|\Models\SharedQuery|\Models\UserNoteQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildUser findOne(ConnectionInterface $con = null) Return the first ChildUser matching the query
 * @method     ChildUser findOneOrCreate(ConnectionInterface $con = null) Return the first ChildUser matching the query, or a new ChildUser object populated from the query conditions when no match is found
 *
 * @method     ChildUser findOneById(int $id) Return the first ChildUser filtered by the id column
 * @method     ChildUser findOneByNick(string $nick) Return the first ChildUser filtered by the nick column
 * @method     ChildUser findOneByEmail(string $email) Return the first ChildUser filtered by the email column
 * @method     ChildUser findOneByRights(int $rights) Return the first ChildUser filtered by the rights column
 * @method     ChildUser findOneByEmailConfirmedAt(string $email_confirmed_at) Return the first ChildUser filtered by the email_confirmed_at column
 * @method     ChildUser findOneByPassword(string $password) Return the first ChildUser filtered by the password column
 * @method     ChildUser findOneByPasswordResetToken(string $password_reset_token) Return the first ChildUser filtered by the password_reset_token column
 * @method     ChildUser findOneBySigninCount(int $signin_count) Return the first ChildUser filtered by the signin_count column
 * @method     ChildUser findOneByEmailConfirmToken(string $email_confirm_token) Return the first ChildUser filtered by the email_confirm_token column
 * @method     ChildUser findOneByAvatarPath(string $avatar_path) Return the first ChildUser filtered by the avatar_path column
 * @method     ChildUser findOneByLastSigninAt(string $last_signin_at) Return the first ChildUser filtered by the last_signin_at column
 * @method     ChildUser findOneByCreatedAt(string $created_at) Return the first ChildUser filtered by the created_at column
 * @method     ChildUser findOneByUpdatedAt(string $updated_at) Return the first ChildUser filtered by the updated_at column *

 * @method     ChildUser requirePk($key, ConnectionInterface $con = null) Return the ChildUser by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOne(ConnectionInterface $con = null) Return the first ChildUser matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUser requireOneById(int $id) Return the first ChildUser filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByNick(string $nick) Return the first ChildUser filtered by the nick column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByEmail(string $email) Return the first ChildUser filtered by the email column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByRights(int $rights) Return the first ChildUser filtered by the rights column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByEmailConfirmedAt(string $email_confirmed_at) Return the first ChildUser filtered by the email_confirmed_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByPassword(string $password) Return the first ChildUser filtered by the password column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByPasswordResetToken(string $password_reset_token) Return the first ChildUser filtered by the password_reset_token column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneBySigninCount(int $signin_count) Return the first ChildUser filtered by the signin_count column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByEmailConfirmToken(string $email_confirm_token) Return the first ChildUser filtered by the email_confirm_token column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByAvatarPath(string $avatar_path) Return the first ChildUser filtered by the avatar_path column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByLastSigninAt(string $last_signin_at) Return the first ChildUser filtered by the last_signin_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByCreatedAt(string $created_at) Return the first ChildUser filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByUpdatedAt(string $updated_at) Return the first ChildUser filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUser[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildUser objects based on current ModelCriteria
 * @method     ChildUser[]|ObjectCollection findById(int $id) Return ChildUser objects filtered by the id column
 * @method     ChildUser[]|ObjectCollection findByNick(string $nick) Return ChildUser objects filtered by the nick column
 * @method     ChildUser[]|ObjectCollection findByEmail(string $email) Return ChildUser objects filtered by the email column
 * @method     ChildUser[]|ObjectCollection findByRights(int $rights) Return ChildUser objects filtered by the rights column
 * @method     ChildUser[]|ObjectCollection findByEmailConfirmedAt(string $email_confirmed_at) Return ChildUser objects filtered by the email_confirmed_at column
 * @method     ChildUser[]|ObjectCollection findByPassword(string $password) Return ChildUser objects filtered by the password column
 * @method     ChildUser[]|ObjectCollection findByPasswordResetToken(string $password_reset_token) Return ChildUser objects filtered by the password_reset_token column
 * @method     ChildUser[]|ObjectCollection findBySigninCount(int $signin_count) Return ChildUser objects filtered by the signin_count column
 * @method     ChildUser[]|ObjectCollection findByEmailConfirmToken(string $email_confirm_token) Return ChildUser objects filtered by the email_confirm_token column
 * @method     ChildUser[]|ObjectCollection findByAvatarPath(string $avatar_path) Return ChildUser objects filtered by the avatar_path column
 * @method     ChildUser[]|ObjectCollection findByLastSigninAt(string $last_signin_at) Return ChildUser objects filtered by the last_signin_at column
 * @method     ChildUser[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildUser objects filtered by the created_at column
 * @method     ChildUser[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildUser objects filtered by the updated_at column
 * @method     ChildUser[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class UserQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Models\Base\UserQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Models\\User', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildUserQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildUserQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildUserQuery) {
            return $criteria;
        }
        $query = new ChildUserQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildUser|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = UserTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(UserTableMap::DATABASE_NAME);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildUser A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, nick, email, rights, email_confirmed_at, password, password_reset_token, signin_count, email_confirm_token, avatar_path, last_signin_at, created_at, updated_at FROM user WHERE id = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildUser $obj */
            $obj = new ChildUser();
            $obj->hydrate($row);
            UserTableMap::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildUser|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(UserTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(UserTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(UserTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(UserTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the nick column
     *
     * Example usage:
     * <code>
     * $query->filterByNick('fooValue');   // WHERE nick = 'fooValue'
     * $query->filterByNick('%fooValue%'); // WHERE nick LIKE '%fooValue%'
     * </code>
     *
     * @param     string $nick The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByNick($nick = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($nick)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $nick)) {
                $nick = str_replace('*', '%', $nick);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_NICK, $nick, $comparison);
    }

    /**
     * Filter the query on the email column
     *
     * Example usage:
     * <code>
     * $query->filterByEmail('fooValue');   // WHERE email = 'fooValue'
     * $query->filterByEmail('%fooValue%'); // WHERE email LIKE '%fooValue%'
     * </code>
     *
     * @param     string $email The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByEmail($email = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($email)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $email)) {
                $email = str_replace('*', '%', $email);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_EMAIL, $email, $comparison);
    }

    /**
     * Filter the query on the rights column
     *
     * Example usage:
     * <code>
     * $query->filterByRights(1234); // WHERE rights = 1234
     * $query->filterByRights(array(12, 34)); // WHERE rights IN (12, 34)
     * $query->filterByRights(array('min' => 12)); // WHERE rights > 12
     * </code>
     *
     * @param     mixed $rights The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByRights($rights = null, $comparison = null)
    {
        if (is_array($rights)) {
            $useMinMax = false;
            if (isset($rights['min'])) {
                $this->addUsingAlias(UserTableMap::COL_RIGHTS, $rights['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($rights['max'])) {
                $this->addUsingAlias(UserTableMap::COL_RIGHTS, $rights['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_RIGHTS, $rights, $comparison);
    }

    /**
     * Filter the query on the email_confirmed_at column
     *
     * Example usage:
     * <code>
     * $query->filterByEmailConfirmedAt('2011-03-14'); // WHERE email_confirmed_at = '2011-03-14'
     * $query->filterByEmailConfirmedAt('now'); // WHERE email_confirmed_at = '2011-03-14'
     * $query->filterByEmailConfirmedAt(array('max' => 'yesterday')); // WHERE email_confirmed_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $emailConfirmedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByEmailConfirmedAt($emailConfirmedAt = null, $comparison = null)
    {
        if (is_array($emailConfirmedAt)) {
            $useMinMax = false;
            if (isset($emailConfirmedAt['min'])) {
                $this->addUsingAlias(UserTableMap::COL_EMAIL_CONFIRMED_AT, $emailConfirmedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($emailConfirmedAt['max'])) {
                $this->addUsingAlias(UserTableMap::COL_EMAIL_CONFIRMED_AT, $emailConfirmedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_EMAIL_CONFIRMED_AT, $emailConfirmedAt, $comparison);
    }

    /**
     * Filter the query on the password column
     *
     * Example usage:
     * <code>
     * $query->filterByPassword('fooValue');   // WHERE password = 'fooValue'
     * $query->filterByPassword('%fooValue%'); // WHERE password LIKE '%fooValue%'
     * </code>
     *
     * @param     string $password The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPassword($password = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($password)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $password)) {
                $password = str_replace('*', '%', $password);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_PASSWORD, $password, $comparison);
    }

    /**
     * Filter the query on the password_reset_token column
     *
     * Example usage:
     * <code>
     * $query->filterByPasswordResetToken('fooValue');   // WHERE password_reset_token = 'fooValue'
     * $query->filterByPasswordResetToken('%fooValue%'); // WHERE password_reset_token LIKE '%fooValue%'
     * </code>
     *
     * @param     string $passwordResetToken The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPasswordResetToken($passwordResetToken = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($passwordResetToken)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $passwordResetToken)) {
                $passwordResetToken = str_replace('*', '%', $passwordResetToken);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_PASSWORD_RESET_TOKEN, $passwordResetToken, $comparison);
    }

    /**
     * Filter the query on the signin_count column
     *
     * Example usage:
     * <code>
     * $query->filterBySigninCount(1234); // WHERE signin_count = 1234
     * $query->filterBySigninCount(array(12, 34)); // WHERE signin_count IN (12, 34)
     * $query->filterBySigninCount(array('min' => 12)); // WHERE signin_count > 12
     * </code>
     *
     * @param     mixed $signinCount The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterBySigninCount($signinCount = null, $comparison = null)
    {
        if (is_array($signinCount)) {
            $useMinMax = false;
            if (isset($signinCount['min'])) {
                $this->addUsingAlias(UserTableMap::COL_SIGNIN_COUNT, $signinCount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($signinCount['max'])) {
                $this->addUsingAlias(UserTableMap::COL_SIGNIN_COUNT, $signinCount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_SIGNIN_COUNT, $signinCount, $comparison);
    }

    /**
     * Filter the query on the email_confirm_token column
     *
     * Example usage:
     * <code>
     * $query->filterByEmailConfirmToken('fooValue');   // WHERE email_confirm_token = 'fooValue'
     * $query->filterByEmailConfirmToken('%fooValue%'); // WHERE email_confirm_token LIKE '%fooValue%'
     * </code>
     *
     * @param     string $emailConfirmToken The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByEmailConfirmToken($emailConfirmToken = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($emailConfirmToken)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $emailConfirmToken)) {
                $emailConfirmToken = str_replace('*', '%', $emailConfirmToken);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_EMAIL_CONFIRM_TOKEN, $emailConfirmToken, $comparison);
    }

    /**
     * Filter the query on the avatar_path column
     *
     * Example usage:
     * <code>
     * $query->filterByAvatarPath('fooValue');   // WHERE avatar_path = 'fooValue'
     * $query->filterByAvatarPath('%fooValue%'); // WHERE avatar_path LIKE '%fooValue%'
     * </code>
     *
     * @param     string $avatarPath The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByAvatarPath($avatarPath = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($avatarPath)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $avatarPath)) {
                $avatarPath = str_replace('*', '%', $avatarPath);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_AVATAR_PATH, $avatarPath, $comparison);
    }

    /**
     * Filter the query on the last_signin_at column
     *
     * Example usage:
     * <code>
     * $query->filterByLastSigninAt('2011-03-14'); // WHERE last_signin_at = '2011-03-14'
     * $query->filterByLastSigninAt('now'); // WHERE last_signin_at = '2011-03-14'
     * $query->filterByLastSigninAt(array('max' => 'yesterday')); // WHERE last_signin_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $lastSigninAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByLastSigninAt($lastSigninAt = null, $comparison = null)
    {
        if (is_array($lastSigninAt)) {
            $useMinMax = false;
            if (isset($lastSigninAt['min'])) {
                $this->addUsingAlias(UserTableMap::COL_LAST_SIGNIN_AT, $lastSigninAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($lastSigninAt['max'])) {
                $this->addUsingAlias(UserTableMap::COL_LAST_SIGNIN_AT, $lastSigninAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_LAST_SIGNIN_AT, $lastSigninAt, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(UserTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(UserTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(UserTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(UserTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \Models\Note object
     *
     * @param \Models\Note|ObjectCollection $note the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByNote($note, $comparison = null)
    {
        if ($note instanceof \Models\Note) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $note->getUserId(), $comparison);
        } elseif ($note instanceof ObjectCollection) {
            return $this
                ->useNoteQuery()
                ->filterByPrimaryKeys($note->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByNote() only accepts arguments of type \Models\Note or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Note relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinNote($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Note');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Note');
        }

        return $this;
    }

    /**
     * Use the Note relation Note object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\NoteQuery A secondary query class using the current class as primary query
     */
    public function useNoteQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinNote($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Note', '\Models\NoteQuery');
    }

    /**
     * Filter the query by a related \Models\Category object
     *
     * @param \Models\Category|ObjectCollection $category the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByCategory($category, $comparison = null)
    {
        if ($category instanceof \Models\Category) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $category->getUserId(), $comparison);
        } elseif ($category instanceof ObjectCollection) {
            return $this
                ->useCategoryQuery()
                ->filterByPrimaryKeys($category->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCategory() only accepts arguments of type \Models\Category or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Category relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinCategory($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Category');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Category');
        }

        return $this;
    }

    /**
     * Use the Category relation Category object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\CategoryQuery A secondary query class using the current class as primary query
     */
    public function useCategoryQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCategory($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Category', '\Models\CategoryQuery');
    }

    /**
     * Filter the query by a related \Models\Notification object
     *
     * @param \Models\Notification|ObjectCollection $notification the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByNotificationRelatedByUserId($notification, $comparison = null)
    {
        if ($notification instanceof \Models\Notification) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $notification->getUserId(), $comparison);
        } elseif ($notification instanceof ObjectCollection) {
            return $this
                ->useNotificationRelatedByUserIdQuery()
                ->filterByPrimaryKeys($notification->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByNotificationRelatedByUserId() only accepts arguments of type \Models\Notification or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the NotificationRelatedByUserId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinNotificationRelatedByUserId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('NotificationRelatedByUserId');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'NotificationRelatedByUserId');
        }

        return $this;
    }

    /**
     * Use the NotificationRelatedByUserId relation Notification object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\NotificationQuery A secondary query class using the current class as primary query
     */
    public function useNotificationRelatedByUserIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinNotificationRelatedByUserId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'NotificationRelatedByUserId', '\Models\NotificationQuery');
    }

    /**
     * Filter the query by a related \Models\Notification object
     *
     * @param \Models\Notification|ObjectCollection $notification the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByNotificationRelatedByOriginTypeOriginId($notification, $comparison = null)
    {
        if ($notification instanceof \Models\Notification) {
            return $this
                ->where("'user' = ?", $notification->getOriginType(), 2)
                ->addUsingAlias(UserTableMap::COL_ID, $notification->getOriginId(), $comparison);
        } else {
            throw new PropelException('filterByNotificationRelatedByOriginTypeOriginId() only accepts arguments of type \Models\Notification');
        }
    }

    /**
     * Adds a JOIN clause to the query using the NotificationRelatedByOriginTypeOriginId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinNotificationRelatedByOriginTypeOriginId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('NotificationRelatedByOriginTypeOriginId');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'NotificationRelatedByOriginTypeOriginId');
        }

        return $this;
    }

    /**
     * Use the NotificationRelatedByOriginTypeOriginId relation Notification object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\NotificationQuery A secondary query class using the current class as primary query
     */
    public function useNotificationRelatedByOriginTypeOriginIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinNotificationRelatedByOriginTypeOriginId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'NotificationRelatedByOriginTypeOriginId', '\Models\NotificationQuery');
    }

    /**
     * Filter the query by a related \Models\Comment object
     *
     * @param \Models\Comment|ObjectCollection $comment the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByComment($comment, $comparison = null)
    {
        if ($comment instanceof \Models\Comment) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $comment->getUserId(), $comparison);
        } elseif ($comment instanceof ObjectCollection) {
            return $this
                ->useCommentQuery()
                ->filterByPrimaryKeys($comment->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByComment() only accepts arguments of type \Models\Comment or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Comment relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinComment($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Comment');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Comment');
        }

        return $this;
    }

    /**
     * Use the Comment relation Comment object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\CommentQuery A secondary query class using the current class as primary query
     */
    public function useCommentQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinComment($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Comment', '\Models\CommentQuery');
    }

    /**
     * Filter the query by a related \Models\Identity object
     *
     * @param \Models\Identity|ObjectCollection $identity the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByIdentity($identity, $comparison = null)
    {
        if ($identity instanceof \Models\Identity) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $identity->getUserId(), $comparison);
        } elseif ($identity instanceof ObjectCollection) {
            return $this
                ->useIdentityQuery()
                ->filterByPrimaryKeys($identity->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByIdentity() only accepts arguments of type \Models\Identity or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Identity relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinIdentity($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Identity');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Identity');
        }

        return $this;
    }

    /**
     * Use the Identity relation Identity object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\IdentityQuery A secondary query class using the current class as primary query
     */
    public function useIdentityQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinIdentity($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Identity', '\Models\IdentityQuery');
    }

    /**
     * Filter the query by a related \Models\UserGroup object
     *
     * @param \Models\UserGroup|ObjectCollection $userGroup the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByUserGroup($userGroup, $comparison = null)
    {
        if ($userGroup instanceof \Models\UserGroup) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $userGroup->getUserId(), $comparison);
        } elseif ($userGroup instanceof ObjectCollection) {
            return $this
                ->useUserGroupQuery()
                ->filterByPrimaryKeys($userGroup->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUserGroup() only accepts arguments of type \Models\UserGroup or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserGroup relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinUserGroup($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserGroup');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'UserGroup');
        }

        return $this;
    }

    /**
     * Use the UserGroup relation UserGroup object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\UserGroupQuery A secondary query class using the current class as primary query
     */
    public function useUserGroupQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserGroup($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserGroup', '\Models\UserGroupQuery');
    }

    /**
     * Filter the query by a related \Models\Shared object
     *
     * @param \Models\Shared|ObjectCollection $shared the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByShared($shared, $comparison = null)
    {
        if ($shared instanceof \Models\Shared) {
            return $this
                ->where("'user' = ?", $shared->getToType(), 2)
                ->addUsingAlias(UserTableMap::COL_ID, $shared->getToId(), $comparison);
        } else {
            throw new PropelException('filterByShared() only accepts arguments of type \Models\Shared');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Shared relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinShared($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Shared');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Shared');
        }

        return $this;
    }

    /**
     * Use the Shared relation Shared object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\SharedQuery A secondary query class using the current class as primary query
     */
    public function useSharedQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinShared($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Shared', '\Models\SharedQuery');
    }

    /**
     * Filter the query by a related \Models\UserNote object
     *
     * @param \Models\UserNote|ObjectCollection $userNote the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByUserNote($userNote, $comparison = null)
    {
        if ($userNote instanceof \Models\UserNote) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $userNote->getUserId(), $comparison);
        } elseif ($userNote instanceof ObjectCollection) {
            return $this
                ->useUserNoteQuery()
                ->filterByPrimaryKeys($userNote->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUserNote() only accepts arguments of type \Models\UserNote or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserNote relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinUserNote($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserNote');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'UserNote');
        }

        return $this;
    }

    /**
     * Use the UserNote relation UserNote object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\UserNoteQuery A secondary query class using the current class as primary query
     */
    public function useUserNoteQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserNote($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserNote', '\Models\UserNoteQuery');
    }

    /**
     * Filter the query by a related Group object
     * using the user_group table as cross reference
     *
     * @param Group $group the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByGroup($group, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useUserGroupQuery()
            ->filterByGroup($group, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   ChildUser $user Object to remove from the list of results
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function prune($user = null)
    {
        if ($user) {
            $this->addUsingAlias(UserTableMap::COL_ID, $user->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the user table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            UserTableMap::clearInstancePool();
            UserTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(UserTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            UserTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            UserTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildUserQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(UserTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildUserQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(UserTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildUserQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(UserTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildUserQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(UserTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildUserQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(UserTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildUserQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(UserTableMap::COL_CREATED_AT);
    }

} // UserQuery
