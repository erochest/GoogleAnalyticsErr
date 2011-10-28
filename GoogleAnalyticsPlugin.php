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
        self::addHooksAndFilters();
    }

    /**
     * This adds the necessary hooks and filters.
     *
     * @return void
     * @author Eric Rochester <erochest@virginia.edu>
     **/
    public function addHooksAndFilters()
    {
        $broker = get_plugin_broker();
        foreach (self::$_hooks as $hookName) {
            $fnName = Inflector::variablize($hookName);
            $broker->addHook($hookName, array($this, $fnName), 'GoogleAnalytics');
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
        echo '<div id="googleanalyticserr_form">';
        echo __v()->formLabel(
            GOOGLE_ANALYTICS_ACCOUNT_OPTION,
            'Google Analytics Account ID:'
        );
        echo __v()->formText(
            GOOGLE_ANALYTICS_ACCOUNT_OPTION,
            get_option(GOOGLE_ANALYTICS_ACCOUNT_OPTION),
            array('rows' => '15', 'cols' => '80')
        );
        echo '</div>';

        // Now for some instructions. We're user friendly!
        echo <<<EOF
<p>To find your Google Analytics Account ID, follow these steps:</p>
<ol style="list-style: decimal inside;">
<li>Create or log into a 
<a href="https://www.google.com/analytics/" target="_blank">
Google Analytics</a> account;</li>
<li> Add a &quot;Website Profile&quot; for this Omeka.net website; </li>
<li>Copy the value for Account ID found next to the site URL (starts with &quot;UA-&quot;);</li>
<li>Paste it into the text field above and Save Changes.</li>
</ol>
EOF;
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
        $accountId = get_option(GOOGLE_ANALYTICS_ACCOUNT_OPTION);    
        if (empty($accountId)) {
            return;
        }
        $jsAccountId = js_escape($accountId);
        $js = file_get_contents(dirname(__FILE__) . '/snippet.js');
        echo <<<EOF
<script type="text/javascript">
var accountId = $jsAccountId;
$js;
</script>
EOF;
    }
}

