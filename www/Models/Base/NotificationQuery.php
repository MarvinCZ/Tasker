<?php

namespace Models\Base;

use \Exception;
use \PDO;
use Models\Notification as ChildNotification;
use Models\NotificationQuery as ChildNotificationQuery;
use Models\Map\NotificationTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'notification' table.
 *
 *
 *
 * @method     ChildNotificationQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildNotificationQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     ChildNotificationQuery orderByOriginId($order = Criteria::ASC) Order by the origin_id column
 * @method     ChildNotificationQuery orderByOriginType($order = Criteria::ASC) Order by the origin_type column
 * @method     ChildNotificationQuery orderByType($order = Criteria::ASC) Order by the type column
 * @method     ChildNotificationQuery orderByText($order = Criteria::ASC) Order by the text column
 * @method     ChildNotificationQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildNotificationQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildNotificationQuery groupById() Group by the id column
 * @method     ChildNotificationQuery groupByUserId() Group by the user_id column
 * @method     ChildNotificationQuery groupByOriginId() Group by the origin_id column
 * @method     ChildNotificationQuery groupByOriginType() Group by the origin_type column
 * @method     ChildNotificationQuery groupByType() Group by the type column
 * @method     ChildNotificationQuery groupByText() Group by the text column
 * @method     ChildNotificationQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildNotificationQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildNotificationQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildNotificationQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildNotificationQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildNotificationQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildNotificationQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildNotificationQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildNotificationQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ChildNotificationQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ChildNotificationQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     ChildNotificationQuery joinWithUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the User relation
 *
 * @method     ChildNotificationQuery leftJoinWithUser() Adds a LEFT JOIN clause and with to the query using the User relation
 * @method     ChildNotificationQuery rightJoinWithUser() Adds a RIGHT JOIN clause and with to the query using the User relation
 * @method     ChildNotificationQuery innerJoinWithUser() Adds a INNER JOIN clause and with to the query using the User relation
 *
 * @method     ChildNotificationQuery leftJoinOriginUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the OriginUser relation
 * @method     ChildNotificationQuery rightJoinOriginUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OriginUser relation
 * @method     ChildNotificationQuery innerJoinOriginUser($relationAlias = null) Adds a INNER JOIN clause to the query using the OriginUser relation
 *
 * @method     ChildNotificationQuery joinWithOriginUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the OriginUser relation
 *
 * @method     ChildNotificationQuery leftJoinWithOriginUser() Adds a LEFT JOIN clause and with to the query using the OriginUser relation
 * @method     ChildNotificationQuery rightJoinWithOriginUser() Adds a RIGHT JOIN clause and with to the query using the OriginUser relation
 * @method     ChildNotificationQuery innerJoinWithOriginUser() Adds a INNER JOIN clause and with to the query using the OriginUser relation
 *
 * @method     ChildNotificationQuery leftJoinNote($relationAlias = null) Adds a LEFT JOIN clause to the query using the Note relation
 * @method     ChildNotificationQuery rightJoinNote($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Note relation
 * @method     ChildNotificationQuery innerJoinNote($relationAlias = null) Adds a INNER JOIN clause to the query using the Note relation
 *
 * @method     ChildNotificationQuery joinWithNote($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Note relation
 *
 * @method     ChildNotificationQuery leftJoinWithNote() Adds a LEFT JOIN clause and with to the query using the Note relation
 * @method     ChildNotificationQuery rightJoinWithNote() Adds a RIGHT JOIN clause and with to the query using the Note relation
 * @method     ChildNotificationQuery innerJoinWithNote() Adds a INNER JOIN clause and with to the query using the Note relation
 *
 * @method     \Models\UserQuery|\Models\NoteQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildNotification findOne(ConnectionInterface $con = null) Return the first ChildNotification matching the query
 * @method     ChildNotification findOneOrCreate(ConnectionInterface $con = null) Return the first ChildNotification matching the query, or a new ChildNotification object populated from the query conditions when no match is found
 *
 * @method     ChildNotification findOneById(int $id) Return the first ChildNotification filtered by the id column
 * @method     ChildNotification findOneByUserId(int $user_id) Return the first ChildNotification filtered by the user_id column
 * @method     ChildNotification findOneByOriginId(int $origin_id) Return the first ChildNotification filtered by the origin_id column
 * @method     ChildNotification findOneByOriginType(string $origin_type) Return the first ChildNotification filtered by the origin_type column
 * @method     ChildNotification findOneByType(int $type) Return the first ChildNotification filtered by the type column
 * @method     ChildNotification findOneByText(string $text) Return the first ChildNotification filtered by the text column
 * @method     ChildNotification findOneByCreatedAt(string $created_at) Return the first ChildNotification filtered by the created_at column
 * @method     ChildNotification findOneByUpdatedAt(string $updated_at) Return the first ChildNotification filtered by the updated_at column *

 * @method     ChildNotification requirePk($key, ConnectionInterface $con = null) Return the ChildNotification by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildNotification requireOne(ConnectionInterface $con = null) Return the first ChildNotification matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildNotification requireOneById(int $id) Return the first ChildNotification filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildNotification requireOneByUserId(int $user_id) Return the first ChildNotification filtered by the user_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildNotification requireOneByOriginId(int $origin_id) Return the first ChildNotification filtered by the origin_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildNotification requireOneByOriginType(string $origin_type) Return the first ChildNotification filtered by the origin_type column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildNotification requireOneByType(int $type) Return the first ChildNotification filtered by the type column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildNotification requireOneByText(string $text) Return the first ChildNotification filtered by the text column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildNotification requireOneByCreatedAt(string $created_at) Return the first ChildNotification filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildNotification requireOneByUpdatedAt(string $updated_at) Return the first ChildNotification filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildNotification[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildNotification objects based on current ModelCriteria
 * @method     ChildNotification[]|ObjectCollection findById(int $id) Return ChildNotification objects filtered by the id column
 * @method     ChildNotification[]|ObjectCollection findByUserId(int $user_id) Return ChildNotification objects filtered by the user_id column
 * @method     ChildNotification[]|ObjectCollection findByOriginId(int $origin_id) Return ChildNotification objects filtered by the origin_id column
 * @method     ChildNotification[]|ObjectCollection findByOriginType(string $origin_type) Return ChildNotification objects filtered by the origin_type column
 * @method     ChildNotification[]|ObjectCollection findByType(int $type) Return ChildNotification objects filtered by the type column
 * @method     ChildNotification[]|ObjectCollection findByText(string $text) Return ChildNotification objects filtered by the text column
 * @method     ChildNotification[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildNotification objects filtered by the created_at column
 * @method     ChildNotification[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildNotification objects filtered by the updated_at column
 * @method     ChildNotification[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class NotificationQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Models\Base\NotificationQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Models\\Notification', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildNotificationQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildNotificationQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildNotificationQuery) {
            return $criteria;
        }
        $query = new ChildNotificationQuery();
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
     * @return ChildNotification|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = NotificationTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(NotificationTableMap::DATABASE_NAME);
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
     * @return ChildNotification A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, user_id, origin_id, origin_type, type, text, created_at, updated_at FROM notification WHERE id = :p0';
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
            /** @var ChildNotification $obj */
            $obj = new ChildNotification();
            $obj->hydrate($row);
            NotificationTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildNotification|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildNotificationQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(NotificationTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildNotificationQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(NotificationTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildNotificationQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(NotificationTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(NotificationTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(NotificationTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the user_id column
     *
     * Example usage:
     * <code>
     * $query->filterByUserId(1234); // WHERE user_id = 1234
     * $query->filterByUserId(array(12, 34)); // WHERE user_id IN (12, 34)
     * $query->filterByUserId(array('min' => 12)); // WHERE user_id > 12
     * </code>
     *
     * @see       filterByUser()
     *
     * @param     mixed $userId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildNotificationQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(NotificationTableMap::COL_USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(NotificationTableMap::COL_USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(NotificationTableMap::COL_USER_ID, $userId, $comparison);
    }

    /**
     * Filter the query on the origin_id column
     *
     * Example usage:
     * <code>
     * $query->filterByOriginId(1234); // WHERE origin_id = 1234
     * $query->filterByOriginId(array(12, 34)); // WHERE origin_id IN (12, 34)
     * $query->filterByOriginId(array('min' => 12)); // WHERE origin_id > 12
     * </code>
     *
     * @see       filterByOriginUser()
     *
     * @see       filterByNote()
     *
     * @param     mixed $originId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildNotificationQuery The current query, for fluid interface
     */
    public function filterByOriginId($originId = null, $comparison = null)
    {
        if (is_array($originId)) {
            $useMinMax = false;
            if (isset($originId['min'])) {
                $this->addUsingAlias(NotificationTableMap::COL_ORIGIN_ID, $originId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($originId['max'])) {
                $this->addUsingAlias(NotificationTableMap::COL_ORIGIN_ID, $originId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(NotificationTableMap::COL_ORIGIN_ID, $originId, $comparison);
    }

    /**
     * Filter the query on the origin_type column
     *
     * Example usage:
     * <code>
     * $query->filterByOriginType('fooValue');   // WHERE origin_type = 'fooValue'
     * $query->filterByOriginType('%fooValue%'); // WHERE origin_type LIKE '%fooValue%'
     * </code>
     *
     * @param     string $originType The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildNotificationQuery The current query, for fluid interface
     */
    public function filterByOriginType($originType = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($originType)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $originType)) {
                $originType = str_replace('*', '%', $originType);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(NotificationTableMap::COL_ORIGIN_TYPE, $originType, $comparison);
    }

    /**
     * Filter the query on the type column
     *
     * @param     mixed $type The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildNotificationQuery The current query, for fluid interface
     */
    public function filterByType($type = null, $comparison = null)
    {
        $valueSet = NotificationTableMap::getValueSet(NotificationTableMap::COL_TYPE);
        if (is_scalar($type)) {
            if (!in_array($type, $valueSet)) {
                throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $type));
            }
            $type = array_search($type, $valueSet);
        } elseif (is_array($type)) {
            $convertedValues = array();
            foreach ($type as $value) {
                if (!in_array($value, $valueSet)) {
                    throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $value));
                }
                $convertedValues []= array_search($value, $valueSet);
            }
            $type = $convertedValues;
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(NotificationTableMap::COL_TYPE, $type, $comparison);
    }

    /**
     * Filter the query on the text column
     *
     * Example usage:
     * <code>
     * $query->filterByText('fooValue');   // WHERE text = 'fooValue'
     * $query->filterByText('%fooValue%'); // WHERE text LIKE '%fooValue%'
     * </code>
     *
     * @param     string $text The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildNotificationQuery The current query, for fluid interface
     */
    public function filterByText($text = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($text)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $text)) {
                $text = str_replace('*', '%', $text);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(NotificationTableMap::COL_TEXT, $text, $comparison);
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
     * @return $this|ChildNotificationQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(NotificationTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(NotificationTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(NotificationTableMap::COL_CREATED_AT, $createdAt, $comparison);
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
     * @return $this|ChildNotificationQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(NotificationTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(NotificationTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(NotificationTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \Models\User object
     *
     * @param \Models\User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildNotificationQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof \Models\User) {
            return $this
                ->addUsingAlias(NotificationTableMap::COL_USER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(NotificationTableMap::COL_USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByUser() only accepts arguments of type \Models\User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the User relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildNotificationQuery The current query, for fluid interface
     */
    public function joinUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('User');

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
            $this->addJoinObject($join, 'User');
        }

        return $this;
    }

    /**
     * Use the User relation User object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\Models\UserQuery');
    }

    /**
     * Filter the query by a related \Models\User object
     *
     * @param \Models\User $user The related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildNotificationQuery The current query, for fluid interface
     */
    public function filterByOriginUser($user, $comparison = null)
    {
        if ($user instanceof \Models\User) {
            return $this
                ->addUsingAlias(NotificationTableMap::COL_ORIGIN_TYPE, 'user', $comparison)
                ->addUsingAlias(NotificationTableMap::COL_ORIGIN_ID, $user->getId(), $comparison);
        } else {
            throw new PropelException('filterByOriginUser() only accepts arguments of type \Models\User');
        }
    }

    /**
     * Adds a JOIN clause to the query using the OriginUser relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildNotificationQuery The current query, for fluid interface
     */
    public function joinOriginUser($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('OriginUser');

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
            $this->addJoinObject($join, 'OriginUser');
        }

        return $this;
    }

    /**
     * Use the OriginUser relation User object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\UserQuery A secondary query class using the current class as primary query
     */
    public function useOriginUserQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinOriginUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'OriginUser', '\Models\UserQuery');
    }

    /**
     * Filter the query by a related \Models\Note object
     *
     * @param \Models\Note $note The related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildNotificationQuery The current query, for fluid interface
     */
    public function filterByNote($note, $comparison = null)
    {
        if ($note instanceof \Models\Note) {
            return $this
                ->addUsingAlias(NotificationTableMap::COL_ORIGIN_TYPE, 'note', $comparison)
                ->addUsingAlias(NotificationTableMap::COL_ORIGIN_ID, $note->getId(), $comparison);
        } else {
            throw new PropelException('filterByNote() only accepts arguments of type \Models\Note');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Note relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildNotificationQuery The current query, for fluid interface
     */
    public function joinNote($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
    public function useNoteQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinNote($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Note', '\Models\NoteQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildNotification $notification Object to remove from the list of results
     *
     * @return $this|ChildNotificationQuery The current query, for fluid interface
     */
    public function prune($notification = null)
    {
        if ($notification) {
            $this->addUsingAlias(NotificationTableMap::COL_ID, $notification->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the notification table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(NotificationTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            NotificationTableMap::clearInstancePool();
            NotificationTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(NotificationTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(NotificationTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            NotificationTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            NotificationTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildNotificationQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(NotificationTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildNotificationQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(NotificationTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildNotificationQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(NotificationTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildNotificationQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(NotificationTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildNotificationQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(NotificationTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildNotificationQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(NotificationTableMap::COL_CREATED_AT);
    }

} // NotificationQuery
