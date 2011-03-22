<?php

// Constants

// Hooks
add_plugin_hook('public_theme_footer',
                'googleanalyticserr_append_code');
add_plugin_hook('config', 'googleanalyticserr_config');
add_plugin_hook('config_form', 'googleanalyticserr_config_form');

// Save the plugin configuration page.
function googleanalyticserr_config() {
  // Save the message as a plugin option.
  set_option('googleanalyticserr_code',
             trim($_POST['googleanalyticserr_code']));
}

// Show plugin configuration page.
function googleanalyticserr_form()
{
  // Create a form with inputs to collect the Analytics code.
  echo '<div id="googleanalyticserr_form">';
  echo label(array('for' => 'googleanalyticserr_code'),
             'Your Google Analytics code:');
  echo textarea(array('name' => 'googleanalyticserr_code',
                      'rows' => '15',
                      'cols' => '50'),
                get_option('googleanalyticserr_code'));
  echo '</div>';
  echo '<div>To find your Google Analytics code, follow these steps:</div>';
  echo '<ol>';
  echo '<li>log onto your <a href="https://www.google.com/analystics/">Google Analytics</a> account;</li>';
  echo '<li>click "Edit" for the profile you want to use;</li>';
  echo '<li>click "Check Status";</li>';
  echo '<li>Follow the instructions, and copy and paste the text labelled "Paste this code on your site"; and</li>';
  echo '<li>Paste the code into the box above.</li>';
  echo '</ol>';
}

// Show the code on the page.
function googleanalyticserr_append_code() {
  echo '<p>Google Analytics stuff goes here.</p>';
}


// Filters

// Helper Functions

// Includes

// Baseline Code


?>

