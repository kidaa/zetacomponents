<?php
/**
 * File containing the ezcDocumentOdtStyle class
 *
 * @package Document
 * @version //autogen//
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @access private
 */

/**
 * Base class for ODT styles.
 *
 * @property-read string $name The style name.
 * @property ArrayObject $listLevels ArrayObject(ezcDocumentOdtListLevelStyle)
 *
 * @package Document
 * @version //autogen//
 * @access private
 */
abstract class ezcDocumentOdtListLevelStyle
{
    /**
     * Properties
     * 
     * @var array(string=>mixed)
     */
    private $properties = array(
        'level'     => null,
        'textStyle' => null,
    );

    /**
     * Creates a new list-level style.
     * 
     * @param int $level 
     */
    public function __construct( $level )
    {
        $this->properties['level']   = $level;
    }

    /**
     * Sets the property $name to $value.
     *
     * @throws ezcBasePropertyNotFoundException if the property does not exist.
     * @param string $name
     * @param mixed $value
     * @ignore
     */
    public function __set( $name, $value )
    {
        switch ( $name )
        {
            case 'level':
                throw new ezcBasePropertyPermissionException( $name, ezcBasePropertyPermissionException::READ );
            case 'textStyle':
                if ( !is_object( $value ) || !( $value instanceof ezcDocumentOdtStyle ) || $value->family !== ezcDocumentOdtStyle::FAMILY_TEXT )
                {
                    throw new ezcBaseValueException( $name, $value, 'ezcDocumentOdtStyle with ezcDocumentOdtStyle::FAMILY_TEXT' );
                }
                break;
            default:
                throw new ezcBasePropertyNotFoundException( $name );
        }
        $this->properties[$name] = $value;
    }

    /**
     * Returns the value of the property $name.
     *
     * @throws ezcBasePropertyNotFoundException if the property does not exist.
     * @param string $name
     * @ignore
     */
    public function __get( $name )
    {
        if ( $this->__isset( $name ) )
        {
            return $this->properties[$name];
        }
        throw new ezcBasePropertyNotFoundException( $name );
    }

    /**
     * Returns true if the property $name is set, otherwise false.
     *
     * @param string $name     
     * @return bool
     * @ignore
     */
    public function __isset( $name )
    {
        return array_key_exists( $name, $this->properties );
    }
}

?>