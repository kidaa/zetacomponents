<?php
/**
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version //autogentag//
 * @filesource
 * @package MvcTools
 */

/**
 * Interface defining view handlers.
 *
 * A view handler is the implementation of a view that converts the abstract
 * ezcMvcResult objects to ezcMvcResponse objects - which are then send to the
 * client with a response writer.
 *
 * @package MvcTools
 * @version //autogentag//
 */
interface ezcMvcViewHandler
{
    /**
     * Creates a new view handler, where $name is the name of the block and
     * $templateLocation the location of a view template.
     *
     * @var string $name
     * @var string $templateLocation
     */
    public function __construct( $name, $templateLocation = null );

    /**
     * Adds a variable to the template, which can then be used for rendering
     * the view.
     *
     * @param string $name
     * @param mixed $value
     */
    public function send( $name, $value );

    /**
     * Processes the template with the variables added by the send() method.
     * The result of this action should be retrievable through the getResult() method.
     */
    public function process( $last );

    /**
     * Returns the name of the template, as set in the constructor.
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the result of the process() method.
     *
     * @return mixed
     */
    public function getResult();
}
?>