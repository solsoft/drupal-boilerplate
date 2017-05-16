<?php
/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */

// Load dependencies
use \Symfony\Component\Yaml\Yaml;
use \Symfony\Component\Yaml\Exception\ParseException;
use \Adbar\ArrayAccess;

class RoboFile extends \Robo\Tasks
{

    // Load dependencies
    use \Boedah\Robo\Task\Drush\loadTasks;

    // Define constants
    const FILE_YAML_PROJECT = 'project.yaml';
    const FILE_YAML_ENVIRONMENT = 'environment.yaml';
    const FILE_YAML_GLOBAL = 'global.yaml';
    const FILE_YAML_LOCAL = 'local.yaml';
    const FILE_INI_PHP = 'php.ini';
    const FILE_BIN_DRUSH = 'bin/drush';

    // Create variables
    public $configuration = array();

    // Create class properties
    protected $thisValidation = null;
    protected $thisDeploy = null;
    protected $thisInstall = null;
    protected $thisExec = null;

    // Class initialization
    public function __construct()
    {
        // Load the base configuration
        $this->_loadBaseConfig();

        // Construct the project paths
        $this->_constructProjectPaths();

        // Load the environment file
        $this->_loadEnvironment();

        // Load the project configuration
        $this->_loadProjectConfig();

        // Output the header with information
        print_r($this->_outputHeader());
        $this->say($this->_outputHeader());

        // Load child class files
        require_once($this->_truepath($this->_getConfig('deployment.scripts.robo._targetFQPN') . DIRECTORY_SEPARATOR . 'validation.php'));
        require_once($this->_truepath($this->_getConfig('deployment.scripts.robo._targetFQPN') . DIRECTORY_SEPARATOR . 'deploy.php'));
        require_once($this->_truepath($this->_getConfig('deployment.scripts.robo._targetFQPN') . DIRECTORY_SEPARATOR . 'install.php'));
        require_once($this->_truepath($this->_getConfig('deployment.scripts.robo._targetFQPN') . DIRECTORY_SEPARATOR . 'exec.php'));

        // Create child class instances
        $this->RoboFileValidation = new RoboFileValidation($this);
        $this->RoboFileDeploy = new RoboFileDeploy($this);
        $this->RoboFileInstall = new RoboFileInstall($this);
        $this->RoboFileExec = new RoboFileExec($this);

        // Validate configuration
        $this->RoboFileValidation->validateStack();
        $this->RoboFileValidation->validateApplication();
    }

    /**
     * Loads the base configuration from FILE_YAML_PROJECT.
     *
     * @return void
     */
    private function _loadBaseConfig()
    {
        // Initialize configBase variable
        $configBase = array();

        // Import the base configuration file
        $configBase = $this->_loadYAMLfile($this::FILE_YAML_PROJECT);

        // Merge the base with the active configuration
        $this->configuration = array_replace_recursive($this->configuration, $configBase);
    }

    /**
     * Loads the environment file from FILE_YAML_ENVIRONMENT.
     *
     * @return void
     */
    private function _loadEnvironment()
    {
        // Initialize environmentFile variable
        $environmentFile = array();

        // Import the environment file
        $environmentFile = $this->_loadYAMLfile($this->_getConfig('deployment.config.project._targetFQPN') . DIRECTORY_SEPARATOR . $this::FILE_YAML_ENVIRONMENT);

        // Set the environment in the active configuration
        $this->configuration = array_replace_recursive($this->configuration, array('environment' => $environmentFile['environment']));
    }

    /**
     * Loads the project configuration from FILE_YAML_GLOBAL, FILE_YAML_LOCAL
     * and environment files.
     *
     * @return void
     */
    private function _loadProjectConfig()
    {
        // Initialize config variables
        $configGlobal = $configEnv = $configLocal = array();

        // Import the global configuration file
        $configGlobal = $this->_loadYAMLfile($this->_getConfig('deployment.config.project._targetFQPN') . DIRECTORY_SEPARATOR . $this::FILE_YAML_GLOBAL);

        // Import the selected environment configuration file
        $configEnv = $this->_loadYAMLfile($this->_getConfig('deployment.config.project.envs._targetFQPN') . DIRECTORY_SEPARATOR . $this->_getConfig('environment') . '.yaml');

        // Import the local configuration file
        if (is_readable($this->_getConfig('deployment.config.project._targetFQPN') . DIRECTORY_SEPARATOR . $this::FILE_YAML_LOCAL)) {
            $configLocal = $this->_loadYAMLfile($this->_getConfig('deployment.config.project._targetFQPN') . DIRECTORY_SEPARATOR . $this::FILE_YAML_LOCAL);
        } else {
            $configLocal = array();
        }

        // Merge the project with the active configuration
        $this->configuration = array_replace_recursive($this->configuration, $configGlobal, $configEnv, $configLocal);
    }

