<?php
/**
 * Deployment tasks for Robo task runner.
 *
 */
class RoboFileDeploy extends RoboFile
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
    * Deploys the application.
    *
    * @return mixed Returns the exit code.
    */
    public function pathDeploy()
    {
        // Store temporary variables
        $RoboFile = $this->RoboFile;
        $exitCode = null;

        // Initialize the Filesystem Stack
        $taskFilesystemStack = $RoboFile->taskFilesystemStack();

        // Initialize the configKeys variable
        $configKeys = array();

        // Add build paths to queue
        $configKeys[] = 'deployment.build';
        $configKeys[] = 'deployment.build.sass';

        // Add vendor and assets paths to queue
        $configKeys[] = 'deployment.vendor';
        $configKeys[] = 'deployment.vendor.gitkeep';
        $configKeys[] = 'deployment.assets';
        $configKeys[] = 'deployment.assets.gitkeep';

        // Add data paths to queue
        $configKeys[] = 'deployment.data';
        $configKeys[] = 'deployment.data.sites';
        $configKeys[] = 'deployment.data.sites.default';
        $configKeys[] = 'deployment.data.sites.default.public';
        $configKeys[] = 'deployment.data.sites.default.private';

        // Add app paths to queue

        $configKeys[] = 'deployment.app';
        $configKeys[] = 'deployment.app.core';
        $configKeys[] = 'deployment.app.profiles';
        $configKeys[] = 'deployment.app.profiles.contrib';
        $configKeys[] = 'deployment.app.profiles.contrib.gitkeep';
        $configKeys[] = 'deployment.app.profiles.custom';
        $configKeys[] = 'deployment.app.profiles.custom.gitkeep';
        $configKeys[] = 'deployment.app.modules';
        $configKeys[] = 'deployment.app.modules.contrib';
        $configKeys[] = 'deployment.app.modules.contrib.gitkeep';
        $configKeys[] = 'deployment.app.modules.custom';
        $configKeys[] = 'deployment.app.modules.custom.gitkeep';
        $configKeys[] = 'deployment.app.themes';
        $configKeys[] = 'deployment.app.themes.contrib';
        $configKeys[] = 'deployment.app.themes.contrib.gitkeep';
        $configKeys[] = 'deployment.app.themes.custom';
        $configKeys[] = 'deployment.app.themes.custom.gitkeep';
        $configKeys[] = 'deployment.app.libraries';
        $configKeys[] = 'deployment.app.sites';
        $configKeys[] = 'deployment.web';

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
                        // Create target if it does not exist
                        if (!is_dir($nodeTargetFQPN)) {
                            $taskFilesystemStack->mkdir($nodeTargetFQPN);
                            $taskFilesystemStack->chmod($nodeTargetFQPN, 0777);
                            $taskFilesystemStack->run();
                        }

                        // Copy contents to target if source is a directory
                        if (is_dir($nodeLinkFQPN)) {
                            $taskCopyDir = $RoboFile->taskCopyDir(array($nodeLinkFQPN => $nodeTargetFQPN));
                            $taskCopyDir->run();
                            $taskFilesystemStack->remove($nodeLinkFQPN);
                            $taskFilesystemStack->run();
                        }

                        // Reset target permissions
                        $taskFilesystemStack->chmod($nodeTargetFQPN, $nodeTargetPerms);
                        $taskFilesystemStack->run();

                        // Remove source if is a symlink
                        if (is_link($nodeLinkFQPN)) {
                            $taskFilesystemStack->remove($nodeLinkFQPN);
                        }

                        // Create source symlink
                        $taskFilesystemStack->symlink($nodeTargetName, $nodeLinkFQPN, false);
                        $taskFilesystemStack->run();

                        $exitCode = 0;
                    } else {
                        $exitCode = -1;
                    }
                    break;

                // Validate and construct a directory
                case 'dir':
                    if (!empty($nodeTargetName) && !empty($nodeTargetFQPN)) {
                        // Create directory
                        $taskFilesystemStack->mkdir($nodeTargetFQPN);
                        $taskFilesystemStack->chmod($nodeTargetFQPN, $nodeTargetPerms);
                        $taskFilesystemStack->run();

                        $exitCode = 0;
                    } else {
                        $exitCode = -1;
                    }
                    break;

                // Validate and construct a file
                case 'file':
                    if (!empty($nodeTargetName) && !empty($nodeTargetFQPN)) {
                        // Create file
                        $taskFilesystemStack->touch($nodeTargetFQPN);
                        $taskFilesystemStack->chmod($nodeTargetFQPN, $nodeTargetPerms);
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

    /**
    * Undeploys the application.
    *
    * @return mixed Returns the exit code.
    */
    public function pathUndeploy()
    {
        // Store temporary variables
        $RoboFile = $this->RoboFile;
        $exitCode = null;

        // Initialize the Filesystem Stack
        $taskFilesystemStack = $RoboFile->taskFilesystemStack();

        // Initialize the configKeys variable
        $configKeys = array();

        // Add build paths to queue
        $configKeys[] = 'deployment.build';

        // Add vendor and assets paths to queue
        $configKeys[] = 'deployment.assets';

        // Add data paths to queue
        $configKeys[] = 'deployment.data';

        // Add app paths to queue
        $configKeys[] = 'deployment.app.core';
        $configKeys[] = 'deployment.app.profiles.contrib';
        $configKeys[] = 'deployment.app.modules.contrib';
        $configKeys[] = 'deployment.app.themes.contrib';
        $configKeys[] = 'deployment.app.libraries';
        $configKeys[] = 'deployment.app.sites';
        $configKeys[] = 'deployment.web';

        // Perform bulk operations
        foreach ($configKeys as $configKey) {
            // Initialize variables
            $nodeTargetName = $nodeTargetFQPN = $nodeTargetPerms = null;
            $nodeLinkName = $nodeLinkFQPN = null;
            $exitCode = false;

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
