<?php

require_once '../GoogleAnalyticsPlugin.php';
require_once HELPERS;

/**
 * Since this plugin is really a bunch of hooks with little functionality, this does all the testing.
 **/
class GoogleAnalytics_Test_AppTestCase extends Omeka_Test_AppTestCase
{

    /**
     * This is a flag indicating whether the plugin was installed before each 
     * test.
     *
     * @var bool
     **/
    var $_installed;

    /**
     * This is the plugin object.
     *
     * @var GoogleAnalyticsPlugin
     **/
    var $_plugin;

    /**
     * If the plugin was already installed, this is the value of the previous 
     * code.
     *
     * @var string
     **/
    var $_previousCode;
    
    public function _setUpPlugin()
    {
        $plugin_broker = get_plugin_broker();
        $this->_addPluginHooksAndFilters($plugin_broker, 'GoogleAnalytics');
    }

    /**
     * Load the plugin hooks and filters.
     *
     * @return void
     * @author Eric Rochester <erochest@virginia.edu>
     **/
    public function _addPluginHooksAndFilters($plugin_broker, $plugin_name)
    {
        $plugin_broker->setCurrentPluginDirName($plugin_name);
        $this->_plugin = new GoogleAnalyticsPlugin();
    }

    /**
     * This checks that the plugin is installed. If not, it installs it.
     *
     * Either way, it sets the $this->_installed parameter.
     *
     * @return void
     * @author Eric Rochester <erochest@virginia.edu>
     **/
    function _checkInstall()
    {
        if (!get_option('googleanalyticserr_version')) {
            $this->_installed = false;
            $this->_plugin->install();
            $this->_previousCode = null;
        } else {
            $this->_installed = true;
            $this->_previousCode = get_option(GOOGLE_ANALYTICS_ACCOUNT_OPTION);
        }
    }

    /**
     * This checks _installed and removes the plugin if it wasn't previously 
     * installed.
     *
     * @return void
     * @author Eric Rochester <erochest@virginia.edu>
     **/
    function _checkUninstall()
    {
        if ($this->_installed) {
            if ($this->_previousCode == null) {
                delete_option(GOOGLE_ANALYTICS_ACCOUNT_OPTION);
            } else {
                set_option(GOOGLE_ANALYTICS_ACCOUNT_OPTION,
                           $this->_previousCode);
            }
        } else {
            $this->_plugin->uninstall();
        }
    }

    /**
     * This sets up for all tests.
     *
     * @return void
     * @author Eric Rochester <erochest@virginia.edu>
     **/
    public function setUp()
    {
        parent::setUp();
        $pluginHelper = new Omeka_Test_Helper_Plugin;
        $pluginHelper->setUp('GoogleAnalytics');
        $this->_plugin = new GoogleAnalyticsPlugin;
        $this->_checkInstall();
    }

    /**
     * This tears down for all tests.
     *
     * @return void
     * @author Eric Rochester <erochest@virginia.edu>
     **/
    public function tearDown()
    {
        $this->_checkUninstall();
    }

    /**
     * This tests installation.
     *
     * Currently, this just makes sure that the version is in the database.
     *
     * @return void
     * @author Eric Rochester <erochest@virginia.edu>
     **/
    public function testInstall()
    {
        $this->assertEquals('1.3-1.0', get_option('googleanalyticserr_version'));
    }

    /**
     * This tests uninstalling the plugin.
     *
     * Currently, this just makes sure that the version is removed from the 
     * database.
     *
     * @return void
     * @author Eric Rochester <erochest@virginia.edu>
     **/
    public function testUninstall()
    {
        $this->_plugin->uninstall();
        $this->assertNull(get_option('googleanalyticserr_version'));
        $this->assertNull(get_option(GOOGLE_ANALYTICS_ACCOUNT_OPTION));
    }

    /**
     * This tests that the configuration is handled properly.
     *
     * @return void
     * @author Eric Rochester <erochest@virginia.edu>
     **/
    public function testConfig()
    {
        $_POST[GOOGLE_ANALYTICS_ACCOUNT_OPTION] = 'TestCode';
        $this->_plugin->config();
        $this->assertEquals("TestCode", get_option(GOOGLE_ANALYTICS_ACCOUNT_OPTION));
    }

    /**
     * This tests that the config form is asking for the proper options.
     *
     * @return void
     * @author Eric Rochester <erochest@virginia.edu>
     **/
    public function testConfigForm()
    {
        ob_start();
        $this->_plugin->configForm();
        $text = ob_get_contents();
        ob_clean();

        $this->assertInternalType('int', strpos($text, 'Google Analytics Account ID:'));
        $this->assertInternalType('int', strpos($text, 'googleanalytics_account_id'));
    }

    /**
     * This tests that the account ID (and hopefully Google Analytics) code 
     * gets added to the page.
     *
     * @return void
     * @author Eric Rochester <erochest@virginia.edu>
     **/
    public function testAppendCode()
    {
        $_POST[GOOGLE_ANALYTICS_ACCOUNT_OPTION] = 'TestCode';
        $this->_plugin->config();

        /*
        $this->dispatch('/');
        $this->assertQueryContentRegex('script', '/var accountId = [\'"]TestCode[\'"];/');
         */

        ob_start();
        $this->_plugin->publicThemeFooter();
        $text = ob_get_contents();
        ob_end_clean();

        $this->assertEquals(1, preg_match('/var accountId = [\'"]TestCode[\'"];/', $text));
    }
}


