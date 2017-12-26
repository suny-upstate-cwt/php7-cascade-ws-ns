<?php
/*
This authentication file is used to switch between SOAP and REST on the one hand,
and between the two servers on the other. Since the class name
AssetOperationHandlerService has been embedded in the library,
both SOAP and REST use the same class name. Therefore, the library files of
the two classes must be renamed accordingly. When SOAP is used, the file
containing SOAP code must be renamed to AssetOperationHandlerService.class.php,
and the other file containing REST code should be renamed to
AssetOperationHandlerServiceRest.class.php. When REST is used, the file
containing SOAP code must be renamed to AssetOperationHandlerServiceSoap.class.php,
and the other file containing REST code should be renamed to
AssetOperationHandlerService.class.php.
*/
$soap   = false;
$webapp = true;

$folderPath = "/Applications/MAMP/bin/php/php_include/cascade_ws_ns7/";
$fileName   = "AssetOperationHandlerService.class.php";

if( is_dir( $folderPath ) && $handle = opendir( $folderPath ) )
{
    if( file_exists( $folderPath . $fileName ) && is_file( $folderPath . $fileName ) )
    {
        if( $soap )
        {
            if( file_exists( $folderPath .
                "AssetOperationHandlerServiceSoap.class.php" ) && 
                is_file( $folderPath . "AssetOperationHandlerServiceSoap.class.php" ) )
            {
                rename( $folderPath . $fileName,
                    $folderPath . "AssetOperationHandlerServiceRest.class.php" );
                
                rename( $folderPath . "AssetOperationHandlerServiceSoap.class.php",
                    $folderPath . $fileName );
            }
        }
        else
        {
            if( file_exists( $folderPath .
                "AssetOperationHandlerServiceRest.class.php" ) && 
                is_file( $folderPath . "AssetOperationHandlerServiceRest.class.php" ) )
            {
                rename( $folderPath . $fileName,
                    $folderPath . "AssetOperationHandlerServiceSoap.class.php" );

                rename( $folderPath . "AssetOperationHandlerServiceRest.class.php",
                    $folderPath . $fileName );
            }
        }
    }
}
// pick the correct authentication file pointing to correct server.
if( $soap && $webapp )
    require_once( "auth_tutorial7.php" );
elseif( $soap && !$webapp )
    require_once( "auth_chanw.php" );
elseif( !$soap && $webapp )
    require_once( "auth_rest_webapp.php" );
elseif( !$soap && !$webapp )
    require_once( "auth_rest_web.php" );
?>