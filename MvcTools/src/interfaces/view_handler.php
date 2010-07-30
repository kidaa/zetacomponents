<?php
/**
 * File containing the ezcMvcViewHandler class
 *
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 * 
 *   http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 *
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
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
     * @param string $name
     * @param string $templateLocation
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
     *
     * The $last parameter is set if the view handler is the last one in the
     * list of zones for a specific view.
     *
     * @param bool $last
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
