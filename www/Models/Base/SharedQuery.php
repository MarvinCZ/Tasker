<?php

namespace Models\Base;

use \Exception;
use \PDO;
use Models\Shared as ChildShared;
use Models\SharedQuery as ChildSharedQuery;
use Models\Map\SharedTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'shared' table.
 *
 *
 *
 * @method     ChildSharedQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildSharedQuery orderByWhatId($order = Criteria::ASC) Order by the what_id column
 * @method     ChildSharedQuery orderByWhatType($order = Criteria::ASC) Order by the what_type column
 * @method     ChildSharedQuery orderByToId($order = Criteria::ASC) Order by the to_id column
 * @method     ChildSharedQuery orderByToType($order = Criteria::ASC) Order by the to_type column
 * @method     ChildSharedQuery orderByRights($order = Criteria::ASC) Order by the rights column
 * @method     ChildSharedQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildSharedQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildSharedQuery groupById() Group by the id column
 * @method     ChildSharedQuery groupByWhatId() Group by the what_id column
 * @method     ChildSharedQuery groupByWhatType() Group by the what_type column
 * @method     ChildSharedQuery groupByToId() Group by the to_id column
 * @method     ChildSharedQuery groupByToType() Group by the to_type column
 * @method     ChildSharedQuery groupByRights() Group by the rights column
 * @method     ChildSharedQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildSharedQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildSharedQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildSharedQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildSharedQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildSharedQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildSharedQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildSharedQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildSharedQuery leftJoinNote($relationAlias = null) Adds a LEFT JOIN clause to the query using the Note relation
 * @method     ChildSharedQuery rightJoinNote($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Note relation
 * @method     ChildSharedQuery innerJoinNote($relationAlias = null) Adds a INNER JOIN clause to the query using the Note relation
 *
 * @method     ChildSharedQuery joinWithNote($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Note relation
 *
 * @method     ChildSharedQuery leftJoinWithNote() Adds a LEFT JOIN clause and with to the query using the Note relation
 * @method     ChildSharedQuery rightJoinWithNote() Adds a RIGHT JOIN clause and with to the query using the Note relation
 * @method     ChildSharedQuery innerJoinWithNote() Adds a INNER JOIN clause and with to the query using the Note relation
 *
 * @method     ChildSharedQuery leftJoinCategory($relationAlias = null) Adds a LEFT JOIN clause to the query using the Category relation
 * @method     ChildSharedQuery rightJoinCategory($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Category relation
 * @method     ChildSharedQuery innerJoinCategory($relationAlias = null) Adds a INNER JOIN clause to the query using the Category relation
 *
 * @method     ChildSharedQuery joinWithCategory($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Category relation
 *
 * @method     ChildSharedQuery leftJoinWithCategory() Adds a LEFT JOIN clause and with to the query using the Category relation
 * @method     ChildSharedQuery rightJoinWithCategory() Adds a RIGHT JOIN clause and with to the query using the Category relation
 * @method     ChildSharedQuery innerJoinWithCategory() Adds a INNER JOIN clause and with to the query using the Category relation
 *
 * @method     ChildSharedQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ChildSharedQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ChildSharedQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     ChildSharedQuery joinWithUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the User relation
 *
 * @method     ChildSharedQuery leftJoinWithUser() Adds a LEFT JOIN clause and with to the query using the User relation
 * @method     ChildSharedQuery rightJoinWithUser() Adds a RIGHT JOIN clause and with to the query using the User relation
 * @method     ChildSharedQuery innerJoinWithUser() Adds a INNER JOIN clause and with to the query using the User relation
 *
 * @method     ChildSharedQuery leftJoinGroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the Group relation
 * @method     ChildSharedQuery rightJoinGroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Group relation
 * @method     ChildSharedQuery innerJoinGroup($relationAlias = null) Adds a INNER JOIN clause to the query using the Group relation
 *
 * @method     ChildSharedQuery joinWithGroup($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Group relation
 *
 * @method     ChildSharedQuery leftJoinWithGroup() Adds a LEFT JOIN clause and with to the query using the Group relation
 * @method     ChildSharedQuery rightJoinWithGroup() Adds a RIGHT JOIN clause and with to the query using the Group relation
 * @method     ChildSharedQuery innerJoinWithGroup() Adds a INNER JOIN clause and with to the query using the Group relation
 *
 * @method     \Models\NoteQuery|\Models\CategoryQuery|\Models\UserQuery|\Models\GroupQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildShared findOne(ConnectionInterface $con = null) Return the first ChildShared matching the query
 * @method     ChildShared findOneOrCreate(ConnectionInterface $con = null) Return the first ChildShared matching the query, or a new ChildShared object populated from the query conditions when no match is found
 *
 * @method     ChildShared findOneById(int $id) Return the first ChildShared filtered by the id column
 * @method     ChildShared findOneByWhatId(int $what_id) Return the first ChildShared filtered by the what_id column
 * @method     ChildShared findOneByWhatType(string $what_type) Return the first ChildShared filtered by the what_type column
 * @method     ChildShared findOneByToId(int $to_id) Return the first ChildShared filtered by the to_id column
 * @method     ChildShared findOneByToType(string $to_type) Return the first ChildShared filtered by the to_type column
 * @method     ChildShared findOneByRights(int $rights) Return the first ChildShared filtered by the rights column
 * @method     ChildShared findOneByCreatedAt(string $created_at) Return the first ChildShared filtered by the created_at column
 * @method     ChildShared findOneByUpdatedAt(string $updated_at) Return the first ChildShared filtered by the updated_at column *

 * @method     ChildShared requirePk($key, ConnectionInterface $con = null) Return the ChildShared by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildShared requireOne(ConnectionInterface $con = null) Return the first ChildShared matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildShared requireOneById(int $id) Return the first ChildShared filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildShared requireOneByWhatId(int $what_id) Return the first ChildShared filtered by the what_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildShared requireOneByWhatType(string $what_type) Return the first ChildShared filtered by the what_type column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildShared requireOneByToId(int $to_id) Return the first ChildShared filtered by the to_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildShared requireOneByToType(string $to_type) Return the first ChildShared filtered by the to_type column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildShared requireOneByRights(int $rights) Return the first ChildShared filtered by the rights column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildShared requireOneByCreatedAt(string $created_at) Return the first ChildShared filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildShared requireOneByUpdatedAt(string $updated_at) Return the first ChildShared filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildShared[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildShared objects based on current ModelCriteria
 * @method     ChildShared[]|ObjectCollection findById(int $id) Return ChildShared objects filtered by the id column
 * @method     ChildShared[]|ObjectCollection findByWhatId(int $what_id) Return ChildShared objects filtered by the what_id column
 * @method     ChildShared[]|ObjectCollection findByWhatType(string $what_type) Return ChildShared objects filtered by the what_type column
 * @method     ChildShared[]|ObjectCollection findByToId(int $to_id) Return ChildShared objects filtered by the to_id column
 * @method     ChildShared[]|ObjectCollection findByToType(string $to_type) Return ChildShared objects filtered by the to_type column
 * @method     ChildShared[]|ObjectCollection findByRights(int $rights) Return ChildShared objects filtered by the rights column
 * @method     ChildShared[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildShared objects filtered by the created_at column
 * @method     ChildShared[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildShared objects filtered by the updated_at column
 * @method     ChildShared[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class SharedQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Models\Base\SharedQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Models\\Shared', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildSharedQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildSharedQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildSharedQuery) {
            return $criteria;
        }
        $query = new ChildSharedQuery();
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
     * @return ChildShared|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = SharedTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(SharedTableMap::DATABASE_NAME);
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
     * @return ChildShared A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, what_id, what_type, to_id, to_type, rights, created_at, updated_at FROM shared WHERE id = :p0';
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
            /** @var ChildShared $obj */
            $obj = new ChildShared();
            $obj->hydrate($row);
            SharedTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildShared|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildSharedQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(SharedTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildSharedQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(SharedTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildSharedQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(SharedTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(SharedTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SharedTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the what_id column
     *
     * Example usage:
     * <code>
     * $query->filterByWhatId(1234); // WHERE what_id = 1234
     * $query->filterByWhatId(array(12, 34)); // WHERE what_id IN (12, 34)
     * $query->filterByWhatId(array('min' => 12)); // WHERE what_id > 12
     * </code>
     *
     * @see       filterByNote()
     *
     * @see       filterByCategory()
     *
     * @param     mixed $whatId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSharedQuery The current query, for fluid interface
     */
    public function filterByWhatId($whatId = null, $comparison = null)
    {
        if (is_array($whatId)) {
            $useMinMax = false;
            if (isset($whatId['min'])) {
                $this->addUsingAlias(SharedTableMap::COL_WHAT_ID, $whatId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($whatId['max'])) {
                $this->addUsingAlias(SharedTableMap::COL_WHAT_ID, $whatId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SharedTableMap::COL_WHAT_ID, $whatId, $comparison);
    }

    /**
     * Filter the query on the what_type column
     *
     * Example usage:
     * <code>
     * $query->filterByWhatType('fooValue');   // WHERE what_type = 'fooValue'
     * $query->filterByWhatType('%fooValue%'); // WHERE what_type LIKE '%fooValue%'
     * </code>
     *
     * @param     string $whatType The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSharedQuery The current query, for fluid interface
     */
    public function filterByWhatType($whatType = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($whatType)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $whatType)) {
                $whatType = str_replace('*', '%', $whatType);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SharedTableMap::COL_WHAT_TYPE, $whatType, $comparison);
    }

    /**
     * Filter the query on the to_id column
     *
     * Example usage:
     * <code>
     * $query->filterByToId(1234); // WHERE to_id = 1234
     * $query->filterByToId(array(12, 34)); // WHERE to_id IN (12, 34)
     * $query->filterByToId(array('min' => 12)); // WHERE to_id > 12
     * </code>
     *
     * @see       filterByUser()
     *
     * @see       filterByGroup()
     *
     * @param     mixed $toId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSharedQuery The current query, for fluid interface
     */
    public function filterByToId($toId = null, $comparison = null)
    {
        if (is_array($toId)) {
            $useMinMax = false;
            if (isset($toId['min'])) {
                $this->addUsingAlias(SharedTableMap::COL_TO_ID, $toId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($toId['max'])) {
                $this->addUsingAlias(SharedTableMap::COL_TO_ID, $toId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SharedTableMap::COL_TO_ID, $toId, $comparison);
    }

    /**
     * Filter the query on the to_type column
     *
     * Example usage:
     * <code>
     * $query->filterByToType('fooValue');   // WHERE to_type = 'fooValue'
     * $query->filterByToType('%fooValue%'); // WHERE to_type LIKE '%fooValue%'
     * </code>
     *
     * @param     string $toType The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSharedQuery The current query, for fluid interface
     */
    public function filterByToType($toType = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($toType)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $toType)) {
                $toType = str_replace('*', '%', $toType);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SharedTableMap::COL_TO_TYPE, $toType, $comparison);
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
     * @return $this|ChildSharedQuery The current query, for fluid interface
     */
    public function filterByRights($rights = null, $comparison = null)
    {
        if (is_array($rights)) {
            $useMinMax = false;
            if (isset($rights['min'])) {
                $this->addUsingAlias(SharedTableMap::COL_RIGHTS, $rights['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($rights['max'])) {
                $this->addUsingAlias(SharedTableMap::COL_RIGHTS, $rights['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SharedTableMap::COL_RIGHTS, $rights, $comparison);
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
     * @return $this|ChildSharedQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(SharedTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(SharedTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SharedTableMap::COL_CREATED_AT, $createdAt, $comparison);
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
     * @return $this|ChildSharedQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(SharedTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(SharedTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SharedTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \Models\Note object
     *
     * @param \Models\Note $note The related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildSharedQuery The current query, for fluid interface
     */
    public function filterByNote($note, $comparison = null)
    {
        if ($note instanceof \Models\Note) {
            return $this
                ->addUsingAlias(SharedTableMap::COL_WHAT_TYPE, 'note', $comparison)
                ->addUsingAlias(SharedTableMap::COL_WHAT_ID, $note->getId(), $comparison);
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
     * @return $this|ChildSharedQuery The current query, for fluid interface
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
     * Filter the query by a related \Models\Category object
     *
     * @param \Models\Category $category The related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildSharedQuery The current query, for fluid interface
     */
    public function filterByCategory($category, $comparison = null)
    {
        if ($category instanceof \Models\Category) {
            return $this
                ->addUsingAlias(SharedTableMap::COL_WHAT_TYPE, 'category', $comparison)
                ->addUsingAlias(SharedTableMap::COL_WHAT_ID, $category->getId(), $comparison);
        } else {
            throw new PropelException('filterByCategory() only accepts arguments of type \Models\Category');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Category relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildSharedQuery The current query, for fluid interface
     */
    public function joinCategory($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
    public function useCategoryQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCategory($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Category', '\Models\CategoryQuery');
    }

    /**
     * Filter the query by a related \Models\User object
     *
     * @param \Models\User $user The related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildSharedQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof \Models\User) {
            return $this
                ->addUsingAlias(SharedTableMap::COL_TO_TYPE, 'user', $comparison)
                ->addUsingAlias(SharedTableMap::COL_TO_ID, $user->getId(), $comparison);
        } else {
            throw new PropelException('filterByUser() only accepts arguments of type \Models\User');
        }
    }

    /**
     * Adds a JOIN clause to the query using the User relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildSharedQuery The current query, for fluid interface
     */
    public function joinUser($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
    public function useUserQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\Models\UserQuery');
    }

    /**
     * Filter the query by a related \Models\Group object
     *
     * @param \Models\Group $group The related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildSharedQuery The current query, for fluid interface
     */
    public function filterByGroup($group, $comparison = null)
    {
        if ($group instanceof \Models\Group) {
            return $this
                ->addUsingAlias(SharedTableMap::COL_TO_TYPE, 'group', $comparison)
                ->addUsingAlias(SharedTableMap::COL_TO_ID, $group->getId(), $comparison);
        } else {
            throw new PropelException('filterByGroup() only accepts arguments of type \Models\Group');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Group relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildSharedQuery The current query, for fluid interface
     */
    public function joinGroup($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Group');

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
            $this->addJoinObject($join, 'Group');
        }

        return $this;
    }

    /**
     * Use the Group relation Group object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\GroupQuery A secondary query class using the current class as primary query
     */
    public function useGroupQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinGroup($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Group', '\Models\GroupQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildShared $shared Object to remove from the list of results
     *
     * @return $this|ChildSharedQuery The current query, for fluid interface
     */
    public function prune($shared = null)
    {
        if ($shared) {
            $this->addUsingAlias(SharedTableMap::COL_ID, $shared->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the shared table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SharedTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            SharedTableMap::clearInstancePool();
            SharedTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(SharedTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(SharedTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            SharedTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            SharedTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildSharedQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(SharedTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildSharedQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(SharedTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildSharedQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(SharedTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildSharedQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(SharedTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildSharedQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(SharedTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildSharedQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(SharedTableMap::COL_CREATED_AT);
    }

} // SharedQuery
