<?php
/**
 * File containing the ezcPersistentSession class
 *
 * @package PersistentObject
 * @version //autogen//
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * ezcPersistentSession is the main runtime interface for manipulation of persistent objects.
 *
 * Persistent objects can be stored calling save() resulting in an INSERT query. If
 * the object is already persistent you can store it using update() which results in
 * an UPDATE query. If you want to query persistent objects you can use the find methods.
 *
 * @property-read ezcDbHandler $database
 *                The database handler set in the constructor.
 * @property-read ezcPersistentDefinitionManager $definitionManager
 *                The persistent definition manager set in the constructor.
 *
 * @package PersistentObject
 * @version //autogen//
 * @mainclass
 */
class ezcPersistentSession
{
    /**
     * Holds the properties of this class.
     *
     * @var array(string=>mixed)
     */
    private $properties = array();

    /**
     * Constructs a new persistent session that works on the database $db.
     *
     * The $manager provides valid persistent object definitions to the
     * session. The $db will be used to perform all database operations.
     *
     * @param ezcDbHandler $db
     * @param ezcPersistentDefinitionManager $manager
     */
    public function __construct( ezcDbHandler $db, ezcPersistentDefinitionManager $manager )
    {
        $this->properties['database']          = $db;
        $this->properties['definitionManager'] = $manager;
        $this->properties['loadHandler']       = new ezcPersistentLoadHandler( $this );
        $this->properties['saveHandler']       = new ezcPersistentSaveHandler( $this );
        $this->properties['deleteHandler']     = new ezcPersistentDeleteHandler( $this );
    }

    /**
     * Returns the persistent object of class $class with id $id.
     *
     * @throws ezcPersistentObjectException
     *         if the object is not available.
     * @throws ezcPersistentObjectException
     *         if there is no such persistent class.
     *
     * @param string $class
     * @param int $id
     *
     * @return object
     */
    public function load( $class, $id )
    {
        return $this->loadHandler->load( $class, $id );
    }

    /**
     * Returns the persistent object of class $class with id $id.
     *
     * This method is equivalent to {@link load()} except that it returns null
     * instead of throwing an exception if the object does not exist.
     *
     * @param string $class
     * @param int $id
     *
     * @return object|null
     */
    public function loadIfExists( $class, $id )
    {
        return $this->loadHandler->loadIfExists( $class, $id );
    }

    /**
     * Loads the persistent object with the id $id into the object $object.
     *
     * The class of the persistent object to load is determined by the class
     * of $object.
     *
     * @throws ezcPersistentObjectException
     *         if the object is not available.
     * @throws ezcPersistentDefinitionNotFoundException
     *         if $object is not of a valid persistent object type.
     * @throws ezcPersistentQueryException
     *         if the find query failed.
     *
     * @param object $object
     * @param int $id
     */
    public function loadIntoObject( $object, $id )
    {
        return $this->loadHandler->loadIntoObject( $object, $id );
    }

    /**
     * Syncronizes the contents of $object with those in the database.
     *
     * Note that calling this method is equavalent with calling {@link
     * loadIntoObject()} on $object with the id of $object. Any changes made
     * to $object prior to calling refresh() will be discarded.
     *
     * @throws ezcPersistentObjectException
     *         if $object is not of a valid persistent object type.
     * @throws ezcPersistentObjectException
     *         if $object is not persistent already.
     * @throws ezcPersistentObjectException
     *         if the select query failed.
     *
     * @param object $object
     */
    public function refresh( $object )
    {
        return $this->loadHandler->refresh( $object );
    }

    /**
     * Returns the result of the query $query as a list of objects.
     *
     * Returns the persistent objects found for $class using the submitted
     * $query. $query should be created using {@link createFindQuery()} to
     * ensure correct alias mappings and can be manipulated as needed.
     *
     * Example:
     * <code>
     * $q = $session->createFindQuery( 'Person' );
     * $allPersons = $session->find( $q, 'Person' );
     * </code>
     *
     * If you are retrieving large result set, consider using {@link
     * findIterator()} instead.
     *
     * Example:
     * <code>
     * $q = $session->createFindQuery( 'Person' );
     * $objects = $session->findIterator( $q, 'Person' );
     *
     * foreach( $objects as $object )
     * {
     *     // ...
     * }
     * </code>
     *
     * @throws ezcPersistentDefinitionNotFoundException
     *         if there is no such persistent class.
     * @throws ezcPersistentQueryException
     *         if the find query failed.
     *
     * @param ezcQuerySelect $query
     * @param string $class
     *
     * @return array(object($class))
     */
    public function find( ezcQuerySelect $query, $class )
    {
        return $this->loadHandler->find( $query, $class );
    }

