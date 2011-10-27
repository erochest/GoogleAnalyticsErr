<?php

/**
 * This points the test runner to all tests.
 **/
class GoogleAnalytics_AllTests extends PHPUnit_Framework_TestSuite
{
    
    public static function suite()
    {
        $suite = new GoogleAnalytics_AllTests('GoogleAnalytics Tests');
        $collector = new PHPUnit_Runner_IncludePathTestCollector(
            array(
                dirname(__FILE__) . '/integration',
                dirname(__FILE__) . '/unit'
            )
        );

        $suite->addTestFiles($collector->collectTests());
        return $suite;
    }
}