    /**
     * Constructs the project paths.
     *
     * @return void
     */
    private function _constructProjectPaths()
    {
        // Initialize the configKeys variable
        $configKeys = array();

        // Add root keys to queue
        $configKeys[] = 'deployment';

        // Add lock files to queue
        $configKeys[] = 'deployment.composerlock';

        // Add scripts keys to queue
        $configKeys[] = 'deployment.scripts';
        $configKeys[] = 'deployment.scripts.robo';

        // Add config keys to queue
        $configKeys[] = 'deployment.config';
        $configKeys[] = 'deployment.config.project';
        $configKeys[] = 'deployment.config.project.envs';
        $configKeys[] = 'deployment.config.composer';
        $configKeys[] = 'deployment.config.composer-examples';

        // Add build paths to queue
        $configKeys[] = 'deployment.build';
        $configKeys[] = 'deployment.build.sass';

        // Add vendor and assets paths to queue
        $configKeys[] = 'deployment.vendor';
        $configKeys[] = 'deployment.vendor.gitkeep';
        $configKeys[] = 'deployment.assets';
        $configKeys[] = 'deployment.assets.gitkeep';

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

        // Add data paths to queue
        $configKeys[] = 'deployment.data';
        $configKeys[] = 'deployment.data.sites';
        $configKeys[] = 'deployment.data.sites.default';
        $configKeys[] = 'deployment.data.sites.default.settingsphp';
        $configKeys[] = 'deployment.data.sites.default.public';
        $configKeys[] = 'deployment.data.sites.default.private';

        // Perform bulk operations
        foreach ($configKeys as $configKey) {
            // Initialize temporary variables
            $parentKey = $parentFQPN = null;
            $nodeTargetName = $nodeTargetFQPN = $nodeTargetPerms = null;
            $nodeLinkName = $nodeLinkFQPN = null;
            $paths = array();
            $error = false;

            // Prepare the parent variables
            $parentKey = substr($configKey, 0, strrpos($configKey, '.'));
            if (!empty($parentKey)) {
                $parentFQPN = $this->_getConfig($parentKey . '._targetFQPN') . DIRECTORY_SEPARATOR;
            } else {
                $parentFQPN = $this->_getConfig('deployment._targetName') . DIRECTORY_SEPARATOR;
            }

            // Prepare the node variables
            $nodeTargetName = $this->_getConfig($configKey . '._targetName');
            $nodeTargetFQPN = $this->_truepath($parentFQPN . $nodeTargetName);
            $nodeTargetPerms = $this->_getConfig($configKey . '._targetPerms');

            // Resolve the path type
            switch($this->_getConfig($configKey . '._type')) {

                // Validate and construct a symlink
                case 'symlink':
                    // Prepare the extra node link variables
                    $nodeLinkName = $this->_getConfig($configKey . '._linkName');
                    $nodeLinkFQPN = $this->_truepath($parentFQPN . $nodeLinkName);
                    if (!empty($nodeTargetName) && !empty($nodeTargetFQPN) && !empty($nodeTargetPerms) && !empty($nodeLinkName) && !empty($nodeLinkFQPN)) {
                        // Reset permissions
                        if (empty($nodeTargetPerms)) {
                            $nodeTargetPerms = '0755';
                        }
                        // Set the new path variables
                        $this->_setDotArr($paths, $configKey . '._targetName', $nodeTargetName);
                        $this->_setDotArr($paths, $configKey . '._targetFQPN', $nodeTargetFQPN);
                        $this->_setDotArr($paths, $configKey . '._targetPerms', $nodeTargetPerms);
                        $this->_setDotArr($paths, $configKey . '._linkName', $nodeLinkName);
                        $this->_setDotArr($paths, $configKey . '._linkFQPN', $nodeLinkFQPN);
                    } else {
                        $error = true;
                    }
                    break;

                // Validate and construct a directory or file
                case 'dir' || 'file':
                    if (!empty($nodeTargetName) && !empty($nodeTargetFQPN)) {
                        // Reset permissions
                        if (empty($nodeTargetPerms) && $this->_getConfig($configKey . '._type') == 'dir') {
                            $nodeTargetPerms = '0755';
                        } elseif (empty($nodeTargetPerms) && $this->_getConfig($configKey . '._type') == 'file') {
                            $nodeTargetPerms = '0644';
                        }
                        // Set the new path variables
                        $this->_setDotArr($paths, $configKey . '._targetFQPN', $nodeTargetFQPN);
                        $this->_setDotArr($paths, $configKey . '._targetPerms', $nodeTargetPerms);
                    } else {
                        $error = true;
                    }
                    break;

                // Invalid type of path
                default:
                    $error = true;
                    break;

            }

            // Merge the paths with the active configuration or return an error
            if (!$error) {
                $this->configuration = array_replace_recursive($this->configuration, $paths);
            } else {
                $this->_throwError("Invalid combination of $configKey values: type => %type, targetName => %targetName, targetFQPN => %targetFQPN, targetPerms => %targetPerms, linkName => %linkName, linkFQPN => %linkFQPN", array(
                    '%type' => $this->_getConfig($configKey . '._type'),
                    '%targetName' => $nodeTargetName,
                    '%targetFQPN' => $nodeTargetFQPN,
                    '%targetPerms' => $nodeTargetPerms,
                    '%linkName' => $nodeLinkName,
                    '%linkFQPN' => $nodeLinkFQPN
                ));
            }
        }
    }