    /**
     * Returns the result of $query for the $class as an iterator.
     *
     * This method is similar to {@link find()} but returns an {@link
     * ezcPersistentFindIterator} instead of an array of objects. This is
     * useful if you are going to loop over the objects and just need them one
     * at the time.  Because you only instantiate one object it is faster than
     * {@link find()}. In addition, only 1 record is retrieved from the
     * database in each iteration, which may reduce the data transfered between
     * the database and PHP, if you iterate only through a small subset of the
     * affected records.
     *
     * Note that if you do not loop over the complete result set you must call
     * {@link ezcPersistentFindIterator::flush()} before issuing another query.
     *
     * @throws ezcPersistentDefinitionNotFoundException
     *         if there is no such persistent class.
     * @throws ezcPersistentQueryException
     *         if the find query failed.
     *
     * @param ezcQuerySelect $query
     * @param string $class
     *
     * @return ezcPersistentFindIterator
     */
    public function findIterator( ezcQuerySelect $query, $class )
    {
        return $this->loadHandler->findIterator( $query, $class );
    }

    /**
     * Returns the related objects of a given $relatedClass for an $object.
     *
     * This method returns the related objects of type $relatedClass for the
     * given $object. This method (in contrast to {@link getRelatedObject()})
     * always returns an array of found objects, no matter if only 1 object
     * was found (e.g. {@link ezcPersistentManyToOneRelation}), none or several
     * ({@link ezcPersistentManyToManyRelation}).
     *
     * Example:
     * <code>
     * $person = $session->load( "Person", 1 );
     * $relatedAddresses = $session->getRelatedObjects( $person, "Address" );
     * echo "Number of addresses found: " . count( $relatedAddresses );
     * </code>
     *
     * Relations that should preferably be used with this method are:
     * <ul>
     * <li>{@link ezcPersistentOneToManyRelation}</li>
     * <li>{@link ezcPersistentManyToManyRelation}</li>
     * </ul>
     * For other relation types {@link getRelatedObject()} is recommended.
     *
     * @param object $object
     * @param string $relatedClass
     *
     * @return array(int=>object($relatedClass))
     *
     * @throws ezcPersistentRelationNotFoundException
     *         if the given $object does not have a relation to $relatedClass.
     */
    public function getRelatedObjects( $object, $relatedClass )
    {
        return $this->loadHandler->getRelatedObjects( $object, $relatedClass );
    }

    /**
     * Returns the related object of a given $relatedClass for an $object.
     *
     * This method returns the related object of type $relatedClass for the
     * object $object. This method (in contrast to {@link getRelatedObjects()})
     * always returns a single result object, no matter if more related objects
     * could be found (e.g. {@link ezcPersistentOneToManyRelation}). If no
     * related object is found, an exception is thrown, while {@link
     * getRelatedObjects()} just returns an empty array in this case.
     *
     * Example:
     * <code>
     * $person = $session->load( "Person", 1 );
     * $relatedAddress = $session->getRelatedObject( $person, "Address" );
     * echo "Address of this person: " . $relatedAddress->__toString();
     * </code>
     *
     * Relations that should preferably be used with this method are:
     * <ul>
     * <li>{@link ezcPersistentManyToOneRelation}</ li>
     * <li>{@link ezcPersistentOneToOneRelation}</li>
     * </ul>
     * For other relation types {@link getRelatedObjects()} is recommended.
     *
     * @param object $object
     * @param string $relatedClass
     *
     * @return object($relatedClass)
     *
     * @throws ezcPersistentRelationNotFoundException
     *         if the given $object does not have a relation to $relatedClass.
     */
    public function getRelatedObject( $object, $relatedClass )
    {
        return $this->loadHandler->getRelatedObject( $object, $relatedClass );
    }

