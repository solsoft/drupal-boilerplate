<?php
/**
 * Installation tasks for Robo task runner.
 *
 */
class RoboFileInstall extends RoboFile
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
    * Installs the application on the local environment.
    *
    * @return mixed Returns the exit code.
    */
    public function siteInstall()
    {
        // Store temporary variables
        $RoboFile = $this->RoboFile;
        $exitCode = null;

        // Initialize the Drush Stack
        $taskDrushStack = $RoboFile->taskDrushStack($RoboFile->_truepath($RoboFile->_getConfig('deployment.vendor._targetFQPN') . DIRECTORY_SEPARATOR . $RoboFile::FILE_BIN_DRUSH));

        // Add web path [global]
        $taskDrushStack->drupalRootDirectory($RoboFile->_getConfig('deployment.web._targetFQPN'));

        // Validate sql type
        switch ($RoboFile->_getConfig('stack.sql.type')) {
        case 'mysql':
            // Add sql URL protocol
            $sqlCon = 'mysql://';

            // Add sql database prefix [optional]
            if ($RoboFile->_getConfig('stack.sql.prefix') !== null) {
                $taskDrushStack->dbPrefix($RoboFile->_getConfig('stack.sql.prefix'));
            }

            // Add sql database su username and password [optional]
            if ($RoboFile->_getConfig('stack.mysql.suname') !== null && $RoboFile->_getConfig('stack.mysql.supass') !== null) {
                $taskDrushStack->dbSu($RoboFile->_getConfig('stack.mysql.suname'));
                $taskDrushStack->dbSuPw($RoboFile->_getConfig('stack.mysql.supass'));
            }

            // Add sql username and password [optional]
            if ($RoboFile->_getConfig('stack.mysql.username') !== null && $RoboFile->_getConfig('stack.mysql.password') !== null) {
                $sqlCon .= $RoboFile->_getConfig('stack.mysql.username') . ':' . $RoboFile->_getConfig('stack.mysql.password') . '@';
            }

            // Add sql host address [mandatory]
            if ($RoboFile->_getConfig('stack.mysql.host') !== null) {
                $sqlCon .= $RoboFile->_getConfig('stack.mysql.host');
            } else {
                $RoboFile->_throwError("Missing stack.mysql.host value: %value", array('%value' => $RoboFile->_getConfig('stack.mysql.host')));
            }

            // Add sql port number [optional]
            if ($RoboFile->_getConfig('stack.mysql.port') !== null) {
                $sqlCon .= ':' . $RoboFile->_getConfig('stack.mysql.port');
            }

            // Add sql database name [mandatory]
            if ($RoboFile->_getConfig('stack.mysql.database') !== null) {
                $sqlCon .= ':' . $RoboFile->_getConfig('stack.mysql.database');
            } else {
                $RoboFile->_throwError("Missing stack.mysql.database value: %value", array('%value' => $RoboFile->_getConfig('stack.mysql.database')));
            }

            // Add sql URL
            $taskDrushStack->dbUrl($sqlCon);
            break;
        case 'sqlite':
            // Add sql URL protocol
            $sqlCon = 'sqlite://';

            // Add sql database prefix [optional]
            if ($RoboFile->_getConfig('stack.sql.prefix') !== null) {
                $taskDrushStack->dbPrefix($RoboFile->_getConfig('stack.sql.prefix'));
            }

            // Add sql database file [mandatory]
            if ($RoboFile->_getConfig('stack.sqlite.dbfile') !== null) {
                $sqlCon .= $RoboFile->_truepath($RoboFile->_getConfig('deployment.data._targetFQPN') . DIRECTORY_SEPARATOR . $RoboFile->_getConfig('stack.sqlite.dbfile'));
            } else {
                $RoboFile->_throwError("Missing stack.sqlite.dbfile value: %value", array('%value' => $RoboFile->_getConfig('stack.sqlite.dbfile')));
            }

            // Add sql URL
            $taskDrushStack->dbUrl($sqlCon);
            break;
        }

        // Add site uri [optional]
        if ($RoboFile->_getConfig('application.basic.uri') !== null) {
            $taskDrushStack->uri($RoboFile->_getConfig('stack.php.proto') . '://' . $RoboFile->_getConfig('application.basic.uri') . ':' . $RoboFile->_getConfig('stack.php.port') . '/');
        }

        // Add site name [optional]
        if ($RoboFile->_getConfig('application.basic.name') !== null) {
            $taskDrushStack->siteName($RoboFile->_getConfig('application.basic.name'));
        }

        // Add site email [optional]
        if ($RoboFile->_getConfig('application.basic.email') !== null) {
            $taskDrushStack->siteMail($RoboFile->_getConfig('application.basic.email'));
        }

        // Add site locale [optional]
        if ($RoboFile->_getConfig('application.basic.locale') !== null) {
            $taskDrushStack->locale($RoboFile->_getConfig('application.basic.locale'));
        }

        // Add site profile [optional]
        if ($RoboFile->_getConfig('application.basic.profile') !== null) {
            $taskDrushStack->siteInstall($RoboFile->_getConfig('application.basic.profile'));
        }

        // Add adminuser name and pass [optional]
        if ($RoboFile->_getConfig('application.adminUser.name') !== null && $RoboFile->_getConfig('application.adminUser.pass') !== null) {
            $taskDrushStack->accountName($RoboFile->_getConfig('application.adminUser.name'));
            $taskDrushStack->accountPass($RoboFile->_getConfig('application.adminUser.pass'));
        }

        // Add adminuser email [optional]
        if ($RoboFile->_getConfig('application.adminUser.email') !== null) {
            $taskDrushStack->accountMail($RoboFile->_getConfig('application.adminUser.email'));
        }

        // Output log message
        $RoboFile->say("Installing Drupal...");

        // Start installation
        $exitCode = $taskDrushStack->run();

        return $exitCode->getExitCode();
    }

    /**
    * Uninstalls the application from the local environment.
    *
    * @return mixed Returns the exit code.
    */
    public function siteUninstall()
    {
        // Store temporary variables
        $RoboFile = $this->RoboFile;
        $exitCode = null;

        // Initialize the Filesystem Stack
        $taskFilesystemStack = $RoboFile->taskFilesystemStack();

        // Reset data permissions
        if (file_exists($RoboFile->_getConfig('deployment.data._targetFQPN'))) {
            $taskFilesystemStack->chmod($RoboFile->_getConfig('deployment.data._targetFQPN'), 0755)->run();
        }
        if (file_exists($RoboFile->_getConfig('deployment.data._targetFQPN'))) {
            $taskFilesystemStack->chmod($RoboFile->_getConfig('deployment.data.sites._targetFQPN'), 0755)->run();
        }
        if (file_exists($RoboFile->_getConfig('deployment.data._targetFQPN'))) {
            $taskFilesystemStack->chmod($RoboFile->_getConfig('deployment.data.sites.default._targetFQPN'), 0755)->run();
        }

        // Remove the sqlite database file
        if ($RoboFile->_getConfig('stack.sqlite.dbfile') !== null) {
            $taskFilesystemStack->remove($RoboFile->_truepath($RoboFile->_getConfig('deployment.data._targetFQPN') . DIRECTORY_SEPARATOR . $RoboFile->_getConfig('stack.sqlite.dbfile')));
            $taskFilesystemStack->run();
        }

        // Initialize the configKeys variable
        $configKeys = array();

        // Add data paths to queue
        $configKeys[] = 'deployment.data.sites.default.settingsphp';
        $configKeys[] = 'deployment.data.sites.default.servicesyaml';
        $configKeys[] = 'deployment.data.sites.default.public';
        $configKeys[] = 'deployment.data.sites.default.private';

        // Perform bulk operations
        foreach ($configKeys as $configKey) {
            // Initialize variables
            $nodeTargetName = $nodeTargetFQPN = $nodeTargetPerms = null;
            $nodeLinkName = $nodeLinkFQPN = null;

            // Prepare the node variables
            $nodeTargetName = $RoboFile->_getConfig($configKey . '._targetName');
            $nodeTargetFQPN = $RoboFile->_getConfig($configKey . '._targetFQPN');
            $nodeTargetPerms = $RoboFile->_getConfig($configKey . '._targetPerms');
            $nodeLinkName = $RoboFile->_getConfig($configKey . '._linkName');
            $nodeLinkFQPN = $RoboFile->_getConfig($configKey . '._linkFQPN');

            // Resolve the path type
            switch($RoboFile->_getConfig($configKey . '._type')) {

                // Validate and construct a symlink
                case 'symlink':
                    if (!empty($nodeTargetName) && !empty($nodeTargetFQPN) && !empty($nodeTargetPerms) && !empty($nodeLinkName) && !empty($nodeLinkFQPN)) {
                        // Delete target
                        $taskFilesystemStack->remove($nodeTargetFQPN);
                        $taskFilesystemStack->run();

                        // Delete source symlink
                        $taskFilesystemStack->remove($nodeLinkFQPN);
                        $taskFilesystemStack->run();

                        $exitCode = 0;
                    } else {
                        $exitCode = -1;
                    }
                    break;

                // Validate and construct a directory
                case 'dir':
                    if (!empty($nodeTargetName) && !empty($nodeTargetFQPN)) {
                        // Delete directory
                        $taskFilesystemStack->remove($nodeTargetFQPN);
                        $taskFilesystemStack->run();

                        $exitCode = 0;
                    } else {
                        $exitCode = -1;
                    }
                    break;

                // Validate and construct a file
                case 'file':
                    if (!empty($nodeTargetName) && !empty($nodeTargetFQPN)) {
                        // Delete file
                        $taskFilesystemStack->remove($nodeTargetFQPN);
                        $taskFilesystemStack->run();

                        $exitCode = 0;
                    } else {
                        $exitCode = -1;
                    }
                    break;

                // Invalid type of path
                default:
                    $exitCode = -1;
                    break;

            }

            // Merge the paths with the active configuration or return an error
            if ($exitCode != 0) {
                $RoboFile->_throwError("Invalid combination of $configKey values: type => %type, targetName => %targetName, targetFQPN => %targetFQPN, targetPerms => %targetPerms, linkName => %linkName, linkFQPN => %linkFQPN", array(
                    '%type' => $this->_getConfig($configKey . '._type'),
                    '%targetName' => $nodeTargetName,
                    '%targetFQPN' => $nodeTargetFQPN,
                    '%targetPerms' => $nodeTargetPerms,
                    '%linkName' => $nodeLinkName,
                    '%linkFQPN' => $nodeLinkFQPN
                ));
            }
        }

        return $exitCode;
    }

}
