<?php
/**
 * File containing the ezcConsoleArgumentTypeViolationException class.
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
 * @package ConsoleTools
 * @version //autogen//
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 */

/**
 * An argument was submitted with an illigal type.
 * This exception can be caught using {@link ezcConsoleArgumentException}.
 *
 * @package ConsoleTools
 * @version //autogen//
 */
class ezcConsoleArgumentTypeViolationException extends ezcConsoleArgumentException
{
    /**
     * Creates a new exception object. 
     * 
     * @param ezcConsoleArgument $arg The violated argument.
     * @param mixed $value            The incorrect value.
     * @return void
     */
    public function __construct( ezcConsoleArgument $arg, $value )
    {
        $typeName = 'unknown';
        switch ( $arg->type )
        {
            case ezcConsoleInput::TYPE_INT:
                $typeName = 'int';
                break;
        }
        parent::__construct( "The argument '{$arg->name}' expects a value of type '{$typeName}', but received the value '{$value}'." );
    }
}

?>