    /**
     * Gets the true fqpn of a fqpn for the current operating system.
     * https://stackoverflow.com/questions/4049856/replace-phps-realpath/4050444
     *
     * @param string     $fqpn   The fqpn to transform.
     *
     * @return string    Returns the new true fqpn.
     */
    public function _truepath($fqpn = null) {
        // Validate the fqpn variable
        if (empty($fqpn) || !is_string($fqpn)) {
            $this->_throwError("Invalid FQPN: %fqpn", array('%fqpn' => $fqpn));
        }

        // Validate whether fqpn is unix or not
        $unix = (strpos($fqpn, ':') === false && strpos($fqpn, '\\') === false) ? true: false;
        $unc = (substr($fqpn, 0, 2) == '\\\\') ? true : false;

        // Attempts to detect if fqpn is relative in which case, add cwd
        if (substr($fqpn, 0, 1) != '/' && $unix && !$unc) {
            $fqpn = getcwd() . DIRECTORY_SEPARATOR . $fqpn;
        }

        // Resolve fqpn parts (single dot, double dot and double delimiters)
        $fqpn = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $fqpn);
        $parts = array_filter(explode(DIRECTORY_SEPARATOR, $fqpn), 'strlen');
        $absolutes = array();
        foreach ($parts as $part) {
            if ($part == '.') {
                continue;
            }
            if ($part == '..') {
                array_pop($absolutes);
            } else {
                $absolutes[] = $part;
            }
        }
        $fqpn = implode(DIRECTORY_SEPARATOR, $absolutes);

        // Resolve any symlinks
        if (function_exists('readlink') && file_exists($fqpn) && linkinfo($fqpn) > 0) {
            $fqpn = readlink($fqpn);
        }

        // Put initial separator that could have been lost
        $fqpn = ($unix && substr($fqpn, 0, 1) != '/') ? '/' . $fqpn : $fqpn;
        $fqpn = ($unc && substr($fqpn, 0, 2) != '\\\\') ? '\\\\' . $fqpn : $fqpn;

