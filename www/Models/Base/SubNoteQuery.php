<?php

namespace Models\Base;

use \Exception;
use \PDO;
use Models\SubNote as ChildSubNote;
use Models\SubNoteQuery as ChildSubNoteQuery;
use Models\Map\SubNoteTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'sub_note' table.
 *
 *
 *
 * @method     ChildSubNoteQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildSubNoteQuery orderByNoteId($order = Criteria::ASC) Order by the note_id column
 * @method     ChildSubNoteQuery orderByText($order = Criteria::ASC) Order by the text column
 * @method     ChildSubNoteQuery orderByState($order = Criteria::ASC) Order by the state column
 * @method     ChildSubNoteQuery orderByDoneAt($order = Criteria::ASC) Order by the done_at column
 * @method     ChildSubNoteQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildSubNoteQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildSubNoteQuery groupById() Group by the id column
 * @method     ChildSubNoteQuery groupByNoteId() Group by the note_id column
 * @method     ChildSubNoteQuery groupByText() Group by the text column
 * @method     ChildSubNoteQuery groupByState() Group by the state column
 * @method     ChildSubNoteQuery groupByDoneAt() Group by the done_at column
 * @method     ChildSubNoteQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildSubNoteQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildSubNoteQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildSubNoteQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildSubNoteQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildSubNoteQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildSubNoteQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildSubNoteQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildSubNoteQuery leftJoinNote($relationAlias = null) Adds a LEFT JOIN clause to the query using the Note relation
 * @method     ChildSubNoteQuery rightJoinNote($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Note relation
 * @method     ChildSubNoteQuery innerJoinNote($relationAlias = null) Adds a INNER JOIN clause to the query using the Note relation
 *
 * @method     ChildSubNoteQuery joinWithNote($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Note relation
 *
 * @method     ChildSubNoteQuery leftJoinWithNote() Adds a LEFT JOIN clause and with to the query using the Note relation
 * @method     ChildSubNoteQuery rightJoinWithNote() Adds a RIGHT JOIN clause and with to the query using the Note relation
 * @method     ChildSubNoteQuery innerJoinWithNote() Adds a INNER JOIN clause and with to the query using the Note relation
 *
 * @method     \Models\NoteQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildSubNote findOne(ConnectionInterface $con = null) Return the first ChildSubNote matching the query
 * @method     ChildSubNote findOneOrCreate(ConnectionInterface $con = null) Return the first ChildSubNote matching the query, or a new ChildSubNote object populated from the query conditions when no match is found
 *
 * @method     ChildSubNote findOneById(int $id) Return the first ChildSubNote filtered by the id column
 * @method     ChildSubNote findOneByNoteId(int $note_id) Return the first ChildSubNote filtered by the note_id column
 * @method     ChildSubNote findOneByText(string $text) Return the first ChildSubNote filtered by the text column
 * @method     ChildSubNote findOneByState(int $state) Return the first ChildSubNote filtered by the state column
 * @method     ChildSubNote findOneByDoneAt(string $done_at) Return the first ChildSubNote filtered by the done_at column
 * @method     ChildSubNote findOneByCreatedAt(string $created_at) Return the first ChildSubNote filtered by the created_at column
 * @method     ChildSubNote findOneByUpdatedAt(string $updated_at) Return the first ChildSubNote filtered by the updated_at column *

 * @method     ChildSubNote requirePk($key, ConnectionInterface $con = null) Return the ChildSubNote by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubNote requireOne(ConnectionInterface $con = null) Return the first ChildSubNote matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSubNote requireOneById(int $id) Return the first ChildSubNote filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubNote requireOneByNoteId(int $note_id) Return the first ChildSubNote filtered by the note_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubNote requireOneByText(string $text) Return the first ChildSubNote filtered by the text column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubNote requireOneByState(int $state) Return the first ChildSubNote filtered by the state column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubNote requireOneByDoneAt(string $done_at) Return the first ChildSubNote filtered by the done_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubNote requireOneByCreatedAt(string $created_at) Return the first ChildSubNote filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubNote requireOneByUpdatedAt(string $updated_at) Return the first ChildSubNote filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSubNote[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildSubNote objects based on current ModelCriteria
 * @method     ChildSubNote[]|ObjectCollection findById(int $id) Return ChildSubNote objects filtered by the id column
 * @method     ChildSubNote[]|ObjectCollection findByNoteId(int $note_id) Return ChildSubNote objects filtered by the note_id column
 * @method     ChildSubNote[]|ObjectCollection findByText(string $text) Return ChildSubNote objects filtered by the text column
 * @method     ChildSubNote[]|ObjectCollection findByState(int $state) Return ChildSubNote objects filtered by the state column
 * @method     ChildSubNote[]|ObjectCollection findByDoneAt(string $done_at) Return ChildSubNote objects filtered by the done_at column
 * @method     ChildSubNote[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildSubNote objects filtered by the created_at column
 * @method     ChildSubNote[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildSubNote objects filtered by the updated_at column
 * @method     ChildSubNote[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class SubNoteQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Models\Base\SubNoteQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Models\\SubNote', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildSubNoteQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildSubNoteQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildSubNoteQuery) {
            return $criteria;
        }
        $query = new ChildSubNoteQuery();
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
     * @return ChildSubNote|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = SubNoteTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(SubNoteTableMap::DATABASE_NAME);
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
     * @return ChildSubNote A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, note_id, text, state, done_at, created_at, updated_at FROM sub_note WHERE id = :p0';
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
            /** @var ChildSubNote $obj */
            $obj = new ChildSubNote();
            $obj->hydrate($row);
            SubNoteTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildSubNote|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildSubNoteQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(SubNoteTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildSubNoteQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(SubNoteTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildSubNoteQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(SubNoteTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(SubNoteTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SubNoteTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the note_id column
     *
     * Example usage:
     * <code>
     * $query->filterByNoteId(1234); // WHERE note_id = 1234
     * $query->filterByNoteId(array(12, 34)); // WHERE note_id IN (12, 34)
     * $query->filterByNoteId(array('min' => 12)); // WHERE note_id > 12
     * </code>
     *
     * @see       filterByNote()
     *
     * @param     mixed $noteId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSubNoteQuery The current query, for fluid interface
     */
    public function filterByNoteId($noteId = null, $comparison = null)
    {
        if (is_array($noteId)) {
            $useMinMax = false;
            if (isset($noteId['min'])) {
                $this->addUsingAlias(SubNoteTableMap::COL_NOTE_ID, $noteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($noteId['max'])) {
                $this->addUsingAlias(SubNoteTableMap::COL_NOTE_ID, $noteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SubNoteTableMap::COL_NOTE_ID, $noteId, $comparison);
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
     * @return $this|ChildSubNoteQuery The current query, for fluid interface
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

        return $this->addUsingAlias(SubNoteTableMap::COL_TEXT, $text, $comparison);
    }

    /**
     * Filter the query on the state column
     *
     * @param     mixed $state The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSubNoteQuery The current query, for fluid interface
     */
    public function filterByState($state = null, $comparison = null)
    {
        $valueSet = SubNoteTableMap::getValueSet(SubNoteTableMap::COL_STATE);
        if (is_scalar($state)) {
            if (!in_array($state, $valueSet)) {
                throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $state));
            }
            $state = array_search($state, $valueSet);
        } elseif (is_array($state)) {
            $convertedValues = array();
            foreach ($state as $value) {
                if (!in_array($value, $valueSet)) {
                    throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $value));
                }
                $convertedValues []= array_search($value, $valueSet);
            }
            $state = $convertedValues;
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SubNoteTableMap::COL_STATE, $state, $comparison);
    }

    /**
     * Filter the query on the done_at column
     *
     * Example usage:
     * <code>
     * $query->filterByDoneAt('2011-03-14'); // WHERE done_at = '2011-03-14'
     * $query->filterByDoneAt('now'); // WHERE done_at = '2011-03-14'
     * $query->filterByDoneAt(array('max' => 'yesterday')); // WHERE done_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $doneAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSubNoteQuery The current query, for fluid interface
     */
    public function filterByDoneAt($doneAt = null, $comparison = null)
    {
        if (is_array($doneAt)) {
            $useMinMax = false;
            if (isset($doneAt['min'])) {
                $this->addUsingAlias(SubNoteTableMap::COL_DONE_AT, $doneAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($doneAt['max'])) {
                $this->addUsingAlias(SubNoteTableMap::COL_DONE_AT, $doneAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SubNoteTableMap::COL_DONE_AT, $doneAt, $comparison);
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
     * @return $this|ChildSubNoteQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(SubNoteTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(SubNoteTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SubNoteTableMap::COL_CREATED_AT, $createdAt, $comparison);
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
     * @return $this|ChildSubNoteQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(SubNoteTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(SubNoteTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SubNoteTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \Models\Note object
     *
     * @param \Models\Note|ObjectCollection $note The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildSubNoteQuery The current query, for fluid interface
     */
    public function filterByNote($note, $comparison = null)
    {
        if ($note instanceof \Models\Note) {
            return $this
                ->addUsingAlias(SubNoteTableMap::COL_NOTE_ID, $note->getId(), $comparison);
        } elseif ($note instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SubNoteTableMap::COL_NOTE_ID, $note->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildSubNoteQuery The current query, for fluid interface
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
     * Exclude object from result
     *
     * @param   ChildSubNote $subNote Object to remove from the list of results
     *
     * @return $this|ChildSubNoteQuery The current query, for fluid interface
     */
    public function prune($subNote = null)
    {
        if ($subNote) {
            $this->addUsingAlias(SubNoteTableMap::COL_ID, $subNote->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the sub_note table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SubNoteTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            SubNoteTableMap::clearInstancePool();
            SubNoteTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(SubNoteTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(SubNoteTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            SubNoteTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            SubNoteTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildSubNoteQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(SubNoteTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildSubNoteQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(SubNoteTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildSubNoteQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(SubNoteTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildSubNoteQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(SubNoteTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildSubNoteQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(SubNoteTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildSubNoteQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(SubNoteTableMap::COL_CREATED_AT);
    }

} // SubNoteQuery