    /**
     * Returns a select query for the given persistent object $class.
     *
     * The query is initialized to fetch all columns from the correct table and
     * has correct alias mappings between columns and property names of the
     * persistent $class.
     *
     * Example:
     * <code>
     * $q = $session->createFindQuery( 'Person' );
     * $allPersons = $session->find( $q, 'Person' );
     * </code>
     *
     * @throws ezcPersistentObjectException
     *         if there is no such persistent class.
     *
     * @param string $class
     *
     * @return ezcQuerySelect
     */
    public function createFindQuery( $class )
    {
        return $this->loadHandler->createFindQuery( $class );
    }

    /**
     * Returns the base query for retrieving related objects.
     *
     * See {@link getRelatedObject()} and {@link getRelatedObjects()}. Can be
     * modified by additional where conditions and simply be used with
     * {@link find()} and the related class name, to retrieve a sub-set of
     * related objects.
     *
     * @param object $object
     * @param string $relatedClass
     *
     * @return ezcDbSelectQuery
     *
     * @throws ezcPersistentRelationNotFoundException
     *         if the given $object does not have a relation to $relatedClass.
     */
    public function createRelationFindQuery( $object, $relatedClass )
    {
        return $this->loadHandler->createRelationFindQuery( $object, $relatedClass );
    }

    /**
     * Saves the new persistent object $object to the database using an INSERT INTO query.
     *
     * The correct ID is set to $object.
     *
     * @throws ezcPersistentObjectException if $object
     *         is not of a valid persistent object type.
     * @throws ezcPersistentObjectException if $object
     *         is already stored to the database.
     * @throws ezcPersistentObjectException
     *         if it was not possible to generate a unique identifier for the
     *         new object.
     * @throws ezcPersistentObjectException
     *         if the insert query failed.
     *
     * @param object $object
     */
    public function save( $object )
    {
        return $this->saveHandler->save( $object );
    }

    /**
     * Saves the new persistent object $object to the database using an UPDATE query.
     *
     * @throws ezcPersistentDefinitionNotFoundException if $object is not of a valid persistent object type.
     * @throws ezcPersistentObjectNotPersistentException if $object is not stored in the database already.
     * @throws ezcPersistentQueryException
     * @param object $object
     * @return void
     */
    public function update( $object )
    {
        return $this->saveHandler->update( $object );
    }

    /**
     * Saves or updates the persistent object $object to the database.
     *
     * If the object is a new object an INSERT INTO query will be executed. If
     * the object is persistent already it will be updated with an UPDATE
     * query.
     *
     * @throws ezcPersistentDefinitionNotFoundException
     *         if the definition of the persistent object could not be loaded.
     * @throws ezcPersistentObjectException
     *         if $object is not of a valid persistent object type.
     * @throws ezcPersistentObjectException
     *         if any of the definition requirements are not met.
     * @throws ezcPersistentObjectException
     *         if the insert or update query failed.
     * @param object $object
     * @return void
     */
    public function saveOrUpdate( $object )
    {
        return $this->saveHandler->saveOrUpdate( $object );
    }

    /**
     * Create a relation between $object and $relatedObject.
     *
     * This method is used to create a relation between the given source
     * $object and the desired $relatedObject. The related object is not stored
     * in the database automatically, only the desired properties are set. An
     * exception is {@ezcPersistentManyToManyRelation}s, where the relation
     * record is stored automatically and there is no need to store
     * $relatedObject explicitly after establishing the relation.
     *
     * @param object $object
     * @param object $relatedObject
     *
     * @throws ezcPersistentRelationOperationNotSupportedException
     *         if a relation to create is marked as "reverse" {@link
     *         ezcPersistentRelation->reverse}.
     * @throws ezcPersistentRelationNotFoundException
     *         if the deisred relation is not defined.
     */
    public function addRelatedObject( $object, $relatedObject )
    {
        return $this->saveHandler->addRelatedObject( $object, $relatedObject );
    }

    /**
     * Returns an update query for the given persistent object $class.
     *
     * The query is initialized to update the correct table and
     * it is only neccessary to set the correct values.
     *
     * @throws ezcPersistentDefinitionNotFoundException
     *         if there is no such persistent class.
     *
     * @param string $class
     *
     * @return ezcQueryUpdate
     */
    public function createUpdateQuery( $class )
    {
        return $this->saveHandler->createUpdateQuery( $class );
    }

