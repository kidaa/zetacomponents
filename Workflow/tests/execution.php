<?php
/**
 * File containing the ezcWorkflowTestExecution class.
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
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 */

/**
 * Workflow execution engine for testing workflows.
 *
 * @package Workflow
 * @subpackage Tests
 * @version //autogen//
 */
class ezcWorkflowTestExecution extends ezcWorkflowExecutionNonInteractive
{
    /**
     * Execution ID.
     *
     * @var integer
     */
    protected $id = 0;

    /**
     * @var array
     */
    protected $inputVariables = array();

    /**
     * @var array
     */
    protected $inputVariablesForSubWorkflow = array();

    /**
     * Property write access.
     *
     * @param string $propertyName Name of the property.
     * @param mixed $val  The value for the property.
     *
     * @throws ezcBaseValueException
     *         If a the value for the property definitionStorage is not an
     *         instance of ezcWorkflowDefinitionStorage.
     * @throws ezcBaseValueException
     *         If a the value for the property workflow is not an instance of
     *         ezcWorkflow.
     * @ignore
     */
    public function __set( $propertyName, $val )
    {
        if ( $propertyName == 'workflow' )
        {
            if ( !( $val instanceof ezcWorkflow ) )
            {
                throw new ezcBaseValueException( $propertyName, $val, 'ezcWorkflow' );
            }

            $this->properties['workflow'] = $val;

            return;
        }
        else
        {
            return parent::__set( $propertyName, $val );
        }
    }

    /**
     * Sets an input variable.
     *
     * @param  string $name
     * @param  mixed  $value
     */
    public function setInputVariable( $name, $value )
    {
        $this->inputVariables[$name] = $value;
    }

    /**
     * Sets an input variable for a sub workflow.
     *
     * @param  string $name
     * @param  mixed  $value
     */
    public function setInputVariableForSubWorkflow( $name, $value )
    {
        $this->inputVariablesForSubWorkflow[$name] = $value;
    }

    /**
     * Suspend workflow execution.
     */
    public function suspend()
    {
        parent::suspend();

        PHPUnit_Framework_Assert::assertFalse( $this->hasEnded() );
        PHPUnit_Framework_Assert::assertFalse( $this->isResumed() );
        PHPUnit_Framework_Assert::assertTrue( $this->isSuspended() );

        $inputData  = array();
        $waitingFor = $this->getWaitingFor();

        foreach ( $this->inputVariables as $name => $value )
        {
            if ( isset( $waitingFor[$name] ) )
            {
                $inputData[$name] = $value;
            }
        }

        if ( empty( $inputData ) )
        {
            throw new ezcWorkflowExecutionException(
              'Workflow is waiting for input data that has not been mocked.'
            );
        }

        $this->resume( $inputData );
    }

    /**
     * Returns a new execution object for a sub workflow.
     *
     * @param  int $id
     * @return ezcWorkflowExecution
     */
    protected function doGetSubExecution( $id = NULL )
    {
        parent::doGetSubExecution( $id );

        $execution = new ezcWorkflowTestExecution( $id );

        foreach ( $this->inputVariablesForSubWorkflow as $name => $value )
        {
            $execution->setInputVariable( $name, $value );
        }

        if ( $id !== NULL )
        {
            $execution->resume();
        }

        return $execution;
    }
}
?>
