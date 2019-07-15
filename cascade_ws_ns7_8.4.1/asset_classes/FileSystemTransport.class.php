<?php 
/**
  * Author: Wing Ming Chan
  * Copyright (c) 2017 Wing Ming Chan <chanw@upstate.edu>
  * MIT Licensed
  * Modification history:
  * 6/23/2017 Replaced static WSDL code with call to getXMLFragments.
  * 6/13/2017 Added WSDL.
  * 1/12/2017 Added JSON dump.
  * 5/28/2015 Added namespaces.
 */
namespace cascade_ws_asset;

use cascade_ws_constants as c;
use cascade_ws_AOHS      as aohs;
use cascade_ws_utility   as u;
use cascade_ws_exception as e;
use cascade_ws_property  as p;

/**
<documentation>
<description>
<?php global $service;
$doc_string = "<h2>Introduction</h2>
<p>A <code>FileSystemTransport</code> object represents a file system transport asset. This class is a sub-class of <a href=\"/cascade-admin/web-services/api/asset-classes/transport.php\"><code>Transport</code></a>.</p>
<h2>Structure of <code>fileSystemTransport</code></h2>
<pre>fileSystemTransport
  id
  name
  parentContainerId
  parentContainerPath
  path
  siteId
  siteName
  directory
</pre>
<h2>WSDL</h2>";
$doc_string .=
    $service->getXMLFragments( array(
        array( "getComplexTypeXMLByName" => "fileSystemTransport" ),
    ) );
return $doc_string;
?>
</description>
<postscript><h2>Test Code</h2><ul><li><a href="https://github.com/suny-upstate-cwt/php-cascade-ws-ns-examples/blob/master/asset-class-test-code/fs_transport.php">fs_transport.php</a></li></ul>
<h2>JSON Dump</h2>
<pre>{ "asset":{
  "fileSystemTransport":{
    "directory":"about",
    "parentContainerId":"042b48d78b7ffe8339ce5d13f348500d",
    "parentContainerPath":"Transport Container",
    "path":"Transport Container/Test File",
    "siteId":"1f2172088b7ffe834c5fe91e9596d028",
    "siteName":"cascade-admin-webapp",
    "name":"Test File",
    "id":"08378e518b7ffe8339ce5d1372331a0f"}},
  "success":true
}
</pre>
</postscript>
</documentation>
*/
class FileSystemTransport extends Transport
{
    const DEBUG = false;
    const TYPE  = c\T::FSTRANSPORT;
    
/**
<documentation><description><p>The constructor.</p></description>
</documentation>
*/
    public function __construct( 
        aohs\AssetOperationHandlerService $service, \stdClass $identifier )
    {
        parent::__construct( $service, $identifier );
    }

/**
<documentation><description><p>Returns <code>directory</code>.</p></description>
<example>echo $t->getDirectory(), BR;</example>
<return-type>string</return-type>
<exception></exception>
</documentation>
*/
    public function getDirectory() : string
    {
        return $this->getProperty()->directory;
    }
    
/**
<documentation><description><p>Sets <code>directory</code> and returns the calling object.</p></description>
<example>$t->setDirectory( 'about' )->edit();</example>
<return-type>Asset</return-type>
<exception>EmptyValueException</exception>
</documentation>
*/
    public function setDirectory( string $d ) : Asset
    {
        if( trim( $d ) == "" )
            throw new e\EmptyValueException( 
                S_SPAN . c\M::EMPTY_DIRECTORY . E_SPAN );
            
        $this->getProperty()->directory = $d;
        return $this;
    }
}
?>