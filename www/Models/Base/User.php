<?php

namespace Models\Base;

use \DateTime;
use \Exception;
use \PDO;
use Models\Category as ChildCategory;
use Models\CategoryQuery as ChildCategoryQuery;
use Models\Note as ChildNote;
use Models\NoteQuery as ChildNoteQuery;
use Models\User as ChildUser;
use Models\UserQuery as ChildUserQuery;
use Models\Map\UserTableMap;
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
 * Base class that represents a row from the 'user' table.
 *
 *
 *
* @package    propel.generator.Models.Base
*/
abstract class User implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Models\\Map\\UserTableMap';


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
     * The value for the nick field.
     *
     * @var        string
     */
    protected $nick;

    /**
     * The value for the email field.
     *
     * @var        string
     */
    protected $email;

    /**
     * The value for the rights field.
     *
     * @var        int
     */
    protected $rights;

    /**
     * The value for the email_confirmed_at field.
     *
     * @var        \DateTime
     */
    protected $email_confirmed_at;

    /**
     * The value for the password field.
     *
     * @var        string
     */
    protected $password;

    /**
     * The value for the password_reset_token field.
     *
     * @var        string
     */
    protected $password_reset_token;

    /**
     * The value for the signin_count field.
     *
     * @var        int
     */
    protected $signin_count;

    /**
     * The value for the email_confirm_token field.
     *
     * @var        string
     */
    protected $email_confirm_token;

    /**
     * The value for the avatar_path field.
     *
     * @var        string
     */
    protected $avatar_path;

    /**
     * The value for the last_signin_at field.
     *
     * @var        \DateTime
     */
    protected $last_signin_at;

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
     * @var        ObjectCollection|ChildNote[] Collection to store aggregation of ChildNote objects.
     */
    protected $collNotes;
    protected $collNotesPartial;

    /**
     * @var        ObjectCollection|ChildCategory[] Collection to store aggregation of ChildCategory objects.
     */
    protected $collCategories;
    protected $collCategoriesPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildNote[]
     */
    protected $notesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildCategory[]
     */
    protected $categoriesScheduledForDeletion = null;

    /**
     * Initializes internal state of Models\Base\User object.
     */
    public function __construct()
    {
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
     * Compares this with another <code>User</code> instance.  If
     * <code>obj</code> is an instance of <code>User</code>, delegates to
     * <code>equals(User)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|User The current object, for fluid interface
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
     * Get the [nick] column value.
     *
     * @return string
     */
    public function getNick()
    {
        return $this->nick;
    }

    /**
     * Get the [email] column value.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get the [rights] column value.
     *
     * @return int
     */
    public function getRights()
    {
        return $this->rights;
    }

    /**
     * Get the [optionally formatted] temporal [email_confirmed_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getEmailConfirmedAt($format = NULL)
    {
        if ($format === null) {
            return $this->email_confirmed_at;
        } else {
            return $this->email_confirmed_at instanceof \DateTime ? $this->email_confirmed_at->format($format) : null;
        }
    }

    /**
     * Get the [password] column value.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Get the [password_reset_token] column value.
     *
     * @return string
     */
    public function getPasswordResetToken()
    {
        return $this->password_reset_token;
    }

    /**
     * Get the [signin_count] column value.
     *
     * @return int
     */
    public function getSigninCount()
    {
        return $this->signin_count;
    }

    /**
     * Get the [email_confirm_token] column value.
     *
     * @return string
     */
    public function getEmailConfirmToken()
    {
        return $this->email_confirm_token;
    }

    /**
     * Get the [avatar_path] column value.
     *
     * @return string
     */
    public function getAvatarPath()
    {
        return $this->avatar_path;
    }

    /**
     * Get the [optionally formatted] temporal [last_signin_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getLastSigninAt($format = NULL)
    {
        if ($format === null) {
            return $this->last_signin_at;
        } else {
            return $this->last_signin_at instanceof \DateTime ? $this->last_signin_at->format($format) : null;
        }
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
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[UserTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [nick] column.
     *
     * @param string $v new value
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function setNick($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->nick !== $v) {
            $this->nick = $v;
            $this->modifiedColumns[UserTableMap::COL_NICK] = true;
        }

        return $this;
    } // setNick()

    /**
     * Set the value of [email] column.
     *
     * @param string $v new value
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function setEmail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->email !== $v) {
            $this->email = $v;
            $this->modifiedColumns[UserTableMap::COL_EMAIL] = true;
        }

        return $this;
    } // setEmail()

    /**
     * Set the value of [rights] column.
     *
     * @param int $v new value
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function setRights($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->rights !== $v) {
            $this->rights = $v;
            $this->modifiedColumns[UserTableMap::COL_RIGHTS] = true;
        }

        return $this;
    } // setRights()

    /**
     * Sets the value of [email_confirmed_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function setEmailConfirmedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->email_confirmed_at !== null || $dt !== null) {
            if ($this->email_confirmed_at === null || $dt === null || $dt->format("Y-m-d H:i:s") !== $this->email_confirmed_at->format("Y-m-d H:i:s")) {
                $this->email_confirmed_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[UserTableMap::COL_EMAIL_CONFIRMED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setEmailConfirmedAt()

    /**
     * Set the value of [password] column.
     *
     * @param string $v new value
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function setPassword($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->password !== $v) {
            $this->password = $v;
            $this->modifiedColumns[UserTableMap::COL_PASSWORD] = true;
        }

        return $this;
    } // setPassword()

    /**
     * Set the value of [password_reset_token] column.
     *
     * @param string $v new value
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function setPasswordResetToken($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->password_reset_token !== $v) {
            $this->password_reset_token = $v;
            $this->modifiedColumns[UserTableMap::COL_PASSWORD_RESET_TOKEN] = true;
        }

        return $this;
    } // setPasswordResetToken()

    /**
     * Set the value of [signin_count] column.
     *
     * @param int $v new value
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function setSigninCount($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->signin_count !== $v) {
            $this->signin_count = $v;
            $this->modifiedColumns[UserTableMap::COL_SIGNIN_COUNT] = true;
        }

        return $this;
    } // setSigninCount()

    /**
     * Set the value of [email_confirm_token] column.
     *
     * @param string $v new value
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function setEmailConfirmToken($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->email_confirm_token !== $v) {
            $this->email_confirm_token = $v;
            $this->modifiedColumns[UserTableMap::COL_EMAIL_CONFIRM_TOKEN] = true;
        }

        return $this;
    } // setEmailConfirmToken()

    /**
     * Set the value of [avatar_path] column.
     *
     * @param string $v new value
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function setAvatarPath($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->avatar_path !== $v) {
            $this->avatar_path = $v;
            $this->modifiedColumns[UserTableMap::COL_AVATAR_PATH] = true;
        }

        return $this;
    } // setAvatarPath()

    /**
     * Sets the value of [last_signin_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function setLastSigninAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->last_signin_at !== null || $dt !== null) {
            if ($this->last_signin_at === null || $dt === null || $dt->format("Y-m-d H:i:s") !== $this->last_signin_at->format("Y-m-d H:i:s")) {
                $this->last_signin_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[UserTableMap::COL_LAST_SIGNIN_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setLastSigninAt()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            if ($this->created_at === null || $dt === null || $dt->format("Y-m-d H:i:s") !== $this->created_at->format("Y-m-d H:i:s")) {
                $this->created_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[UserTableMap::COL_CREATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            if ($this->updated_at === null || $dt === null || $dt->format("Y-m-d H:i:s") !== $this->updated_at->format("Y-m-d H:i:s")) {
                $this->updated_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[UserTableMap::COL_UPDATED_AT] = true;
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : UserTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : UserTableMap::translateFieldName('Nick', TableMap::TYPE_PHPNAME, $indexType)];
            $this->nick = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : UserTableMap::translateFieldName('Email', TableMap::TYPE_PHPNAME, $indexType)];
            $this->email = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : UserTableMap::translateFieldName('Rights', TableMap::TYPE_PHPNAME, $indexType)];
            $this->rights = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : UserTableMap::translateFieldName('EmailConfirmedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->email_confirmed_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : UserTableMap::translateFieldName('Password', TableMap::TYPE_PHPNAME, $indexType)];
            $this->password = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : UserTableMap::translateFieldName('PasswordResetToken', TableMap::TYPE_PHPNAME, $indexType)];
            $this->password_reset_token = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : UserTableMap::translateFieldName('SigninCount', TableMap::TYPE_PHPNAME, $indexType)];
            $this->signin_count = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : UserTableMap::translateFieldName('EmailConfirmToken', TableMap::TYPE_PHPNAME, $indexType)];
            $this->email_confirm_token = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : UserTableMap::translateFieldName('AvatarPath', TableMap::TYPE_PHPNAME, $indexType)];
            $this->avatar_path = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : UserTableMap::translateFieldName('LastSigninAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->last_signin_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : UserTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : UserTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 13; // 13 = UserTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Models\\User'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(UserTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildUserQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collNotes = null;

            $this->collCategories = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see User::setDeleted()
     * @see User::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildUserQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $isInsert = $this->isNew();
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior

                if (!$this->isColumnModified(UserTableMap::COL_CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(UserTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(UserTableMap::COL_UPDATED_AT)) {
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
                UserTableMap::addInstanceToPool($this);
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

            if ($this->notesScheduledForDeletion !== null) {
                if (!$this->notesScheduledForDeletion->isEmpty()) {
                    \Models\NoteQuery::create()
                        ->filterByPrimaryKeys($this->notesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->notesScheduledForDeletion = null;
                }
            }

            if ($this->collNotes !== null) {
                foreach ($this->collNotes as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->categoriesScheduledForDeletion !== null) {
                if (!$this->categoriesScheduledForDeletion->isEmpty()) {
                    \Models\CategoryQuery::create()
                        ->filterByPrimaryKeys($this->categoriesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->categoriesScheduledForDeletion = null;
                }
            }

            if ($this->collCategories !== null) {
                foreach ($this->collCategories as $referrerFK) {
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

        $this->modifiedColumns[UserTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . UserTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(UserTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(UserTableMap::COL_NICK)) {
            $modifiedColumns[':p' . $index++]  = 'nick';
        }
        if ($this->isColumnModified(UserTableMap::COL_EMAIL)) {
            $modifiedColumns[':p' . $index++]  = 'email';
        }
        if ($this->isColumnModified(UserTableMap::COL_RIGHTS)) {
            $modifiedColumns[':p' . $index++]  = 'rights';
        }
        if ($this->isColumnModified(UserTableMap::COL_EMAIL_CONFIRMED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'email_confirmed_at';
        }
        if ($this->isColumnModified(UserTableMap::COL_PASSWORD)) {
            $modifiedColumns[':p' . $index++]  = 'password';
        }
        if ($this->isColumnModified(UserTableMap::COL_PASSWORD_RESET_TOKEN)) {
            $modifiedColumns[':p' . $index++]  = 'password_reset_token';
        }
        if ($this->isColumnModified(UserTableMap::COL_SIGNIN_COUNT)) {
            $modifiedColumns[':p' . $index++]  = 'signin_count';
        }
        if ($this->isColumnModified(UserTableMap::COL_EMAIL_CONFIRM_TOKEN)) {
            $modifiedColumns[':p' . $index++]  = 'email_confirm_token';
        }
        if ($this->isColumnModified(UserTableMap::COL_AVATAR_PATH)) {
            $modifiedColumns[':p' . $index++]  = 'avatar_path';
        }
        if ($this->isColumnModified(UserTableMap::COL_LAST_SIGNIN_AT)) {
            $modifiedColumns[':p' . $index++]  = 'last_signin_at';
        }
        if ($this->isColumnModified(UserTableMap::COL_CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'created_at';
        }
        if ($this->isColumnModified(UserTableMap::COL_UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'updated_at';
        }

        $sql = sprintf(
            'INSERT INTO user (%s) VALUES (%s)',
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
                    case 'nick':
                        $stmt->bindValue($identifier, $this->nick, PDO::PARAM_STR);
                        break;
                    case 'email':
                        $stmt->bindValue($identifier, $this->email, PDO::PARAM_STR);
                        break;
                    case 'rights':
                        $stmt->bindValue($identifier, $this->rights, PDO::PARAM_INT);
                        break;
                    case 'email_confirmed_at':
                        $stmt->bindValue($identifier, $this->email_confirmed_at ? $this->email_confirmed_at->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'password':
                        $stmt->bindValue($identifier, $this->password, PDO::PARAM_STR);
                        break;
                    case 'password_reset_token':
                        $stmt->bindValue($identifier, $this->password_reset_token, PDO::PARAM_STR);
                        break;
                    case 'signin_count':
                        $stmt->bindValue($identifier, $this->signin_count, PDO::PARAM_INT);
                        break;
                    case 'email_confirm_token':
                        $stmt->bindValue($identifier, $this->email_confirm_token, PDO::PARAM_STR);
                        break;
                    case 'avatar_path':
                        $stmt->bindValue($identifier, $this->avatar_path, PDO::PARAM_STR);
                        break;
                    case 'last_signin_at':
                        $stmt->bindValue($identifier, $this->last_signin_at ? $this->last_signin_at->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
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
        $pos = UserTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getNick();
                break;
            case 2:
                return $this->getEmail();
                break;
            case 3:
                return $this->getRights();
                break;
            case 4:
                return $this->getEmailConfirmedAt();
                break;
            case 5:
                return $this->getPassword();
                break;
            case 6:
                return $this->getPasswordResetToken();
                break;
            case 7:
                return $this->getSigninCount();
                break;
            case 8:
                return $this->getEmailConfirmToken();
                break;
            case 9:
                return $this->getAvatarPath();
                break;
            case 10:
                return $this->getLastSigninAt();
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

        if (isset($alreadyDumpedObjects['User'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['User'][$this->hashCode()] = true;
        $keys = UserTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getNick(),
            $keys[2] => $this->getEmail(),
            $keys[3] => $this->getRights(),
            $keys[4] => $this->getEmailConfirmedAt(),
            $keys[5] => $this->getPassword(),
            $keys[6] => $this->getPasswordResetToken(),
            $keys[7] => $this->getSigninCount(),
            $keys[8] => $this->getEmailConfirmToken(),
            $keys[9] => $this->getAvatarPath(),
            $keys[10] => $this->getLastSigninAt(),
            $keys[11] => $this->getCreatedAt(),
            $keys[12] => $this->getUpdatedAt(),
        );
        if ($result[$keys[4]] instanceof \DateTime) {
            $result[$keys[4]] = $result[$keys[4]]->format('c');
        }

        if ($result[$keys[10]] instanceof \DateTime) {
            $result[$keys[10]] = $result[$keys[10]]->format('c');
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
            if (null !== $this->collNotes) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'notes';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'notes';
                        break;
                    default:
                        $key = 'Notes';
                }

                $result[$key] = $this->collNotes->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCategories) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'categories';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'categories';
                        break;
                    default:
                        $key = 'Categories';
                }

                $result[$key] = $this->collCategories->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\Models\User
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = UserTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Models\User
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setNick($value);
                break;
            case 2:
                $this->setEmail($value);
                break;
            case 3:
                $this->setRights($value);
                break;
            case 4:
                $this->setEmailConfirmedAt($value);
                break;
            case 5:
                $this->setPassword($value);
                break;
            case 6:
                $this->setPasswordResetToken($value);
                break;
            case 7:
                $this->setSigninCount($value);
                break;
            case 8:
                $this->setEmailConfirmToken($value);
                break;
            case 9:
                $this->setAvatarPath($value);
                break;
            case 10:
                $this->setLastSigninAt($value);
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
        $keys = UserTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setNick($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setEmail($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setRights($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setEmailConfirmedAt($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setPassword($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setPasswordResetToken($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setSigninCount($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setEmailConfirmToken($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setAvatarPath($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setLastSigninAt($arr[$keys[10]]);
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
     * @return $this|\Models\User The current object, for fluid interface
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
        $criteria = new Criteria(UserTableMap::DATABASE_NAME);

        if ($this->isColumnModified(UserTableMap::COL_ID)) {
            $criteria->add(UserTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(UserTableMap::COL_NICK)) {
            $criteria->add(UserTableMap::COL_NICK, $this->nick);
        }
        if ($this->isColumnModified(UserTableMap::COL_EMAIL)) {
            $criteria->add(UserTableMap::COL_EMAIL, $this->email);
        }
        if ($this->isColumnModified(UserTableMap::COL_RIGHTS)) {
            $criteria->add(UserTableMap::COL_RIGHTS, $this->rights);
        }
        if ($this->isColumnModified(UserTableMap::COL_EMAIL_CONFIRMED_AT)) {
            $criteria->add(UserTableMap::COL_EMAIL_CONFIRMED_AT, $this->email_confirmed_at);
        }
        if ($this->isColumnModified(UserTableMap::COL_PASSWORD)) {
            $criteria->add(UserTableMap::COL_PASSWORD, $this->password);
        }
        if ($this->isColumnModified(UserTableMap::COL_PASSWORD_RESET_TOKEN)) {
            $criteria->add(UserTableMap::COL_PASSWORD_RESET_TOKEN, $this->password_reset_token);
        }
        if ($this->isColumnModified(UserTableMap::COL_SIGNIN_COUNT)) {
            $criteria->add(UserTableMap::COL_SIGNIN_COUNT, $this->signin_count);
        }
        if ($this->isColumnModified(UserTableMap::COL_EMAIL_CONFIRM_TOKEN)) {
            $criteria->add(UserTableMap::COL_EMAIL_CONFIRM_TOKEN, $this->email_confirm_token);
        }
        if ($this->isColumnModified(UserTableMap::COL_AVATAR_PATH)) {
            $criteria->add(UserTableMap::COL_AVATAR_PATH, $this->avatar_path);
        }
        if ($this->isColumnModified(UserTableMap::COL_LAST_SIGNIN_AT)) {
            $criteria->add(UserTableMap::COL_LAST_SIGNIN_AT, $this->last_signin_at);
        }
        if ($this->isColumnModified(UserTableMap::COL_CREATED_AT)) {
            $criteria->add(UserTableMap::COL_CREATED_AT, $this->created_at);
        }
        if ($this->isColumnModified(UserTableMap::COL_UPDATED_AT)) {
            $criteria->add(UserTableMap::COL_UPDATED_AT, $this->updated_at);
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
        $criteria = ChildUserQuery::create();
        $criteria->add(UserTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \Models\User (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setNick($this->getNick());
        $copyObj->setEmail($this->getEmail());
        $copyObj->setRights($this->getRights());
        $copyObj->setEmailConfirmedAt($this->getEmailConfirmedAt());
        $copyObj->setPassword($this->getPassword());
        $copyObj->setPasswordResetToken($this->getPasswordResetToken());
        $copyObj->setSigninCount($this->getSigninCount());
        $copyObj->setEmailConfirmToken($this->getEmailConfirmToken());
        $copyObj->setAvatarPath($this->getAvatarPath());
        $copyObj->setLastSigninAt($this->getLastSigninAt());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getNotes() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addNote($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCategories() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCategory($relObj->copy($deepCopy));
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
     * @return \Models\User Clone of current object.
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
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('Note' == $relationName) {
            return $this->initNotes();
        }
        if ('Category' == $relationName) {
            return $this->initCategories();
        }
    }

    /**
     * Clears out the collNotes collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addNotes()
     */
    public function clearNotes()
    {
        $this->collNotes = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collNotes collection loaded partially.
     */
    public function resetPartialNotes($v = true)
    {
        $this->collNotesPartial = $v;
    }

    /**
     * Initializes the collNotes collection.
     *
     * By default this just sets the collNotes collection to an empty array (like clearcollNotes());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initNotes($overrideExisting = true)
    {
        if (null !== $this->collNotes && !$overrideExisting) {
            return;
        }
        $this->collNotes = new ObjectCollection();
        $this->collNotes->setModel('\Models\Note');
    }

    /**
     * Gets an array of ChildNote objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildNote[] List of ChildNote objects
     * @throws PropelException
     */
    public function getNotes(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collNotesPartial && !$this->isNew();
        if (null === $this->collNotes || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collNotes) {
                // return empty collection
                $this->initNotes();
            } else {
                $collNotes = ChildNoteQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collNotesPartial && count($collNotes)) {
                        $this->initNotes(false);

                        foreach ($collNotes as $obj) {
                            if (false == $this->collNotes->contains($obj)) {
                                $this->collNotes->append($obj);
                            }
                        }

                        $this->collNotesPartial = true;
                    }

                    return $collNotes;
                }

                if ($partial && $this->collNotes) {
                    foreach ($this->collNotes as $obj) {
                        if ($obj->isNew()) {
                            $collNotes[] = $obj;
                        }
                    }
                }

                $this->collNotes = $collNotes;
                $this->collNotesPartial = false;
            }
        }

        return $this->collNotes;
    }

    /**
     * Sets a collection of ChildNote objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $notes A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setNotes(Collection $notes, ConnectionInterface $con = null)
    {
        /** @var ChildNote[] $notesToDelete */
        $notesToDelete = $this->getNotes(new Criteria(), $con)->diff($notes);


        $this->notesScheduledForDeletion = $notesToDelete;

        foreach ($notesToDelete as $noteRemoved) {
            $noteRemoved->setUser(null);
        }

        $this->collNotes = null;
        foreach ($notes as $note) {
            $this->addNote($note);
        }

        $this->collNotes = $notes;
        $this->collNotesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Note objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Note objects.
     * @throws PropelException
     */
    public function countNotes(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collNotesPartial && !$this->isNew();
        if (null === $this->collNotes || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collNotes) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getNotes());
            }

            $query = ChildNoteQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collNotes);
    }

    /**
     * Method called to associate a ChildNote object to this object
     * through the ChildNote foreign key attribute.
     *
     * @param  ChildNote $l ChildNote
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function addNote(ChildNote $l)
    {
        if ($this->collNotes === null) {
            $this->initNotes();
            $this->collNotesPartial = true;
        }

        if (!$this->collNotes->contains($l)) {
            $this->doAddNote($l);
        }

        return $this;
    }

    /**
     * @param ChildNote $note The ChildNote object to add.
     */
    protected function doAddNote(ChildNote $note)
    {
        $this->collNotes[]= $note;
        $note->setUser($this);
    }

    /**
     * @param  ChildNote $note The ChildNote object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeNote(ChildNote $note)
    {
        if ($this->getNotes()->contains($note)) {
            $pos = $this->collNotes->search($note);
            $this->collNotes->remove($pos);
            if (null === $this->notesScheduledForDeletion) {
                $this->notesScheduledForDeletion = clone $this->collNotes;
                $this->notesScheduledForDeletion->clear();
            }
            $this->notesScheduledForDeletion[]= clone $note;
            $note->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Notes from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildNote[] List of ChildNote objects
     */
    public function getNotesJoinCategory(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildNoteQuery::create(null, $criteria);
        $query->joinWith('Category', $joinBehavior);

        return $this->getNotes($query, $con);
    }

    /**
     * Clears out the collCategories collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addCategories()
     */
    public function clearCategories()
    {
        $this->collCategories = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collCategories collection loaded partially.
     */
    public function resetPartialCategories($v = true)
    {
        $this->collCategoriesPartial = $v;
    }

    /**
     * Initializes the collCategories collection.
     *
     * By default this just sets the collCategories collection to an empty array (like clearcollCategories());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCategories($overrideExisting = true)
    {
        if (null !== $this->collCategories && !$overrideExisting) {
            return;
        }
        $this->collCategories = new ObjectCollection();
        $this->collCategories->setModel('\Models\Category');
    }

    /**
     * Gets an array of ChildCategory objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildCategory[] List of ChildCategory objects
     * @throws PropelException
     */
    public function getCategories(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collCategoriesPartial && !$this->isNew();
        if (null === $this->collCategories || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCategories) {
                // return empty collection
                $this->initCategories();
            } else {
                $collCategories = ChildCategoryQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCategoriesPartial && count($collCategories)) {
                        $this->initCategories(false);

                        foreach ($collCategories as $obj) {
                            if (false == $this->collCategories->contains($obj)) {
                                $this->collCategories->append($obj);
                            }
                        }

                        $this->collCategoriesPartial = true;
                    }

                    return $collCategories;
                }

                if ($partial && $this->collCategories) {
                    foreach ($this->collCategories as $obj) {
                        if ($obj->isNew()) {
                            $collCategories[] = $obj;
                        }
                    }
                }

                $this->collCategories = $collCategories;
                $this->collCategoriesPartial = false;
            }
        }

        return $this->collCategories;
    }

    /**
     * Sets a collection of ChildCategory objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $categories A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setCategories(Collection $categories, ConnectionInterface $con = null)
    {
        /** @var ChildCategory[] $categoriesToDelete */
        $categoriesToDelete = $this->getCategories(new Criteria(), $con)->diff($categories);


        $this->categoriesScheduledForDeletion = $categoriesToDelete;

        foreach ($categoriesToDelete as $categoryRemoved) {
            $categoryRemoved->setUser(null);
        }

        $this->collCategories = null;
        foreach ($categories as $category) {
            $this->addCategory($category);
        }

        $this->collCategories = $categories;
        $this->collCategoriesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Category objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Category objects.
     * @throws PropelException
     */
    public function countCategories(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collCategoriesPartial && !$this->isNew();
        if (null === $this->collCategories || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCategories) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCategories());
            }

            $query = ChildCategoryQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collCategories);
    }

    /**
     * Method called to associate a ChildCategory object to this object
     * through the ChildCategory foreign key attribute.
     *
     * @param  ChildCategory $l ChildCategory
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function addCategory(ChildCategory $l)
    {
        if ($this->collCategories === null) {
            $this->initCategories();
            $this->collCategoriesPartial = true;
        }

        if (!$this->collCategories->contains($l)) {
            $this->doAddCategory($l);
        }

        return $this;
    }

    /**
     * @param ChildCategory $category The ChildCategory object to add.
     */
    protected function doAddCategory(ChildCategory $category)
    {
        $this->collCategories[]= $category;
        $category->setUser($this);
    }

    /**
     * @param  ChildCategory $category The ChildCategory object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeCategory(ChildCategory $category)
    {
        if ($this->getCategories()->contains($category)) {
            $pos = $this->collCategories->search($category);
            $this->collCategories->remove($pos);
            if (null === $this->categoriesScheduledForDeletion) {
                $this->categoriesScheduledForDeletion = clone $this->collCategories;
                $this->categoriesScheduledForDeletion->clear();
            }
            $this->categoriesScheduledForDeletion[]= clone $category;
            $category->setUser(null);
        }

        return $this;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->id = null;
        $this->nick = null;
        $this->email = null;
        $this->rights = null;
        $this->email_confirmed_at = null;
        $this->password = null;
        $this->password_reset_token = null;
        $this->signin_count = null;
        $this->email_confirm_token = null;
        $this->avatar_path = null;
        $this->last_signin_at = null;
        $this->created_at = null;
        $this->updated_at = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
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
            if ($this->collNotes) {
                foreach ($this->collNotes as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCategories) {
                foreach ($this->collCategories as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collNotes = null;
        $this->collCategories = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(UserTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     $this|ChildUser The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[UserTableMap::COL_UPDATED_AT] = true;

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