    /**
     * Updates persistent objects using the query $query.
     *
     * The $query should be created using createUpdateQuery().
     *
     * Currently this method only executes the provided query. Future
     * releases PersistentSession may introduce caching of persistent objects.
     * When caching is introduced it will be required to use this method to run
     * cusom delete queries. To avoid being incompatible with future releases it is
     * advisable to always use this method when running custom delete queries on
     * persistent objects.
     *
     * @throws ezcPersistentQueryException
     *         if the update query failed.
     *
     * @param ezcQueryUpdate $query
     */
    public function updateFromQuery( ezcQueryUpdate $query )
    {
        return $this->saveHandler->updateFromQuery( $query );
    }

    /**
     * Deletes the persistent object $object.
     *
     * This method will perform a DELETE query based on the identifier of the
     * persistent object $object. After delete() the ID property of $object
     * will be reset to null. It is possible to {@link save()} $object
     * afterwards.  $object will then be stored with a new ID.
     *
     * If you defined relations for the given object, these will be checked to
     * be defined as cascading. If cascading is configured, the related objects
     * with this relation will be deleted, too.
     *
     * Relations that support cascading are:
     * <ul>
     * <li>{@link ezcPersistenOneToManyRelation}</li>
     * <li>{@link ezcPersistenOneToOne}</li>
     * </ul>
     *
     * @throws ezcPersistentDefinitionNotFoundxception
     *         if $the object is not recognized as a persistent object.
     * @throws ezcPersistentObjectNotPersistentException
     *         if the object is not persistent already.
     * @throws ezcPersistentQueryException
     *         if the object could not be deleted.
     *
     * @param object $object The persistent object to delete.
     */
    public function delete( $object )
    {
        return $this->deleteHandler->delete( $object );
    }

    /**
     * Removes the relation between $object and $relatedObject.
     *
     * This method is used to delete an existing relation between 2 objects.
     * Like {@link addRelatedObject()} this method does not store the related
     * object after removing its relation properties (unset), except for {@link
     * ezcPersistentManyToManyRelation()}s, for which the relation record is
     * deleted from the database.
     *
     * @param object $object        Source object of the relation.
     * @param object $relatedObject Related object.
     *
     * @throws ezcPersistentRelationOperationNotSupportedException
     *         if a relation to create is marked as "reverse".
     * @throws ezcPersistentRelationNotFoundException
     *         if the deisred relation is not defined.
     */
    public function removeRelatedObject( $object, $relatedObject )
    {
        return $this->deleteHandler->removeRelatedObject( $object, $relatedObject );
    }

    /**
     * Deletes persistent objects using the query $query.
     *
     * The $query should be created using {@link createDeleteQuery()}.
     *
     * Currently this method only executes the provided query. Future
     * releases PersistentSession may introduce caching of persistent objects.
     * When caching is introduced it will be required to use this method to run
     * cusom delete queries. To avoid being incompatible with future releases it is
     * advisable to always use this method when running custom delete queries on
     * persistent objects.
     *
     * @throws ezcPersistentQueryException
     *         if the delete query failed.
     *
     * @param ezcQueryDelete $query
     */
    public function deleteFromQuery( ezcQueryDelete $query )
    {
        return $this->deleteHandler->deleteFromQuery( $query );
    }

    /**
     * Returns a delete query for the given persistent object $class.
     *
     * The query is initialized to delete from the correct table and
     * it is only neccessary to set the where clause.
     *
     * Example:
     * <code>
     * $q = $session->createDeleteQuery( 'Person' );
     * $q->where( $q->expr->gt( 'age', $q->bindValue( 15 ) ) );
     * $session->deleteFromQuery( $q );
     * </code>
     *
     * @throws ezcPersistentObjectException
     *         if there is no such persistent class.
     *
     * @param string $class
     *
     * @return ezcQueryDelete
     */
    public function createDeleteQuery( $class )
    {
        return $this->deleteHandler->createDeleteQuery( $class );
    }


