<?php
/**
 * GoogleAnalyticsErr Omeka plugin.
 *
 * This plug-in allows you to paste in the JavaScript for Google Analytics and 
 * outputs it on the bottom of every public page.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by
 * applicable law or agreed to in writing, software distributed under the
 * License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS
 * OF ANY KIND, either express or implied. See the License for the specific
 * language governing permissions and limitations under the License.
 *
 * @package omeka
 * @subpackage GoogleAnalyticsErr
 * @author Eric Rochester (erochest@gmail.com)
 * @copyright 2011
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version 0.1
 * @link https://github.com/erochest/GoogleAnalyticsErr
 *
 */

/**
 * This is the hook and filter class for the plugin.
 **/
class GoogleAnalyticsPlugin
{

    /**
     * This is a list of the hooks this implements.
     *
     * @var array of string
     **/
    private static $_hooks = array(
        'install',
        'uninstall',
        'public_theme_footer',
        'config',
        'config_form'
    );

    /**
     * The database.
     *
     * @var object
     **/
    private $_db;

    public function __construct()
    {
        $this->_db = get_db();
    }

    /**
     * This adds the necessary hooks and filters.
     *
     * @return void
     * @author Eric Rochester <erochest@virginia.edu>
     **/
    public function addHooksAndFilters()
    {
        foreach (self::$_hooks as $hookName) {
            $fnName = Inflector::variablize($hookName);
            add_plugin_hook($hookName, array($this, $fnName));
        }
        // No filters....
    }

    /**
     * This installs the plugin by setting the version option.
     *
     * @return void
     * @author Eric Rochester <erochest@virginia.edu>
     **/
    public function install()
    {
        set_option(
            'googleanalyticserr_version',
            GOOGLE_ANALYTICS_ERR_PLUGIN_VERSION
        );
    }

    /**
     * This uninstalls the plugin by removing all options.
     *
     * @return void
     * @author Eric Rochester <erochest@virginia.edu>
     **/
    public function uninstall()
    {
        delete_option('googleanalyticserr_version');
        delete_option(GOOGLE_ANALYTICS_ACCOUNT_OPTION);
    }

    /**
     * This processes the configuration information from the form.
     *
     * @return void
     * @author Eric Rochester <erochest@virginia.edu>
     **/
    public function config()
    {
        set_option(
            GOOGLE_ANALYTICS_ACCOUNT_OPTION,
            trim($_POST[GOOGLE_ANALYTICS_ACCOUNT_OPTION])
        );
    }

    /**
     * This shows the plugin configuration form.
     *
     * @return void
     * @author Eric Rochester <erochest@virginia.edu>
     **/
    public function configForm()
    {
    }

    /**
     * This adds the Google Analytics code to the footer of the page, if it's 
     * set.
     *
     * @return void
     * @author Eric Rochester <erochest@virginia.edu>
     **/
    public function publicThemeFooter()
    {
    }
}

