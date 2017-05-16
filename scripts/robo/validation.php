<?php
/**
 * Validation tasks for Robo task runner.
 *
 */
class RoboFileValidation extends RoboFile
{

    // Create class properties
    protected $RoboFile = null;

    /**
    * Class initialization
    */
    public function __construct($parentClass)
    {
        // Store the parent class
        $this->RoboFile = $parentClass;
    }

    /**
    * Validates a host address.
    *
    * @param string     $host   The host address to validate.
    *
    * @return bool      Returns TRUE or FALSE.
    */
    public function validateHost($host = null)
    {
        if (
            filter_var($host, FILTER_VALIDATE_IP) ||
            (
                preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $host) &&
                preg_match("/^.{1,253}$/", $host) &&
                preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $host)
            )
        ) {
            return true;
        } else {
            return false;
        }
    }

    /**
    * Validates a port number.
    *
    * @param string     $port   The port number to validate.
    *
    * @return bool      Returns TRUE or FALSE.
    */
    public function validatePort($port = null)
    {
        if (
            filter_var($port, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1, 'max_range' => 65535)))
        ) {
            return true;
        } else {
            return false;
        }
    }

    /**
    * Validates a email address.
    *
    * @param string     $email  The email address to validate.
    *
    * @return bool      Returns TRUE or FALSE.
    */
    public function validateEmail($email = null)
    {
        if (
            filter_var($email, FILTER_VALIDATE_EMAIL)
        ) {
            return true;
        } else {
            return false;
        }
    }

    /**
    * Validates stack.* configuration keys.
    *
    * @return null
    */
    public function validateStack()
    {
        // Store temporary variables
        $RoboFile = $this->RoboFile;

        // Validate php launch
        if ($RoboFile->_getConfig('stack.php.launch') !== null) {
            if (!is_bool($RoboFile->_getConfig('stack.php.launch'))) {
                $RoboFile->_throwError("Invalid stack.php.launch value: %value", array('%value' => $RoboFile->_getConfig('stack.php.launch')));
            }
        }

        // Validate php protocol
        if ($RoboFile->_getConfig('stack.php.proto') !== null) {
            switch($RoboFile->_getConfig('stack.php.proto')) {
                case 'http':
                    break;
                case 'https':
                    break;
                default:
                    $RoboFile->_throwError("Invalid stack.php.proto value: %value", array('%value' => $RoboFile->_getConfig('stack.php.proto')));
                    break;
            }
        }

        // Validate php host address
        if ($RoboFile->_getConfig('stack.php.host') !== null) {
            if (!$this->validateHost($RoboFile->_getConfig('stack.php.host'))) {
                $RoboFile->_throwError("Invalid stack.php.host value: %value", array('%value' => $RoboFile->_getConfig('stack.php.host')));
            }
        }

        // Validate php port number
        if ($RoboFile->_getConfig('stack.php.port') !== null) {
            if (!$this->validatePort($RoboFile->_getConfig('stack.php.port'))) {
                $RoboFile->_throwError("Invalid stack.php.port value: %value", array('%value' => $RoboFile->_getConfig('stack.php.port')));
            }
        }

        // Validate php task type
        if ($RoboFile->_getConfig('stack.php.taskType') !== null) {
            switch($RoboFile->_getConfig('stack.php.taskType')) {
                case 'background':
                    $taskServer->background();
                    break;
                case 'foreground':
                    break;
                default:
                    $RoboFile->_throwError("Invalid stack.php.taskType value: %value", array('%value' => $RoboFile->_getConfig('stack.php.taskType')));
                    break;
            }
        }

        // Validate php ini
        if ($RoboFile->_getConfig('stack.php.ini') !== null) {
            if (!is_bool($RoboFile->_getConfig('stack.php.ini'))) {
                $RoboFile->_throwError("Invalid stack.php.ini value: %value", array('%value' => $RoboFile->_getConfig('stack.php.ini')));
            }
        }

        // Validate sql type
        if ($RoboFile->_getConfig('stack.sql.type') !== null) {
            switch($RoboFile->_getConfig('stack.sql.type')) {
                case 'mysql':
                    break;
                case 'sqlite':
                    break;
                default:
                    $RoboFile->_throwError("Invalid stack.sql.type value: %value", array('%value' => $RoboFile->_getConfig('stack.sql.type')));
                    break;
            }
        }

        // Validate sql prefix
        if ($RoboFile->_getConfig('stack.sql.prefix') !== null) {
            if (!is_string($RoboFile->_getConfig('stack.sql.prefix'))) {
                $RoboFile->_throwError("Invalid stack.sql.prefix value: %value", array('%value' => $RoboFile->_getConfig('stack.sql.prefix')));
            }
        }

        // Validate mysql host address
        if ($RoboFile->_getConfig('stack.mysql.host') !== null) {
            if (!$this->validateHost($RoboFile->_getConfig('stack.mysql.host'))) {
                $RoboFile->_throwError("Invalid stack.mysql.host value: %value", array('%value' => $RoboFile->_getConfig('stack.mysql.host')));
            }
        }

        // Validate mysql port number
        if ($RoboFile->_getConfig('stack.mysql.port') !== null) {
            if (!$this->validatePort($RoboFile->_getConfig('stack.mysql.port'))) {
                $RoboFile->_throwError("Invalid stack.mysql.port value: %value", array('%value' => $RoboFile->_getConfig('stack.mysql.port')));
            }
        }

        // Validate mysql suname
        if ($RoboFile->_getConfig('stack.mysql.suname') !== null) {
            if (!is_string($RoboFile->_getConfig('stack.mysql.suname'))) {
                $RoboFile->_throwError("Invalid stack.mysql.suname value: %value", array('%value' => $RoboFile->_getConfig('stack.mysql.suname')));
            }
        }

        // Validate mysql supass
        if ($RoboFile->_getConfig('stack.mysql.supass') !== null) {
            if (!is_string($RoboFile->_getConfig('stack.mysql.supass'))) {
                $RoboFile->_throwError("Invalid stack.mysql.supass value: %value", array('%value' => $RoboFile->_getConfig('stack.mysql.supass')));
            }
        }

        // Validate mysql username
        if ($RoboFile->_getConfig('stack.mysql.username') !== null) {
            if (!is_string($RoboFile->_getConfig('stack.mysql.username'))) {
                $RoboFile->_throwError("Invalid stack.mysql.username value: %value", array('%value' => $RoboFile->_getConfig('stack.mysql.username')));
            }
        }

        // Validate mysql password
        if ($RoboFile->_getConfig('stack.mysql.password') !== null) {
            if (!is_string($RoboFile->_getConfig('stack.mysql.password'))) {
                $RoboFile->_throwError("Invalid stack.mysql.password value: %value", array('%value' => $RoboFile->_getConfig('stack.mysql.password')));
            }
        }

        // Validate mysql database
        if ($RoboFile->_getConfig('stack.mysql.database') !== null) {
            if (!is_string($RoboFile->_getConfig('stack.mysql.database'))) {
                $RoboFile->_throwError("Invalid stack.mysql.database value: %value", array('%value' => $RoboFile->_getConfig('stack.mysql.database')));
            }
        }

        // Validate sqlite dbfile
        if ($RoboFile->_getConfig('stack.sqlite.dbfile') !== null) {
            if (!is_string($RoboFile->_getConfig('stack.sqlite.dbfile'))) {
                $RoboFile->_throwError("Invalid stack.sqlite.dbfile value: %value", array('%value' => $RoboFile->_getConfig('stack.sqlite.dbfile')));
            }
        }
    }

    /**
    * Validates application.* configuration keys.
    *
    * @return null
    */
    public function validateApplication()
    {
        // Store temporary variables
        $RoboFile = $this->RoboFile;

        // Validate site uri
        if ($RoboFile->_getConfig('application.basic.uri') !== null) {
            if (!$this->validateHost($RoboFile->_getConfig('application.basic.uri'))) {
                $RoboFile->_throwError("Invalid application.basic.uri value: %value", array('%value' => $RoboFile->_getConfig('application.basic.uri')));
            }
        }

        // Validate site name
        if ($RoboFile->_getConfig('application.basic.name') !== null) {
            if (!is_string($RoboFile->_getConfig('application.basic.name'))) {
                $RoboFile->_throwError("Invalid application.basic.name value: %value", array('%value' => $RoboFile->_getConfig('application.basic.name')));
            }
        }

        // Validate site email
        if ($RoboFile->_getConfig('application.basic.email') !== null) {
            if (!$this->validateEmail($RoboFile->_getConfig('application.basic.email'))) {
                $RoboFile->_throwError("Invalid application.basic.email value: %value", array('%value' => $RoboFile->_getConfig('application.basic.email')));
            }
        }

        // Validate site locale
        if ($RoboFile->_getConfig('application.basic.locale') !== null) {
            if (!is_string($RoboFile->_getConfig('application.basic.locale'))) {
                $RoboFile->_throwError("Invalid application.basic.locale value: %value", array('%value' => $RoboFile->_getConfig('application.basic.locale')));
            }
        }

        // Validate site profile
        if ($RoboFile->_getConfig('application.basic.profile') !== null) {
            if (!is_string($RoboFile->_getConfig('application.basic.profile'))) {
                $RoboFile->_throwError("Invalid application.basic.profile value: %value", array('%value' => $RoboFile->_getConfig('application.basic.profile')));
            }
        }

        // Validate adminuser name
        if ($RoboFile->_getConfig('application.adminUser.name') !== null) {
            if (!is_string($RoboFile->_getConfig('application.adminUser.name'))) {
                $RoboFile->_throwError("Invalid application.adminUser.name value: %value", array('%value' => $RoboFile->_getConfig('application.adminUser.name')));
            }
        }

        // Validate adminuser pass
        if ($RoboFile->_getConfig('application.adminUser.pass') !== null) {
            if (!is_string($RoboFile->_getConfig('application.adminUser.pass'))) {
                $RoboFile->_throwError("Invalid application.adminUser.pass value: %value", array('%value' => $RoboFile->_getConfig('application.adminUser.pass')));
            }
        }

        // Validate adminuser email
        if ($RoboFile->_getConfig('application.adminUser.email') !== null) {
            if (!$this->validateEmail($RoboFile->_getConfig('application.adminUser.email'))) {
                $RoboFile->_throwError("Invalid application.adminUser.email value: %value", array('%value' => $RoboFile->_getConfig('application.adminUser.email')));
            }
        }
    }

}