    /**
     * Returns a hash map between property and column name for the given definition $def.
     * The alias map can be used with the query classes.
     *
     * @param ezcPersistentObjectDefinition $def Definition.
     * @return array(string=>string)
     */
    public function generateAliasMap( ezcPersistentObjectDefinition $def, $prefixTableName = true )
    {
        $table = array();
        $table[$def->idProperty->propertyName] = ( $prefixTableName 
            ? $this->database->quoteIdentifier( $def->table ) . '.' . $this->database->quoteIdentifier( $def->idProperty->columnName )
            : $this->database->quoteIdentifier( $def->idProperty->columnName ) );
        foreach ( $def->properties as $prop )
        {
            $table[$prop->propertyName] = ( $prefixTableName 
                ? $this->database->quoteIdentifier( $def->table ) . '.' . $this->database->quoteIdentifier( $prop->columnName )
                : $this->database->quoteIdentifier( $prop->columnName ) );
        }
        $table[$def->class] = $def->table;
        return $table;
    }

    /**
     * Returns all the columns defined in the persistent object.
     *
     * @param ezcPersistentObjectDefinition $def Defintion.
     * @return array(int=>string)
     */
    public function getColumnsFromDefinition( ezcPersistentObjectDefinition $def, $prefixTableName = true )
    {
        $columns = array();
        $columns[] = ( $prefixTableName 
            ? $this->database->quoteIdentifier( $def->table ) . '.' . $this->database->quoteIdentifier( $def->idProperty->columnName )
            : $this->database->quoteIdentifier( $def->idProperty->columnName ) );
        foreach ( $def->properties as $property )
        {
            $columns[] = ( $prefixTableName
                ? $this->database->quoteIdentifier( $def->table ) . '.' . $this->database->quoteIdentifier( $property->columnName )
                : $this->database->quoteIdentifier( $property->columnName ) );
        }
        return $columns;
    }

    /**
     * Returns the object state.
     *
     * This method wraps around $object->getState() to add optional sanity
     * checks to this call, like a correct return type of getState() and
     * correct keys and values in the returned array.
     * 
     * @param object $object 
     * @return array
     *
     * @access private
     */
    public function getObjectState( $object )
    {
        // @todo Chekcs about object state should be added here, configurable.
        return $object->getState();
    }

    /**
     * Performs the given query.
     *
     * Performs the $query, checks for errors and throws an exception in case.
     * Returns the generated statement object on success.
     * 
     * @param ezcQuery $q 
     * @return PDOStatement
     *
     * @access private
     */
    public function performQuery( ezcQuery $q )
    {
        $this->database->beginTransaction();
        try
        {
            $stmt = $q->prepare();
            $stmt->execute();
            if ( ( $errCode = $stmt->errorCode() ) != 0 )
            {
                $this->database->rollback();
                throw new ezcPersistentQueryException( "The query returned error code $errCode.", $q );
            }
            $this->database->commit();
            return $stmt;
        }
        catch ( PDOException $e )
        {
            $this->database->rollback();
            throw new ezcPersistentQueryException( $e->getMessage(), $q );
        }
    }

    /**
     * Sets the property $name to $value.
     *
     * @throws ezcBasePropertyNotFoundException
     *         if the property does not exist.
     *
     * @param string $name
     * @param mixed $value
     *
     * @ignore
     */
    public function __set( $name, $value )
    {
        switch ( $name )
        {
            case 'database':
            case 'definitionManager':
            case 'loadHandler':
            case 'saveHandler':
            case 'deleteHandler':
                throw new ezcBasePropertyPermissionException( $name, ezcBasePropertyPermissionException::READ );
                break;
            default:
                throw new ezcBasePropertyNotFoundException( $name );
                break;
        }

    }

    /**
     * Property get access.
     *
     * Simply returns a given property.
     * 
     * @throws ezcBasePropertyNotFoundException
     *         If a the value for the property propertys is not an instance of
     * @param string $propertyName The name of the property to get.
     * @return mixed The property value.
     *
     * @ignore
     *
     * @throws ezcBasePropertyNotFoundException
     *         if the given property does not exist.
     * @throws ezcBasePropertyPermissionException
     *         if the property to be set is a write-only property.
     */
    public function __get( $propertyName )
    {
        if ( $this->__isset( $propertyName ) === true )
        {
            return $this->properties[$propertyName];
        }
        throw new ezcBasePropertyNotFoundException( $propertyName );
    }

    /**
     * Returns if a property exists.
     *
     * Returns true if the property exists in the {@link $properties} array
     * (even if it is null) and false otherwise. 
     *
     * @param string $propertyName Option name to check for.
     * @return void
     * @ignore
     */
    public function __isset( $propertyName )
    {
        return array_key_exists( $propertyName, $this->properties );
    }
}
?>
