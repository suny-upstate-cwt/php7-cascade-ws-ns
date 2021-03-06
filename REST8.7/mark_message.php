<?php
require_once( 'auth_rest_webapp.php' );

use cascade_ws_constants as c;
use cascade_ws_AOHS      as aohs;
use cascade_ws_utility   as u;

try
{
    $type = "message";
    $id   = "6e8c72538b7ffe833b19adb8d79fa0bc";
    // read by default
    //u\DebugUtility::dump( $service->markMessage( $service->createId( $type, $id ) ) );
    u\DebugUtility::dump(
        $service->markMessage( $service->createId( $type, $id ), "unread" ) );
}
catch( \Exception $e ) 
{
    echo S_PRE . $e . E_PRE;
}
catch( \Error $er )
{
    echo S_PRE . $er . E_PRE;
}
?>