        return $fqpn;
    }

    /**
     * Parses YAML configuration from a file.
     *
     * @param string     $fqpn   The FQPN of the YAML file to read.
     *
     * @return array     Returns a YAML array.
     */
    public function _loadYAMLfile($fqpn = null)
    {
        // Validate the fqpn variable
        if (empty($fqpn) || !is_string($fqpn) || !is_readable($fqpn)) {
            $this->_throwError("Invalid YAML file FQPN: %fqpn", array('%fqpn' => $fqpn));
        }

        // Initialize the yaml variable
        $yaml = array();

        // Load and parse the YAML file
        try {
            $yaml = Yaml::parse(file_get_contents($fqpn));
        } catch (ParseException $error) {
            printf("Unable to parse the YAML string: %s", $error->getMessage());
        }

        return $yaml;
    }

    /**
     * Gets the value of a key from the active configuration.
     *
     * @param string     $key           The key to get from the active configuration.
     * @param string     $defaultValue  The default value to use in absence.
     *
     * @return mixed     Returns an array or null.
     */
    public function _getConfig($key = null, $defaultValue = null)
    {
        // Validate the key variable
        if (empty($key) || !is_string($key)) {
            $this->_throwError("Invalid configuration key: %key", array('%key' => $key));
        }

        // Initialize the config variable
        $config = null;

        // Get the configuration value
        $config = $this->_getDotArr($this->configuration, $key, $defaultValue);

        return $config;
    }

    /**
     * Sets the value of a key in the active configuration.
     *
     * @param string     $key       The key to set in the active configuration.
     * @param string     $value     The value to match with the key.
     *
     * @return mixed     Returns an array or null.
     */
    public function _setConfig($key = null, $value = null)
    {
        // Validate the key variable
        if (empty($key) || !is_string($key)) {
            $this->_throwError("Invalid configuration key: %key", array('%key' => $key));
        }

        // Initialize the config variable
        $config = null;

        // Get the configuration value
        $config = $this->_setDotArr($this->configuration, $key, $value);

        return $config;
    }

    /**
     * Get an item from an array using "dot" notation.
     * https://github.com/laravel/framework/blob/4.2/src/Illuminate/Support/Arr.php#L211
     *
     * @param  array   $array
     * @param  string  $key
     * @param  mixed   $defaultValue
     *
     * @return mixed
     */
    public function _getDotArr($array = array(), $key = null, $defaultValue = null)
    {
        if (is_null($key)) {
            return $array;
        }

        if (isset($array[$key])) {
            return $array[$key];
        }

        foreach (explode('.', $key) as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return $defaultValue;
            }
            $array = $array[$segment];
        }

        return $array;
    }

    /**
     * Set an array item to a given value using "dot" notation.
     * https://github.com/laravel/framework/blob/4.2/src/Illuminate/Support/Arr.php#L211
     *
     * If no key is given to the method, the entire array will be replaced.
     *
     * @param  array   $array
     * @param  string  $key
     * @param  mixed   $value
     *
     * @return array
     */
    public function _setDotArr(&$array = array(), $key = null, $value = null)
    {
        if (is_null($key)) {
            return $array = $value;
        }

        $keys = explode('.', $key);

        while (count($keys) > 1) {
            $key = array_shift($keys);

            // If the key doesn't exist at this depth, we will just create an empty array
            // to hold the next value, allowing us to create the arrays to hold final
            // values at the correct depth. Then we'll keep digging into the array.
            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = array();
            }

            $array = &$array[$key];
        }

        $array[array_shift($keys)] = $value;

        return $array;
    }

    /**
     * Flatten a multi-dimensional associative array with dots.
     *
     * @param  array   $array
     * @param  string  $prepend
     *
     * @return array
     */
    public function _convertToDotArr($array = array(), $prepend = '')
    {
        $results = array();

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $results = array_merge($results, static::_convertToDotArr($value, $prepend . $key . '.'));
            } else {
                $results[$prepend.$key] = $value;
            }
        }

        return $results;
    }

    /**
     * Stops execution and throws an error message.
     *
     * @param string     $message    The error message to throw.
     * @param string     $substr     The variable substitution array.
     *
     * @return void
     */
    public function _throwError($message = null, $substr = array())
    {
        // Do variable substitution in the error message
        $message = strtr($message, $substr);

        // Throw an error exception with the error message
        throw new Exception($message);
    }

    /**
     * Outputs the header.
     *
     * @return string    Returns a string with the output messages.
     */
    private function _outputHeader()
    {
        // Initialize the output variable
        $output = null;

        // Output the application name
        $output .= "===============================================================================\n";
        $output .= print_r($this->_getConfig('deployment.project'), true) . "\n";
        $output .= "===============================================================================\n";
        $output .= "\n";

        // Output the configuration
        if ($this->_getConfig('deployment.debug') == true) {
            $output .= "Configuration:\n";
            $output .= "===============================================================================\n";
            $output .= "- environment: " . print_r($this->_getConfig('environment'), true) . "\n";
            $output .= "- deployment:\n" . print_r($this->_getConfig('deployment'), true);
            $output .= "- application:\n" . print_r($this->_getConfig('application'), true);
            $output .= "- stack:\n" . print_r($this->_getConfig('stack'), true) . "\n";
            $output .= "===============================================================================\n";
            $output .= "\n";
        }

        return $output;
    }

    /**
    * Deploys the application.
    *
    * @see RoboFileDeploy::pathDeploy
    */
    public function deploy() {
        return $this->RoboFileDeploy->pathDeploy();
    }

    /**
    * Undeploys the application.
    *
    * @see RoboFileDeploy::pathUndeploy
    */
    public function undeploy() {
        return $this->RoboFileDeploy->pathUndeploy();
    }

    /**
    * Installs the application on the local environment.
    *
    * @see RoboFileInstall::siteInstall
    */
    public function install() {
        return $this->RoboFileInstall->siteInstall();
    }

    /**
    * Uninstalls the application from the local environment.
    *
    * @see RoboFileInstall::siteUninstall
    */
    public function uninstall() {
        return $this->RoboFileInstall->siteUninstall();
    }

    /**
    * Runs the application in PHP's built in webserver.
    *
    * @see RoboFileExec::phpRunServer
    */
    public function run() {
        return $this->RoboFileExec->phpRunServer();
    }

}
