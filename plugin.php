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

// {{{ Constants
// TODO: Wrap defines in if (check if already defined), or it breaks Jenkins.
// I shouldn't have to define this, but for testing....
define(
    'GOOGLE_ANALYTICS_ERR_PLUGIN_VERSION',
    '1.3-1.0'
);
define(
    'GOOGLE_ANALYTICS_ERR_PLUGIN_DIR',
    dirname(__FILE__)
);
define(
    'GOOGLE_ANALYTICS_ACCOUNT_OPTION',
    'googleanalytics_account_id'
);
// }}}

require_once GOOGLE_ANALYTICS_ERR_PLUGIN_DIR . '/GoogleAnalyticsPlugin.php';

new GoogleAnalyticsPlugin();

/**
 * Show the plugin configuration form.
 */
function googleanalyticserr_config_form()
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

    // TODO: Use HEREDOC or partial form for this.
 
    // Now for some instructions. We're user friendly!
    echo '<p>To find your Google Analytics Account ID, follow these steps:</p>';
    echo '<ol style="list-style: decimal inside;">';
    echo '<li>Create or log into a';
    echo '<a href="https://www.google.com/analytics/" target="_blank">';
    echo 'Google Analytics</a> account;</li>';
    echo '<li> Add a &quot;Website Profile&quot; for this Omeka.net website; </li>';
    echo '<li>Copy the value for Account ID found next to the site URL (starts with &quot;UA-&quot;);</li>';
    echo '<li>Paste it into the text field above and Save Changes.</li>';
    echo '</ol>';
}

/**
 * Show the code on the page, if it's set.
 */
function googleanalyticserr_append_code()
{
    $accountId = get_option(GOOGLE_ANALYTICS_ACCOUNT_OPTION);    
    if (empty($accountId)) {
        return;
    }
    $js = file_get_contents(dirname(__FILE__) . '/snippet.js');
    echo '<script type="text/javascript">' . "\n";
    echo 'var accountId = ' . js_escape($accountId) . ';' . "\n";
    echo $js;
    echo '</script>' . "\n";
}

// TODO: Remove closer (XSS).
?>

