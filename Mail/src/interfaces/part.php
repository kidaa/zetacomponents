<?php
/**
 * File containing the ezcMailPart class.
 *
 * @package Mail
 * @version //autogen//
 * @copyright Copyright (C) 2005, 2006 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Abstract base class for all mail MIME parts.
 *
 * This base class provides functionality to store headers and to generate
 * the mail part. Implementations of this class must handle the body of that
 * parts themselves. They must also implement {@link generateBody()} which is
 * called when the message part is generated.
 *
 * The ezcMailPart class has the following properties:
 * - <b>contentDisposition</b> <i>ezcMailContentDispositionHeader</i>
 *   contains the information from the Content-Disposition field of this mail.
 *   This useful especially when you are investigating retrieved mail to see
 *   if a part is an attachment or should be displayed inline.
 *   However, it can also be used to set the same on outgoing mail. Note that the
 *   ezcMailFile part sets the Content-Disposition field itself based on it's own
 *   properties when sending mail.
 *
 * @package Mail
 * @version //autogen//
 */
abstract class ezcMailPart
{
    /**
     * An associative array containing all the headers set for this part.
     *
     * @var array(string)
     */
    private $headers = array();

    /**
     * An array of headers to exclude when generating the headers.
     *
     * @var array(string)
     */
    private $excludeHeaders = array();

    /**
     * Holds the properties of this class.
     *
     * @var array(string=>mixed)
     */
    private $properties = array();

    /**
     * Sets the property $name to $value.
     *
     * @throws ezcBasePropertyNotFoundException if the property does not exist.
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function __set( $name, $value )
    {
        switch ( $name )
        {
            case 'contentDisposition':
                $this->properties[$name] = $value;
                break;
            default:
                throw new ezcBasePropertyNotFoundException( $name );
                break;
        }

    }

    /**
     * Returns the property $name.
     *
     * @throws ezcBasePropertyNotFoundException if the property does not exist.
     * @param string $name
     * @return mixed
     */
    public function __get( $name )
    {
        switch ( $name )
        {
            case 'contentDisposition':
                return isset( $this->properties[$name] ) ? $this->properties[$name] : null;
                break;

            default:
                throw new ezcBasePropertyNotFoundException( $name );
                break;
        }
    }


    /**
     * Returns the RAW value of the header $name.
     *
     * Returns an empty string if the header is not found.
     *
     * @param string $name
     * @return string
     */
    public function getHeader( $name )
    {
        if ( array_key_exists( $name, $this->headers ) )
        {
            return $this->headers[$name];
        }
        return '';
    }

    /**
     * Sets the header $name to the value $value.
     *
     * If the header is already set it will override the old value.
     *
     * Note: The header Content-Disposition will be overwritten by the
     * contents of the contentsDisposition property if set.
     *
     * @see generateHeaders()
     *
     * @param string $name
     * @param string $value
     * @return void
     */
    public function setHeader( $name, $value )
    {
        $this->headers[$name] = $value;
    }

    /**
     * Adds the headers $headers.
     *
     * The headers specified in the associative array of the
     * form array(headername=>value) will overwrite any existing
     * header values.
     *
     * @param array(string=>string) $headers
     * @return void
     */
    public function setHeaders( array $headers )
    {
        $this->headers = array_merge( $this->headers, $headers );
    }

    /**
     * Returns the headers set for this part as a RFC 822 string.
     *
     * Each header is separated by a line break.
     * This method does not add the required two lines of space
     * to separate the headers from the body of the part.
     *
     * This function is called automatically by generate() and
     * subclasses can override this method if they wish to set additional
     * headers when the mail is generated.
     *
     * @see setHeader()
     * @return string
     */
    public function generateHeaders()
    {
        // set content disposition header
        if( $this->contentDisposition !== null &&
            ( $this->contentDisposition instanceof ezcMailContentDispositionHeader ) )
        {
            $cdHeader = $this->contentDisposition;
            $cd = "{$cdHeader->disposition}";
            if( $cdHeader->fileName !== null )
            {
                $cd .= "; filename=\"{$cdHeader->fileName}\"";
            }

            if( $cdHeader->creationDate !== null )
            {
                $cd .= "; creation-date=\"{$cdHeader->creationDate}\"";
            }

            if( $cdHeader->modificationDate !== null )
            {
                $cd .= "; modification-date=\"{$cdHeader->modificationDate}\"";
            }

            if( $cdHeader->readDate !== null )
            {
                $cd .= "; read-date=\"{$cdHeader->readDate}\"";
            }

            if( $cdHeader->size !== null )
            {
                $cd .= "; size={$cdHeader->size}";
            }

            foreach( $cdHeader->additionalParameters as $addKey => $addValue )
            {
                $cd .="; {$addKey}=\"{$addValue}\"";
            }

            $this->setHeader( 'Content-Disposition', $cd );
        }

        // generate headers
        $text = "";
        foreach ( $this->headers as $header => $value )
        {
            if ( in_array( strtolower( $header ), $this->excludeHeaders ) === false )
            {
                $text .= "$header: $value" . ezcMailTools::lineBreak();
            }
        }

        return $text;
    }

    /**
     * The array $headers will be excluded when the headers are generated.
     *
     * @see generateHeaders()
     * @param array(string) $headers
     * @return void
     */
    public function appendExcludeHeaders( array $headers )
    {
        $lowerCaseHeaders = array();
        foreach ( $headers as $header )
        {
            $lowerCaseHeaders[] = strtolower( $header );
        }
        $this->excludeHeaders = array_merge( $this->excludeHeaders, $lowerCaseHeaders );
    }

    /**
     * Returns the body of this part as a string.
     *
     * This method is called automatically by generate() and
     * subclasses and must implement it.
     *
     * @return string
     */
    abstract public function generateBody();

    /**
     * Returns the complete mail part including both the header and the body
     * as a string.
     *
     * @return string
     */
    public function generate()
    {
        return $this->generateHeaders() .ezcMailTools::lineBreak() . $this->generateBody();
    }
}
?>
