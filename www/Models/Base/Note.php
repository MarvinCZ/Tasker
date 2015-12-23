<?php

namespace Models\Base;

use \DateTime;
use \Exception;
use \PDO;
use Models\Category as ChildCategory;
use Models\CategoryQuery as ChildCategoryQuery;
use Models\Comment as ChildComment;
use Models\CommentQuery as ChildCommentQuery;
use Models\File as ChildFile;
use Models\FileQuery as ChildFileQuery;
use Models\Note as ChildNote;
use Models\NoteQuery as ChildNoteQuery;
use Models\Notification as ChildNotification;
use Models\NotificationQuery as ChildNotificationQuery;
use Models\Shared as ChildShared;
use Models\SharedQuery as ChildSharedQuery;
use Models\SubNote as ChildSubNote;
use Models\SubNoteQuery as ChildSubNoteQuery;
use Models\User as ChildUser;
use Models\UserNote as ChildUserNote;
use Models\UserNoteQuery as ChildUserNoteQuery;
use Models\UserQuery as ChildUserQuery;
use Models\Map\NoteTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use Propel\Runtime\Util\PropelDateTime;

/**
 * Base class that represents a row from the 'note' table.
 *
 *
 *
* @package    propel.generator.Models.Base
*/
abstract class Note implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Models\\Map\\NoteTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the id field.
     *
     * @var        int
     */
    protected $id;

    /**
     * The value for the user_id field.
     *
     * @var        int
     */
    protected $user_id;

    /**
     * The value for the importance field.
     *
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $importance;

    /**
     * The value for the title field.
     *
     * @var        string
     */
    protected $title;

    /**
     * The value for the deadline field.
     *
     * @var        \DateTime
     */
    protected $deadline;

    /**
     * The value for the category_id field.
     *
     * @var        int
     */
    protected $category_id;

    /**
     * The value for the state field.
     *
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $state;

    /**
     * The value for the repeat_after field.
     *
     * @var        int
     */
    protected $repeat_after;

    /**
     * The value for the done_at field.
     *
     * @var        \DateTime
     */
    protected $done_at;

    /**
     * The value for the public field.
     *
     * @var        boolean
     */
    protected $public;

    /**
     * The value for the description field.
     *
     * @var        string
     */
    protected $description;

    /**
     * The value for the created_at field.
     *
     * @var        \DateTime
     */
    protected $created_at;

    /**
     * The value for the updated_at field.
     *
     * @var        \DateTime
     */
    protected $updated_at;

    /**
     * @var        ChildUser
     */
    protected $aUser;

    /**
     * @var        ChildCategory
     */
    protected $aCategory;

    /**
     * @var        ObjectCollection|ChildSubNote[] Collection to store aggregation of ChildSubNote objects.
     */
    protected $collSubNotes;
    protected $collSubNotesPartial;

    /**
     * @var        ObjectCollection|ChildFile[] Collection to store aggregation of ChildFile objects.
     */
    protected $collFiles;
    protected $collFilesPartial;

    /**
     * @var        ObjectCollection|ChildNotification[] Collection to store aggregation of ChildNotification objects.
     */
    protected $collNotifications;
    protected $collNotificationsPartial;

    /**
     * @var        ObjectCollection|ChildComment[] Collection to store aggregation of ChildComment objects.
     */
    protected $collComments;
    protected $collCommentsPartial;

    /**
     * @var        ObjectCollection|ChildShared[] Collection to store aggregation of ChildShared objects.
     */
    protected $collShareds;
    protected $collSharedsPartial;

    /**
     * @var        ObjectCollection|ChildUserNote[] Collection to store aggregation of ChildUserNote objects.
     */
    protected $collUserNotes;
    protected $collUserNotesPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildSubNote[]
     */
    protected $subNotesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildFile[]
     */
    protected $filesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildNotification[]
     */
    protected $notificationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildComment[]
     */
    protected $commentsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildShared[]
     */
    protected $sharedsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildUserNote[]
     */
    protected $userNotesScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->importance = 0;
        $this->state = 0;
    }

    /**
     * Initializes internal state of Models\Base\Note object.
     * @see applyDefaults()
     */
    public function __construct()
    {
        $this->applyDefaultValues();
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>Note</code> instance.  If
     * <code>obj</code> is an instance of <code>Note</code>, delegates to
     * <code>equals(Note)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        if (!$obj instanceof static) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey() || null === $obj->getPrimaryKey()) {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return $this|Note The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        $cls = new \ReflectionClass($this);
        $propertyNames = [];
        foreach($cls->getProperties() as $property) {
            $propertyNames[] = $property->getName();
        }
        return $propertyNames;
    }

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the [user_id] column value.
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Get the [importance] column value.
     *
     * @return int
     */
    public function getImportance()
    {
        return $this->importance;
    }

    /**
     * Get the [title] column value.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get the [optionally formatted] temporal [deadline] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getDeadline($format = NULL)
    {
        if ($format === null) {
            return $this->deadline;
        } else {
            return $this->deadline instanceof \DateTime ? $this->deadline->format($format) : null;
        }
    }

    /**
     * Get the [category_id] column value.
     *
     * @return int
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * Get the [state] column value.
     *
     * @return string
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getState()
    {
        if (null === $this->state) {
            return null;
        }
        $valueSet = NoteTableMap::getValueSet(NoteTableMap::COL_STATE);
        if (!isset($valueSet[$this->state])) {
            throw new PropelException('Unknown stored enum key: ' . $this->state);
        }

        return $valueSet[$this->state];
    }

    /**
     * Get the [repeat_after] column value.
     *
     * @return int
     */
    public function getRepeatAfter()
    {
        return $this->repeat_after;
    }

    /**
     * Get the [optionally formatted] temporal [done_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getDoneAt($format = NULL)
    {
        if ($format === null) {
            return $this->done_at;
        } else {
            return $this->done_at instanceof \DateTime ? $this->done_at->format($format) : null;
        }
    }

    /**
     * Get the [public] column value.
     *
     * @return boolean
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * Get the [public] column value.
     *
     * @return boolean
     */
    public function isPublic()
    {
        return $this->getPublic();
    }

    /**
     * Get the [description] column value.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get the [optionally formatted] temporal [created_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreatedAt($format = NULL)
    {
        if ($format === null) {
            return $this->created_at;
        } else {
            return $this->created_at instanceof \DateTime ? $this->created_at->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [updated_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getUpdatedAt($format = NULL)
    {
        if ($format === null) {
            return $this->updated_at;
        } else {
            return $this->updated_at instanceof \DateTime ? $this->updated_at->format($format) : null;
        }
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\Models\Note The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[NoteTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [user_id] column.
     *
     * @param int $v new value
     * @return $this|\Models\Note The current object (for fluent API support)
     */
    public function setUserId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->user_id !== $v) {
            $this->user_id = $v;
            $this->modifiedColumns[NoteTableMap::COL_USER_ID] = true;
        }

        if ($this->aUser !== null && $this->aUser->getId() !== $v) {
            $this->aUser = null;
        }

        return $this;
    } // setUserId()

    /**
     * Set the value of [importance] column.
     *
     * @param int $v new value
     * @return $this|\Models\Note The current object (for fluent API support)
     */
    public function setImportance($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->importance !== $v) {
            $this->importance = $v;
            $this->modifiedColumns[NoteTableMap::COL_IMPORTANCE] = true;
        }

        return $this;
    } // setImportance()

    /**
     * Set the value of [title] column.
     *
     * @param string $v new value
     * @return $this|\Models\Note The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->title !== $v) {
            $this->title = $v;
            $this->modifiedColumns[NoteTableMap::COL_TITLE] = true;
        }

        return $this;
    } // setTitle()

    /**
     * Sets the value of [deadline] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\Models\Note The current object (for fluent API support)
     */
    public function setDeadline($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->deadline !== null || $dt !== null) {
            if ($this->deadline === null || $dt === null || $dt->format("Y-m-d H:i:s") !== $this->deadline->format("Y-m-d H:i:s")) {
                $this->deadline = $dt === null ? null : clone $dt;
                $this->modifiedColumns[NoteTableMap::COL_DEADLINE] = true;
            }
        } // if either are not null

        return $this;
    } // setDeadline()

    /**
     * Set the value of [category_id] column.
     *
     * @param int $v new value
     * @return $this|\Models\Note The current object (for fluent API support)
     */
    public function setCategoryId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->category_id !== $v) {
            $this->category_id = $v;
            $this->modifiedColumns[NoteTableMap::COL_CATEGORY_ID] = true;
        }

        if ($this->aCategory !== null && $this->aCategory->getId() !== $v) {
            $this->aCategory = null;
        }

        return $this;
    } // setCategoryId()

    /**
     * Set the value of [state] column.
     *
     * @param  string $v new value
     * @return $this|\Models\Note The current object (for fluent API support)
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function setState($v)
    {
        if ($v !== null) {
            $valueSet = NoteTableMap::getValueSet(NoteTableMap::COL_STATE);
            if (!in_array($v, $valueSet)) {
                throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $v));
            }
            $v = array_search($v, $valueSet);
        }

        if ($this->state !== $v) {
            $this->state = $v;
            $this->modifiedColumns[NoteTableMap::COL_STATE] = true;
        }

        return $this;
    } // setState()

    /**
     * Set the value of [repeat_after] column.
     *
     * @param int $v new value
     * @return $this|\Models\Note The current object (for fluent API support)
     */
    public function setRepeatAfter($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->repeat_after !== $v) {
            $this->repeat_after = $v;
            $this->modifiedColumns[NoteTableMap::COL_REPEAT_AFTER] = true;
        }

        return $this;
    } // setRepeatAfter()

    /**
     * Sets the value of [done_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\Models\Note The current object (for fluent API support)
     */
    public function setDoneAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->done_at !== null || $dt !== null) {
            if ($this->done_at === null || $dt === null || $dt->format("Y-m-d H:i:s") !== $this->done_at->format("Y-m-d H:i:s")) {
                $this->done_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[NoteTableMap::COL_DONE_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setDoneAt()

    /**
     * Sets the value of the [public] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\Models\Note The current object (for fluent API support)
     */
    public function setPublic($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->public !== $v) {
            $this->public = $v;
            $this->modifiedColumns[NoteTableMap::COL_PUBLIC] = true;
        }

        return $this;
    } // setPublic()

    /**
     * Set the value of [description] column.
     *
     * @param string $v new value
     * @return $this|\Models\Note The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[NoteTableMap::COL_DESCRIPTION] = true;
        }

        return $this;
    } // setDescription()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\Models\Note The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            if ($this->created_at === null || $dt === null || $dt->format("Y-m-d H:i:s") !== $this->created_at->format("Y-m-d H:i:s")) {
                $this->created_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[NoteTableMap::COL_CREATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\Models\Note The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            if ($this->updated_at === null || $dt === null || $dt->format("Y-m-d H:i:s") !== $this->updated_at->format("Y-m-d H:i:s")) {
                $this->updated_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[NoteTableMap::COL_UPDATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setUpdatedAt()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
            if ($this->importance !== 0) {
                return false;
            }

            if ($this->state !== 0) {
                return false;
            }

        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : NoteTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : NoteTableMap::translateFieldName('UserId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->user_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : NoteTableMap::translateFieldName('Importance', TableMap::TYPE_PHPNAME, $indexType)];
            $this->importance = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : NoteTableMap::translateFieldName('Title', TableMap::TYPE_PHPNAME, $indexType)];
            $this->title = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : NoteTableMap::translateFieldName('Deadline', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->deadline = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : NoteTableMap::translateFieldName('CategoryId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->category_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : NoteTableMap::translateFieldName('State', TableMap::TYPE_PHPNAME, $indexType)];
            $this->state = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : NoteTableMap::translateFieldName('RepeatAfter', TableMap::TYPE_PHPNAME, $indexType)];
            $this->repeat_after = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : NoteTableMap::translateFieldName('DoneAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->done_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : NoteTableMap::translateFieldName('Public', TableMap::TYPE_PHPNAME, $indexType)];
            $this->public = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : NoteTableMap::translateFieldName('Description', TableMap::TYPE_PHPNAME, $indexType)];
            $this->description = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : NoteTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : NoteTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 13; // 13 = NoteTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Models\\Note'), 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
        if ($this->aUser !== null && $this->user_id !== $this->aUser->getId()) {
            $this->aUser = null;
        }
        if ($this->aCategory !== null && $this->category_id !== $this->aCategory->getId()) {
            $this->aCategory = null;
        }
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(NoteTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildNoteQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aUser = null;
            $this->aCategory = null;
            $this->collSubNotes = null;

            $this->collFiles = null;

            $this->collNotifications = null;

            $this->collComments = null;

            $this->collShareds = null;

            $this->collUserNotes = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Note::setDeleted()
     * @see Note::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(NoteTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildNoteQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $this->setDeleted(true);
            }
        });
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(NoteTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $isInsert = $this->isNew();
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior

                if (!$this->isColumnModified(NoteTableMap::COL_CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(NoteTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(NoteTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                NoteTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }

            return $affectedRows;
        });
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aUser !== null) {
                if ($this->aUser->isModified() || $this->aUser->isNew()) {
                    $affectedRows += $this->aUser->save($con);
                }
                $this->setUser($this->aUser);
            }

            if ($this->aCategory !== null) {
                if ($this->aCategory->isModified() || $this->aCategory->isNew()) {
                    $affectedRows += $this->aCategory->save($con);
                }
                $this->setCategory($this->aCategory);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                    $affectedRows += 1;
                } else {
                    $affectedRows += $this->doUpdate($con);
                }
                $this->resetModified();
            }

            if ($this->subNotesScheduledForDeletion !== null) {
                if (!$this->subNotesScheduledForDeletion->isEmpty()) {
                    \Models\SubNoteQuery::create()
                        ->filterByPrimaryKeys($this->subNotesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->subNotesScheduledForDeletion = null;
                }
            }

            if ($this->collSubNotes !== null) {
                foreach ($this->collSubNotes as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->filesScheduledForDeletion !== null) {
                if (!$this->filesScheduledForDeletion->isEmpty()) {
                    \Models\FileQuery::create()
                        ->filterByPrimaryKeys($this->filesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->filesScheduledForDeletion = null;
                }
            }

            if ($this->collFiles !== null) {
                foreach ($this->collFiles as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->notificationsScheduledForDeletion !== null) {
                if (!$this->notificationsScheduledForDeletion->isEmpty()) {
                    foreach ($this->notificationsScheduledForDeletion as $notification) {
                        // need to save related object because we set the relation to null
                        $notification->save($con);
                    }
                    $this->notificationsScheduledForDeletion = null;
                }
            }

            if ($this->collNotifications !== null) {
                foreach ($this->collNotifications as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->commentsScheduledForDeletion !== null) {
                if (!$this->commentsScheduledForDeletion->isEmpty()) {
                    \Models\CommentQuery::create()
                        ->filterByPrimaryKeys($this->commentsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->commentsScheduledForDeletion = null;
                }
            }

            if ($this->collComments !== null) {
                foreach ($this->collComments as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->sharedsScheduledForDeletion !== null) {
                if (!$this->sharedsScheduledForDeletion->isEmpty()) {
                    foreach ($this->sharedsScheduledForDeletion as $shared) {
                        // need to save related object because we set the relation to null
                        $shared->save($con);
                    }
                    $this->sharedsScheduledForDeletion = null;
                }
            }

            if ($this->collShareds !== null) {
                foreach ($this->collShareds as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->userNotesScheduledForDeletion !== null) {
                if (!$this->userNotesScheduledForDeletion->isEmpty()) {
                    \Models\UserNoteQuery::create()
                        ->filterByPrimaryKeys($this->userNotesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->userNotesScheduledForDeletion = null;
                }
            }

            if ($this->collUserNotes !== null) {
                foreach ($this->collUserNotes as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[NoteTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . NoteTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(NoteTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(NoteTableMap::COL_USER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'user_id';
        }
        if ($this->isColumnModified(NoteTableMap::COL_IMPORTANCE)) {
            $modifiedColumns[':p' . $index++]  = 'importance';
        }
        if ($this->isColumnModified(NoteTableMap::COL_TITLE)) {
            $modifiedColumns[':p' . $index++]  = 'title';
        }
        if ($this->isColumnModified(NoteTableMap::COL_DEADLINE)) {
            $modifiedColumns[':p' . $index++]  = 'deadline';
        }
        if ($this->isColumnModified(NoteTableMap::COL_CATEGORY_ID)) {
            $modifiedColumns[':p' . $index++]  = 'category_id';
        }
        if ($this->isColumnModified(NoteTableMap::COL_STATE)) {
            $modifiedColumns[':p' . $index++]  = 'state';
        }
        if ($this->isColumnModified(NoteTableMap::COL_REPEAT_AFTER)) {
            $modifiedColumns[':p' . $index++]  = 'repeat_after';
        }
        if ($this->isColumnModified(NoteTableMap::COL_DONE_AT)) {
            $modifiedColumns[':p' . $index++]  = 'done_at';
        }
        if ($this->isColumnModified(NoteTableMap::COL_PUBLIC)) {
            $modifiedColumns[':p' . $index++]  = 'public';
        }
        if ($this->isColumnModified(NoteTableMap::COL_DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = 'description';
        }
        if ($this->isColumnModified(NoteTableMap::COL_CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'created_at';
        }
        if ($this->isColumnModified(NoteTableMap::COL_UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'updated_at';
        }

        $sql = sprintf(
            'INSERT INTO note (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'id':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case 'user_id':
                        $stmt->bindValue($identifier, $this->user_id, PDO::PARAM_INT);
                        break;
                    case 'importance':
                        $stmt->bindValue($identifier, $this->importance, PDO::PARAM_INT);
                        break;
                    case 'title':
                        $stmt->bindValue($identifier, $this->title, PDO::PARAM_STR);
                        break;
                    case 'deadline':
                        $stmt->bindValue($identifier, $this->deadline ? $this->deadline->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'category_id':
                        $stmt->bindValue($identifier, $this->category_id, PDO::PARAM_INT);
                        break;
                    case 'state':
                        $stmt->bindValue($identifier, $this->state, PDO::PARAM_INT);
                        break;
                    case 'repeat_after':
                        $stmt->bindValue($identifier, $this->repeat_after, PDO::PARAM_INT);
                        break;
                    case 'done_at':
                        $stmt->bindValue($identifier, $this->done_at ? $this->done_at->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'public':
                        $stmt->bindValue($identifier, (int) $this->public, PDO::PARAM_INT);
                        break;
                    case 'description':
                        $stmt->bindValue($identifier, $this->description, PDO::PARAM_STR);
                        break;
                    case 'created_at':
                        $stmt->bindValue($identifier, $this->created_at ? $this->created_at->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'updated_at':
                        $stmt->bindValue($identifier, $this->updated_at ? $this->updated_at->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = NoteTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getUserId();
                break;
            case 2:
                return $this->getImportance();
                break;
            case 3:
                return $this->getTitle();
                break;
            case 4:
                return $this->getDeadline();
                break;
            case 5:
                return $this->getCategoryId();
                break;
            case 6:
                return $this->getState();
                break;
            case 7:
                return $this->getRepeatAfter();
                break;
            case 8:
                return $this->getDoneAt();
                break;
            case 9:
                return $this->getPublic();
                break;
            case 10:
                return $this->getDescription();
                break;
            case 11:
                return $this->getCreatedAt();
                break;
            case 12:
                return $this->getUpdatedAt();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {

        if (isset($alreadyDumpedObjects['Note'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Note'][$this->hashCode()] = true;
        $keys = NoteTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getUserId(),
            $keys[2] => $this->getImportance(),
            $keys[3] => $this->getTitle(),
            $keys[4] => $this->getDeadline(),
            $keys[5] => $this->getCategoryId(),
            $keys[6] => $this->getState(),
            $keys[7] => $this->getRepeatAfter(),
            $keys[8] => $this->getDoneAt(),
            $keys[9] => $this->getPublic(),
            $keys[10] => $this->getDescription(),
            $keys[11] => $this->getCreatedAt(),
            $keys[12] => $this->getUpdatedAt(),
        );
        if ($result[$keys[4]] instanceof \DateTime) {
            $result[$keys[4]] = $result[$keys[4]]->format('c');
        }

        if ($result[$keys[8]] instanceof \DateTime) {
            $result[$keys[8]] = $result[$keys[8]]->format('c');
        }

        if ($result[$keys[11]] instanceof \DateTime) {
            $result[$keys[11]] = $result[$keys[11]]->format('c');
        }

        if ($result[$keys[12]] instanceof \DateTime) {
            $result[$keys[12]] = $result[$keys[12]]->format('c');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aUser) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'user';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'user';
                        break;
                    default:
                        $key = 'User';
                }

                $result[$key] = $this->aUser->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aCategory) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'category';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'category';
                        break;
                    default:
                        $key = 'Category';
                }

                $result[$key] = $this->aCategory->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collSubNotes) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'subNotes';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'sub_notes';
                        break;
                    default:
                        $key = 'SubNotes';
                }

                $result[$key] = $this->collSubNotes->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collFiles) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'files';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'files';
                        break;
                    default:
                        $key = 'Files';
                }

                $result[$key] = $this->collFiles->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collNotifications) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'notifications';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'notifications';
                        break;
                    default:
                        $key = 'Notifications';
                }

                $result[$key] = $this->collNotifications->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collComments) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'comments';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'comments';
                        break;
                    default:
                        $key = 'Comments';
                }

                $result[$key] = $this->collComments->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collShareds) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'shareds';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'shareds';
                        break;
                    default:
                        $key = 'Shareds';
                }

                $result[$key] = $this->collShareds->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collUserNotes) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'userNotes';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'user_notes';
                        break;
                    default:
                        $key = 'UserNotes';
                }

                $result[$key] = $this->collUserNotes->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param  string $name
     * @param  mixed  $value field value
     * @param  string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this|\Models\Note
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = NoteTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Models\Note
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setUserId($value);
                break;
            case 2:
                $this->setImportance($value);
                break;
            case 3:
                $this->setTitle($value);
                break;
            case 4:
                $this->setDeadline($value);
                break;
            case 5:
                $this->setCategoryId($value);
                break;
            case 6:
                $valueSet = NoteTableMap::getValueSet(NoteTableMap::COL_STATE);
                if (isset($valueSet[$value])) {
                    $value = $valueSet[$value];
                }
                $this->setState($value);
                break;
            case 7:
                $this->setRepeatAfter($value);
                break;
            case 8:
                $this->setDoneAt($value);
                break;
            case 9:
                $this->setPublic($value);
                break;
            case 10:
                $this->setDescription($value);
                break;
            case 11:
                $this->setCreatedAt($value);
                break;
            case 12:
                $this->setUpdatedAt($value);
                break;
        } // switch()

        return $this;
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = NoteTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setUserId($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setImportance($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setTitle($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setDeadline($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setCategoryId($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setState($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setRepeatAfter($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setDoneAt($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setPublic($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setDescription($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setCreatedAt($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setUpdatedAt($arr[$keys[12]]);
        }
    }

     /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     * @param string $keyType The type of keys the array uses.
     *
     * @return $this|\Models\Note The current object, for fluid interface
     */
    public function importFrom($parser, $data, $keyType = TableMap::TYPE_PHPNAME)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), $keyType);

        return $this;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(NoteTableMap::DATABASE_NAME);

        if ($this->isColumnModified(NoteTableMap::COL_ID)) {
            $criteria->add(NoteTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(NoteTableMap::COL_USER_ID)) {
            $criteria->add(NoteTableMap::COL_USER_ID, $this->user_id);
        }
        if ($this->isColumnModified(NoteTableMap::COL_IMPORTANCE)) {
            $criteria->add(NoteTableMap::COL_IMPORTANCE, $this->importance);
        }
        if ($this->isColumnModified(NoteTableMap::COL_TITLE)) {
            $criteria->add(NoteTableMap::COL_TITLE, $this->title);
        }
        if ($this->isColumnModified(NoteTableMap::COL_DEADLINE)) {
            $criteria->add(NoteTableMap::COL_DEADLINE, $this->deadline);
        }
        if ($this->isColumnModified(NoteTableMap::COL_CATEGORY_ID)) {
            $criteria->add(NoteTableMap::COL_CATEGORY_ID, $this->category_id);
        }
        if ($this->isColumnModified(NoteTableMap::COL_STATE)) {
            $criteria->add(NoteTableMap::COL_STATE, $this->state);
        }
        if ($this->isColumnModified(NoteTableMap::COL_REPEAT_AFTER)) {
            $criteria->add(NoteTableMap::COL_REPEAT_AFTER, $this->repeat_after);
        }
        if ($this->isColumnModified(NoteTableMap::COL_DONE_AT)) {
            $criteria->add(NoteTableMap::COL_DONE_AT, $this->done_at);
        }
        if ($this->isColumnModified(NoteTableMap::COL_PUBLIC)) {
            $criteria->add(NoteTableMap::COL_PUBLIC, $this->public);
        }
        if ($this->isColumnModified(NoteTableMap::COL_DESCRIPTION)) {
            $criteria->add(NoteTableMap::COL_DESCRIPTION, $this->description);
        }
        if ($this->isColumnModified(NoteTableMap::COL_CREATED_AT)) {
            $criteria->add(NoteTableMap::COL_CREATED_AT, $this->created_at);
        }
        if ($this->isColumnModified(NoteTableMap::COL_UPDATED_AT)) {
            $criteria->add(NoteTableMap::COL_UPDATED_AT, $this->updated_at);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = ChildNoteQuery::create();
        $criteria->add(NoteTableMap::COL_ID, $this->id);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        $validPk = null !== $this->getId();

        $validPrimaryKeyFKs = 0;
        $primaryKeyFKs = [];

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Models\Note (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setUserId($this->getUserId());
        $copyObj->setImportance($this->getImportance());
        $copyObj->setTitle($this->getTitle());
        $copyObj->setDeadline($this->getDeadline());
        $copyObj->setCategoryId($this->getCategoryId());
        $copyObj->setState($this->getState());
        $copyObj->setRepeatAfter($this->getRepeatAfter());
        $copyObj->setDoneAt($this->getDoneAt());
        $copyObj->setPublic($this->getPublic());
        $copyObj->setDescription($this->getDescription());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getSubNotes() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSubNote($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getFiles() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addFile($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getNotifications() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addNotification($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getComments() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addComment($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getShareds() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addShared($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getUserNotes() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserNote($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param  boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \Models\Note Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Declares an association between this object and a ChildUser object.
     *
     * @param  ChildUser $v
     * @return $this|\Models\Note The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUser(ChildUser $v = null)
    {
        if ($v === null) {
            $this->setUserId(NULL);
        } else {
            $this->setUserId($v->getId());
        }

        $this->aUser = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildUser object, it will not be re-added.
        if ($v !== null) {
            $v->addNote($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildUser object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildUser The associated ChildUser object.
     * @throws PropelException
     */
    public function getUser(ConnectionInterface $con = null)
    {
        if ($this->aUser === null && ($this->user_id !== null)) {
            $this->aUser = ChildUserQuery::create()->findPk($this->user_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUser->addNotes($this);
             */
        }

        return $this->aUser;
    }

    /**
     * Declares an association between this object and a ChildCategory object.
     *
     * @param  ChildCategory $v
     * @return $this|\Models\Note The current object (for fluent API support)
     * @throws PropelException
     */
    public function setCategory(ChildCategory $v = null)
    {
        if ($v === null) {
            $this->setCategoryId(NULL);
        } else {
            $this->setCategoryId($v->getId());
        }

        $this->aCategory = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildCategory object, it will not be re-added.
        if ($v !== null) {
            $v->addNote($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildCategory object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildCategory The associated ChildCategory object.
     * @throws PropelException
     */
    public function getCategory(ConnectionInterface $con = null)
    {
        if ($this->aCategory === null && ($this->category_id !== null)) {
            $this->aCategory = ChildCategoryQuery::create()->findPk($this->category_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCategory->addNotes($this);
             */
        }

        return $this->aCategory;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('SubNote' == $relationName) {
            return $this->initSubNotes();
        }
        if ('File' == $relationName) {
            return $this->initFiles();
        }
        if ('Notification' == $relationName) {
            return $this->initNotifications();
        }
        if ('Comment' == $relationName) {
            return $this->initComments();
        }
        if ('Shared' == $relationName) {
            return $this->initShareds();
        }
        if ('UserNote' == $relationName) {
            return $this->initUserNotes();
        }
    }

    /**
     * Clears out the collSubNotes collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addSubNotes()
     */
    public function clearSubNotes()
    {
        $this->collSubNotes = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collSubNotes collection loaded partially.
     */
    public function resetPartialSubNotes($v = true)
    {
        $this->collSubNotesPartial = $v;
    }

    /**
     * Initializes the collSubNotes collection.
     *
     * By default this just sets the collSubNotes collection to an empty array (like clearcollSubNotes());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSubNotes($overrideExisting = true)
    {
        if (null !== $this->collSubNotes && !$overrideExisting) {
            return;
        }
        $this->collSubNotes = new ObjectCollection();
        $this->collSubNotes->setModel('\Models\SubNote');
    }

    /**
     * Gets an array of ChildSubNote objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildNote is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildSubNote[] List of ChildSubNote objects
     * @throws PropelException
     */
    public function getSubNotes(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collSubNotesPartial && !$this->isNew();
        if (null === $this->collSubNotes || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collSubNotes) {
                // return empty collection
                $this->initSubNotes();
            } else {
                $collSubNotes = ChildSubNoteQuery::create(null, $criteria)
                    ->filterByNote($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collSubNotesPartial && count($collSubNotes)) {
                        $this->initSubNotes(false);

                        foreach ($collSubNotes as $obj) {
                            if (false == $this->collSubNotes->contains($obj)) {
                                $this->collSubNotes->append($obj);
                            }
                        }

                        $this->collSubNotesPartial = true;
                    }

                    return $collSubNotes;
                }

                if ($partial && $this->collSubNotes) {
                    foreach ($this->collSubNotes as $obj) {
                        if ($obj->isNew()) {
                            $collSubNotes[] = $obj;
                        }
                    }
                }

                $this->collSubNotes = $collSubNotes;
                $this->collSubNotesPartial = false;
            }
        }

        return $this->collSubNotes;
    }

    /**
     * Sets a collection of ChildSubNote objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $subNotes A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildNote The current object (for fluent API support)
     */
    public function setSubNotes(Collection $subNotes, ConnectionInterface $con = null)
    {
        /** @var ChildSubNote[] $subNotesToDelete */
        $subNotesToDelete = $this->getSubNotes(new Criteria(), $con)->diff($subNotes);


        $this->subNotesScheduledForDeletion = $subNotesToDelete;

        foreach ($subNotesToDelete as $subNoteRemoved) {
            $subNoteRemoved->setNote(null);
        }

        $this->collSubNotes = null;
        foreach ($subNotes as $subNote) {
            $this->addSubNote($subNote);
        }

        $this->collSubNotes = $subNotes;
        $this->collSubNotesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related SubNote objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related SubNote objects.
     * @throws PropelException
     */
    public function countSubNotes(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collSubNotesPartial && !$this->isNew();
        if (null === $this->collSubNotes || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSubNotes) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getSubNotes());
            }

            $query = ChildSubNoteQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByNote($this)
                ->count($con);
        }

        return count($this->collSubNotes);
    }

    /**
     * Method called to associate a ChildSubNote object to this object
     * through the ChildSubNote foreign key attribute.
     *
     * @param  ChildSubNote $l ChildSubNote
     * @return $this|\Models\Note The current object (for fluent API support)
     */
    public function addSubNote(ChildSubNote $l)
    {
        if ($this->collSubNotes === null) {
            $this->initSubNotes();
            $this->collSubNotesPartial = true;
        }

        if (!$this->collSubNotes->contains($l)) {
            $this->doAddSubNote($l);
        }

        return $this;
    }

    /**
     * @param ChildSubNote $subNote The ChildSubNote object to add.
     */
    protected function doAddSubNote(ChildSubNote $subNote)
    {
        $this->collSubNotes[]= $subNote;
        $subNote->setNote($this);
    }

    /**
     * @param  ChildSubNote $subNote The ChildSubNote object to remove.
     * @return $this|ChildNote The current object (for fluent API support)
     */
    public function removeSubNote(ChildSubNote $subNote)
    {
        if ($this->getSubNotes()->contains($subNote)) {
            $pos = $this->collSubNotes->search($subNote);
            $this->collSubNotes->remove($pos);
            if (null === $this->subNotesScheduledForDeletion) {
                $this->subNotesScheduledForDeletion = clone $this->collSubNotes;
                $this->subNotesScheduledForDeletion->clear();
            }
            $this->subNotesScheduledForDeletion[]= clone $subNote;
            $subNote->setNote(null);
        }

        return $this;
    }

    /**
     * Clears out the collFiles collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addFiles()
     */
    public function clearFiles()
    {
        $this->collFiles = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collFiles collection loaded partially.
     */
    public function resetPartialFiles($v = true)
    {
        $this->collFilesPartial = $v;
    }

    /**
     * Initializes the collFiles collection.
     *
     * By default this just sets the collFiles collection to an empty array (like clearcollFiles());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initFiles($overrideExisting = true)
    {
        if (null !== $this->collFiles && !$overrideExisting) {
            return;
        }
        $this->collFiles = new ObjectCollection();
        $this->collFiles->setModel('\Models\File');
    }

    /**
     * Gets an array of ChildFile objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildNote is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildFile[] List of ChildFile objects
     * @throws PropelException
     */
    public function getFiles(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collFilesPartial && !$this->isNew();
        if (null === $this->collFiles || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collFiles) {
                // return empty collection
                $this->initFiles();
            } else {
                $collFiles = ChildFileQuery::create(null, $criteria)
                    ->filterByNote($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collFilesPartial && count($collFiles)) {
                        $this->initFiles(false);

                        foreach ($collFiles as $obj) {
                            if (false == $this->collFiles->contains($obj)) {
                                $this->collFiles->append($obj);
                            }
                        }

                        $this->collFilesPartial = true;
                    }

                    return $collFiles;
                }

                if ($partial && $this->collFiles) {
                    foreach ($this->collFiles as $obj) {
                        if ($obj->isNew()) {
                            $collFiles[] = $obj;
                        }
                    }
                }

                $this->collFiles = $collFiles;
                $this->collFilesPartial = false;
            }
        }

        return $this->collFiles;
    }

    /**
     * Sets a collection of ChildFile objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $files A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildNote The current object (for fluent API support)
     */
    public function setFiles(Collection $files, ConnectionInterface $con = null)
    {
        /** @var ChildFile[] $filesToDelete */
        $filesToDelete = $this->getFiles(new Criteria(), $con)->diff($files);


        $this->filesScheduledForDeletion = $filesToDelete;

        foreach ($filesToDelete as $fileRemoved) {
            $fileRemoved->setNote(null);
        }

        $this->collFiles = null;
        foreach ($files as $file) {
            $this->addFile($file);
        }

        $this->collFiles = $files;
        $this->collFilesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related File objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related File objects.
     * @throws PropelException
     */
    public function countFiles(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collFilesPartial && !$this->isNew();
        if (null === $this->collFiles || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collFiles) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getFiles());
            }

            $query = ChildFileQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByNote($this)
                ->count($con);
        }

        return count($this->collFiles);
    }

    /**
     * Method called to associate a ChildFile object to this object
     * through the ChildFile foreign key attribute.
     *
     * @param  ChildFile $l ChildFile
     * @return $this|\Models\Note The current object (for fluent API support)
     */
    public function addFile(ChildFile $l)
    {
        if ($this->collFiles === null) {
            $this->initFiles();
            $this->collFilesPartial = true;
        }

        if (!$this->collFiles->contains($l)) {
            $this->doAddFile($l);
        }

        return $this;
    }

    /**
     * @param ChildFile $file The ChildFile object to add.
     */
    protected function doAddFile(ChildFile $file)
    {
        $this->collFiles[]= $file;
        $file->setNote($this);
    }

    /**
     * @param  ChildFile $file The ChildFile object to remove.
     * @return $this|ChildNote The current object (for fluent API support)
     */
    public function removeFile(ChildFile $file)
    {
        if ($this->getFiles()->contains($file)) {
            $pos = $this->collFiles->search($file);
            $this->collFiles->remove($pos);
            if (null === $this->filesScheduledForDeletion) {
                $this->filesScheduledForDeletion = clone $this->collFiles;
                $this->filesScheduledForDeletion->clear();
            }
            $this->filesScheduledForDeletion[]= clone $file;
            $file->setNote(null);
        }

        return $this;
    }

    /**
     * Clears out the collNotifications collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addNotifications()
     */
    public function clearNotifications()
    {
        $this->collNotifications = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collNotifications collection loaded partially.
     */
    public function resetPartialNotifications($v = true)
    {
        $this->collNotificationsPartial = $v;
    }

    /**
     * Initializes the collNotifications collection.
     *
     * By default this just sets the collNotifications collection to an empty array (like clearcollNotifications());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initNotifications($overrideExisting = true)
    {
        if (null !== $this->collNotifications && !$overrideExisting) {
            return;
        }
        $this->collNotifications = new ObjectCollection();
        $this->collNotifications->setModel('\Models\Notification');
    }

    /**
     * Gets an array of ChildNotification objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildNote is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildNotification[] List of ChildNotification objects
     * @throws PropelException
     */
    public function getNotifications(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collNotificationsPartial && !$this->isNew();
        if (null === $this->collNotifications || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collNotifications) {
                // return empty collection
                $this->initNotifications();
            } else {
                $collNotifications = ChildNotificationQuery::create(null, $criteria)
                    ->filterByNote($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collNotificationsPartial && count($collNotifications)) {
                        $this->initNotifications(false);

                        foreach ($collNotifications as $obj) {
                            if (false == $this->collNotifications->contains($obj)) {
                                $this->collNotifications->append($obj);
                            }
                        }

                        $this->collNotificationsPartial = true;
                    }

                    return $collNotifications;
                }

                if ($partial && $this->collNotifications) {
                    foreach ($this->collNotifications as $obj) {
                        if ($obj->isNew()) {
                            $collNotifications[] = $obj;
                        }
                    }
                }

                $this->collNotifications = $collNotifications;
                $this->collNotificationsPartial = false;
            }
        }

        return $this->collNotifications;
    }

    /**
     * Sets a collection of ChildNotification objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $notifications A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildNote The current object (for fluent API support)
     */
    public function setNotifications(Collection $notifications, ConnectionInterface $con = null)
    {
        /** @var ChildNotification[] $notificationsToDelete */
        $notificationsToDelete = $this->getNotifications(new Criteria(), $con)->diff($notifications);


        $this->notificationsScheduledForDeletion = $notificationsToDelete;

        foreach ($notificationsToDelete as $notificationRemoved) {
            $notificationRemoved->setNote(null);
        }

        $this->collNotifications = null;
        foreach ($notifications as $notification) {
            $this->addNotification($notification);
        }

        $this->collNotifications = $notifications;
        $this->collNotificationsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Notification objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Notification objects.
     * @throws PropelException
     */
    public function countNotifications(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collNotificationsPartial && !$this->isNew();
        if (null === $this->collNotifications || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collNotifications) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getNotifications());
            }

            $query = ChildNotificationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByNote($this)
                ->count($con);
        }

        return count($this->collNotifications);
    }

    /**
     * Method called to associate a ChildNotification object to this object
     * through the ChildNotification foreign key attribute.
     *
     * @param  ChildNotification $l ChildNotification
     * @return $this|\Models\Note The current object (for fluent API support)
     */
    public function addNotification(ChildNotification $l)
    {
        if ($this->collNotifications === null) {
            $this->initNotifications();
            $this->collNotificationsPartial = true;
        }

        if (!$this->collNotifications->contains($l)) {
            $this->doAddNotification($l);
        }

        return $this;
    }

    /**
     * @param ChildNotification $notification The ChildNotification object to add.
     */
    protected function doAddNotification(ChildNotification $notification)
    {
        $this->collNotifications[]= $notification;
        $notification->setNote($this);
    }

    /**
     * @param  ChildNotification $notification The ChildNotification object to remove.
     * @return $this|ChildNote The current object (for fluent API support)
     */
    public function removeNotification(ChildNotification $notification)
    {
        if ($this->getNotifications()->contains($notification)) {
            $pos = $this->collNotifications->search($notification);
            $this->collNotifications->remove($pos);
            if (null === $this->notificationsScheduledForDeletion) {
                $this->notificationsScheduledForDeletion = clone $this->collNotifications;
                $this->notificationsScheduledForDeletion->clear();
            }
            $this->notificationsScheduledForDeletion[]= clone $notification;
            $notification->setNote(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Note is new, it will return
     * an empty collection; or if this Note has previously
     * been saved, it will retrieve related Notifications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Note.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildNotification[] List of ChildNotification objects
     */
    public function getNotificationsJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildNotificationQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getNotifications($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Note is new, it will return
     * an empty collection; or if this Note has previously
     * been saved, it will retrieve related Notifications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Note.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildNotification[] List of ChildNotification objects
     */
    public function getNotificationsJoinOriginUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildNotificationQuery::create(null, $criteria);
        $query->joinWith('OriginUser', $joinBehavior);

        return $this->getNotifications($query, $con);
    }

    /**
     * Clears out the collComments collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addComments()
     */
    public function clearComments()
    {
        $this->collComments = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collComments collection loaded partially.
     */
    public function resetPartialComments($v = true)
    {
        $this->collCommentsPartial = $v;
    }

    /**
     * Initializes the collComments collection.
     *
     * By default this just sets the collComments collection to an empty array (like clearcollComments());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initComments($overrideExisting = true)
    {
        if (null !== $this->collComments && !$overrideExisting) {
            return;
        }
        $this->collComments = new ObjectCollection();
        $this->collComments->setModel('\Models\Comment');
    }

    /**
     * Gets an array of ChildComment objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildNote is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildComment[] List of ChildComment objects
     * @throws PropelException
     */
    public function getComments(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collCommentsPartial && !$this->isNew();
        if (null === $this->collComments || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collComments) {
                // return empty collection
                $this->initComments();
            } else {
                $collComments = ChildCommentQuery::create(null, $criteria)
                    ->filterByNote($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCommentsPartial && count($collComments)) {
                        $this->initComments(false);

                        foreach ($collComments as $obj) {
                            if (false == $this->collComments->contains($obj)) {
                                $this->collComments->append($obj);
                            }
                        }

                        $this->collCommentsPartial = true;
                    }

                    return $collComments;
                }

                if ($partial && $this->collComments) {
                    foreach ($this->collComments as $obj) {
                        if ($obj->isNew()) {
                            $collComments[] = $obj;
                        }
                    }
                }

                $this->collComments = $collComments;
                $this->collCommentsPartial = false;
            }
        }

        return $this->collComments;
    }

    /**
     * Sets a collection of ChildComment objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $comments A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildNote The current object (for fluent API support)
     */
    public function setComments(Collection $comments, ConnectionInterface $con = null)
    {
        /** @var ChildComment[] $commentsToDelete */
        $commentsToDelete = $this->getComments(new Criteria(), $con)->diff($comments);


        $this->commentsScheduledForDeletion = $commentsToDelete;

        foreach ($commentsToDelete as $commentRemoved) {
            $commentRemoved->setNote(null);
        }

        $this->collComments = null;
        foreach ($comments as $comment) {
            $this->addComment($comment);
        }

        $this->collComments = $comments;
        $this->collCommentsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Comment objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Comment objects.
     * @throws PropelException
     */
    public function countComments(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collCommentsPartial && !$this->isNew();
        if (null === $this->collComments || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collComments) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getComments());
            }

            $query = ChildCommentQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByNote($this)
                ->count($con);
        }

        return count($this->collComments);
    }

    /**
     * Method called to associate a ChildComment object to this object
     * through the ChildComment foreign key attribute.
     *
     * @param  ChildComment $l ChildComment
     * @return $this|\Models\Note The current object (for fluent API support)
     */
    public function addComment(ChildComment $l)
    {
        if ($this->collComments === null) {
            $this->initComments();
            $this->collCommentsPartial = true;
        }

        if (!$this->collComments->contains($l)) {
            $this->doAddComment($l);
        }

        return $this;
    }

    /**
     * @param ChildComment $comment The ChildComment object to add.
     */
    protected function doAddComment(ChildComment $comment)
    {
        $this->collComments[]= $comment;
        $comment->setNote($this);
    }

    /**
     * @param  ChildComment $comment The ChildComment object to remove.
     * @return $this|ChildNote The current object (for fluent API support)
     */
    public function removeComment(ChildComment $comment)
    {
        if ($this->getComments()->contains($comment)) {
            $pos = $this->collComments->search($comment);
            $this->collComments->remove($pos);
            if (null === $this->commentsScheduledForDeletion) {
                $this->commentsScheduledForDeletion = clone $this->collComments;
                $this->commentsScheduledForDeletion->clear();
            }
            $this->commentsScheduledForDeletion[]= clone $comment;
            $comment->setNote(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Note is new, it will return
     * an empty collection; or if this Note has previously
     * been saved, it will retrieve related Comments from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Note.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildComment[] List of ChildComment objects
     */
    public function getCommentsJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCommentQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getComments($query, $con);
    }

    /**
     * Clears out the collShareds collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addShareds()
     */
    public function clearShareds()
    {
        $this->collShareds = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collShareds collection loaded partially.
     */
    public function resetPartialShareds($v = true)
    {
        $this->collSharedsPartial = $v;
    }

    /**
     * Initializes the collShareds collection.
     *
     * By default this just sets the collShareds collection to an empty array (like clearcollShareds());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initShareds($overrideExisting = true)
    {
        if (null !== $this->collShareds && !$overrideExisting) {
            return;
        }
        $this->collShareds = new ObjectCollection();
        $this->collShareds->setModel('\Models\Shared');
    }

    /**
     * Gets an array of ChildShared objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildNote is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildShared[] List of ChildShared objects
     * @throws PropelException
     */
    public function getShareds(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collSharedsPartial && !$this->isNew();
        if (null === $this->collShareds || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collShareds) {
                // return empty collection
                $this->initShareds();
            } else {
                $collShareds = ChildSharedQuery::create(null, $criteria)
                    ->filterByNote($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collSharedsPartial && count($collShareds)) {
                        $this->initShareds(false);

                        foreach ($collShareds as $obj) {
                            if (false == $this->collShareds->contains($obj)) {
                                $this->collShareds->append($obj);
                            }
                        }

                        $this->collSharedsPartial = true;
                    }

                    return $collShareds;
                }

                if ($partial && $this->collShareds) {
                    foreach ($this->collShareds as $obj) {
                        if ($obj->isNew()) {
                            $collShareds[] = $obj;
                        }
                    }
                }

                $this->collShareds = $collShareds;
                $this->collSharedsPartial = false;
            }
        }

        return $this->collShareds;
    }

    /**
     * Sets a collection of ChildShared objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $shareds A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildNote The current object (for fluent API support)
     */
    public function setShareds(Collection $shareds, ConnectionInterface $con = null)
    {
        /** @var ChildShared[] $sharedsToDelete */
        $sharedsToDelete = $this->getShareds(new Criteria(), $con)->diff($shareds);


        $this->sharedsScheduledForDeletion = $sharedsToDelete;

        foreach ($sharedsToDelete as $sharedRemoved) {
            $sharedRemoved->setNote(null);
        }

        $this->collShareds = null;
        foreach ($shareds as $shared) {
            $this->addShared($shared);
        }

        $this->collShareds = $shareds;
        $this->collSharedsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Shared objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Shared objects.
     * @throws PropelException
     */
    public function countShareds(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collSharedsPartial && !$this->isNew();
        if (null === $this->collShareds || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collShareds) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getShareds());
            }

            $query = ChildSharedQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByNote($this)
                ->count($con);
        }

        return count($this->collShareds);
    }

    /**
     * Method called to associate a ChildShared object to this object
     * through the ChildShared foreign key attribute.
     *
     * @param  ChildShared $l ChildShared
     * @return $this|\Models\Note The current object (for fluent API support)
     */
    public function addShared(ChildShared $l)
    {
        if ($this->collShareds === null) {
            $this->initShareds();
            $this->collSharedsPartial = true;
        }

        if (!$this->collShareds->contains($l)) {
            $this->doAddShared($l);
        }

        return $this;
    }

    /**
     * @param ChildShared $shared The ChildShared object to add.
     */
    protected function doAddShared(ChildShared $shared)
    {
        $this->collShareds[]= $shared;
        $shared->setNote($this);
    }

    /**
     * @param  ChildShared $shared The ChildShared object to remove.
     * @return $this|ChildNote The current object (for fluent API support)
     */
    public function removeShared(ChildShared $shared)
    {
        if ($this->getShareds()->contains($shared)) {
            $pos = $this->collShareds->search($shared);
            $this->collShareds->remove($pos);
            if (null === $this->sharedsScheduledForDeletion) {
                $this->sharedsScheduledForDeletion = clone $this->collShareds;
                $this->sharedsScheduledForDeletion->clear();
            }
            $this->sharedsScheduledForDeletion[]= clone $shared;
            $shared->setNote(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Note is new, it will return
     * an empty collection; or if this Note has previously
     * been saved, it will retrieve related Shareds from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Note.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildShared[] List of ChildShared objects
     */
    public function getSharedsJoinCategory(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildSharedQuery::create(null, $criteria);
        $query->joinWith('Category', $joinBehavior);

        return $this->getShareds($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Note is new, it will return
     * an empty collection; or if this Note has previously
     * been saved, it will retrieve related Shareds from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Note.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildShared[] List of ChildShared objects
     */
    public function getSharedsJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildSharedQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getShareds($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Note is new, it will return
     * an empty collection; or if this Note has previously
     * been saved, it will retrieve related Shareds from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Note.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildShared[] List of ChildShared objects
     */
    public function getSharedsJoinGroup(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildSharedQuery::create(null, $criteria);
        $query->joinWith('Group', $joinBehavior);

        return $this->getShareds($query, $con);
    }

    /**
     * Clears out the collUserNotes collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUserNotes()
     */
    public function clearUserNotes()
    {
        $this->collUserNotes = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collUserNotes collection loaded partially.
     */
    public function resetPartialUserNotes($v = true)
    {
        $this->collUserNotesPartial = $v;
    }

    /**
     * Initializes the collUserNotes collection.
     *
     * By default this just sets the collUserNotes collection to an empty array (like clearcollUserNotes());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUserNotes($overrideExisting = true)
    {
        if (null !== $this->collUserNotes && !$overrideExisting) {
            return;
        }
        $this->collUserNotes = new ObjectCollection();
        $this->collUserNotes->setModel('\Models\UserNote');
    }

    /**
     * Gets an array of ChildUserNote objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildNote is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildUserNote[] List of ChildUserNote objects
     * @throws PropelException
     */
    public function getUserNotes(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collUserNotesPartial && !$this->isNew();
        if (null === $this->collUserNotes || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUserNotes) {
                // return empty collection
                $this->initUserNotes();
            } else {
                $collUserNotes = ChildUserNoteQuery::create(null, $criteria)
                    ->filterByNote($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collUserNotesPartial && count($collUserNotes)) {
                        $this->initUserNotes(false);

                        foreach ($collUserNotes as $obj) {
                            if (false == $this->collUserNotes->contains($obj)) {
                                $this->collUserNotes->append($obj);
                            }
                        }

                        $this->collUserNotesPartial = true;
                    }

                    return $collUserNotes;
                }

                if ($partial && $this->collUserNotes) {
                    foreach ($this->collUserNotes as $obj) {
                        if ($obj->isNew()) {
                            $collUserNotes[] = $obj;
                        }
                    }
                }

                $this->collUserNotes = $collUserNotes;
                $this->collUserNotesPartial = false;
            }
        }

        return $this->collUserNotes;
    }

    /**
     * Sets a collection of ChildUserNote objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $userNotes A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildNote The current object (for fluent API support)
     */
    public function setUserNotes(Collection $userNotes, ConnectionInterface $con = null)
    {
        /** @var ChildUserNote[] $userNotesToDelete */
        $userNotesToDelete = $this->getUserNotes(new Criteria(), $con)->diff($userNotes);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->userNotesScheduledForDeletion = clone $userNotesToDelete;

        foreach ($userNotesToDelete as $userNoteRemoved) {
            $userNoteRemoved->setNote(null);
        }

        $this->collUserNotes = null;
        foreach ($userNotes as $userNote) {
            $this->addUserNote($userNote);
        }

        $this->collUserNotes = $userNotes;
        $this->collUserNotesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related UserNote objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related UserNote objects.
     * @throws PropelException
     */
    public function countUserNotes(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collUserNotesPartial && !$this->isNew();
        if (null === $this->collUserNotes || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUserNotes) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getUserNotes());
            }

            $query = ChildUserNoteQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByNote($this)
                ->count($con);
        }

        return count($this->collUserNotes);
    }

    /**
     * Method called to associate a ChildUserNote object to this object
     * through the ChildUserNote foreign key attribute.
     *
     * @param  ChildUserNote $l ChildUserNote
     * @return $this|\Models\Note The current object (for fluent API support)
     */
    public function addUserNote(ChildUserNote $l)
    {
        if ($this->collUserNotes === null) {
            $this->initUserNotes();
            $this->collUserNotesPartial = true;
        }

        if (!$this->collUserNotes->contains($l)) {
            $this->doAddUserNote($l);
        }

        return $this;
    }

    /**
     * @param ChildUserNote $userNote The ChildUserNote object to add.
     */
    protected function doAddUserNote(ChildUserNote $userNote)
    {
        $this->collUserNotes[]= $userNote;
        $userNote->setNote($this);
    }

    /**
     * @param  ChildUserNote $userNote The ChildUserNote object to remove.
     * @return $this|ChildNote The current object (for fluent API support)
     */
    public function removeUserNote(ChildUserNote $userNote)
    {
        if ($this->getUserNotes()->contains($userNote)) {
            $pos = $this->collUserNotes->search($userNote);
            $this->collUserNotes->remove($pos);
            if (null === $this->userNotesScheduledForDeletion) {
                $this->userNotesScheduledForDeletion = clone $this->collUserNotes;
                $this->userNotesScheduledForDeletion->clear();
            }
            $this->userNotesScheduledForDeletion[]= clone $userNote;
            $userNote->setNote(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Note is new, it will return
     * an empty collection; or if this Note has previously
     * been saved, it will retrieve related UserNotes from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Note.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildUserNote[] List of ChildUserNote objects
     */
    public function getUserNotesJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildUserNoteQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getUserNotes($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aUser) {
            $this->aUser->removeNote($this);
        }
        if (null !== $this->aCategory) {
            $this->aCategory->removeNote($this);
        }
        $this->id = null;
        $this->user_id = null;
        $this->importance = null;
        $this->title = null;
        $this->deadline = null;
        $this->category_id = null;
        $this->state = null;
        $this->repeat_after = null;
        $this->done_at = null;
        $this->public = null;
        $this->description = null;
        $this->created_at = null;
        $this->updated_at = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collSubNotes) {
                foreach ($this->collSubNotes as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collFiles) {
                foreach ($this->collFiles as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collNotifications) {
                foreach ($this->collNotifications as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collComments) {
                foreach ($this->collComments as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collShareds) {
                foreach ($this->collShareds as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUserNotes) {
                foreach ($this->collUserNotes as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collSubNotes = null;
        $this->collFiles = null;
        $this->collNotifications = null;
        $this->collComments = null;
        $this->collShareds = null;
        $this->collUserNotes = null;
        $this->aUser = null;
        $this->aCategory = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(NoteTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     $this|ChildNote The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[NoteTableMap::COL_UPDATED_AT] = true;

        return $this;
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {

    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
