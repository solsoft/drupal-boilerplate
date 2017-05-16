<?php
/**
 * Execution tasks for Robo task runner.
 *
 */
class RoboFileExec extends RoboFile
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
    * Runs the application in PHP's built in webserver.
    *
    * @param string     $taskType The operation mode (null, background, foreground).
    *
    * @return mixed     Returns the exit code.
    */
    public function phpRunServer($taskType = null)
    {
        // Store temporary variables
        $RoboFile = $this->RoboFile;

        // Create taskServer with php port number [mandatory]
        if ($RoboFile->_getConfig('stack.php.port') !== null) {
            $taskServer = $RoboFile->taskServer($RoboFile->_getConfig('stack.php.port'));
        } else {
            $RoboFile->_throwError("Missing stack.php.port value: %value", array('%value' => $RoboFile->_getConfig('stack.php.port')));
        }

        // Add php host address [optional]
        if ($RoboFile->_getConfig('stack.php.host') !== null) {
            $taskServer->host($RoboFile->_getConfig('stack.php.host'));
        }

        // Add web path [global]
        $taskServer->dir($RoboFile->_getConfig('deployment.web._targetFQPN'));

        // Initialize args variable
        $args = null;

        // Add php ini to arguments [optional]
        if ($RoboFile->_getConfig('stack.php.ini') !== null) {
            $args .= '--php-ini ' . $RoboFile->_truepath($RoboFile->_getConfig('deployment._targetFQPN') . DIRECTORY_SEPARATOR . $RoboFile::FILE_INI_PHP);
        }

        // Add arguments
        if (!is_null($args)) {
            $taskServer->rawArg($args);
        }

        // Add task type [optional]
        switch($taskType) {
            case 'background':
                $taskServer->background();
                break;
            case 'foreground':
                break;
            default:
                $taskType = $RoboFile->_getConfig('stack.php.taskType');
                break;
        }

        // Output log message
        $RoboFile->say("Running PHP server on $taskType mode...");

        // Start the server
        $taskServer->run();

        return true;
    }

}
