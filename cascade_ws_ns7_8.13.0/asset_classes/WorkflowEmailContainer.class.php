<?php 
/**
  * Author: German Drulyk
  * Copyright (c) 2019 German Drulyk <drulykg@upstate.edu>
  * MIT Licensed
  * Modification history:
  * 7/10/2019 Class created
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
<p>A <code>WorkflowEmailContainer</code> object represents a workflow email container asset. This class is a sub-class of <a href=\"http://www.upstate.edu/web-services/api/asset-classes/container.php\"><code>Container</code></a>.</p>
<h2>Structure of <code>workflowEmailContainer</code></h2>
<pre>SOAP:
workflowEmailContainer
  id
  name
  parentContainerId
  parentContainerPath
  path
  siteId
  siteName
  children
    child
      id
      path
        path
        siteId
        siteName
      type
      recycled

REST:
workflowEmailContainer
  children (array)
    stdClass
      id
      path
        path
        siteId
      type
      recycled
  parentContainerId
  parentContainerPath
  path
  siteId
  siteName
  name
  id
</pre>
<h2>WSDL</h2>";
$doc_string .=
    $service->getXMLFragments( array(
        array( "getComplexTypeXMLByName" => "workflowEmailContainer" ),
        array( "getComplexTypeXMLByName" => "container-children" ),
        array( "getComplexTypeXMLByName" => "identifier" ),
        array( "getComplexTypeXMLByName" => "path" ),
    ) );
return $doc_string;
?>
</description>
<postscript><h2>Test Code</h2><ul><li><a href="https://github.com/suny-upstate-cwt/php-cascade-ws-ns-examples/blob/master/asset-class-test-code/workflow_email_container.php">workflow_email_container.php</a></li></ul>
<h2>JSON Dump</h2>
<pre>http://mydomain.edu:1234/api/v1/read/workflowemailcontainer/dcee71f28b7ffea932e15180ae5fe835

{
    "asset":{
        "children": [],
        "parentContainerId": "dce582188b7ffea932e1518086e8d2b1",
        "parentContainerPath": "\/",
        "path": "test-container",
        "siteId": "61885ac08b7ffe8377b637e83a86cca5",
        "siteName": "_brisk",
        "name": "test-container",
        "id": "dcee71f28b7ffea932e15180ae5fe835"
    },
    "authentication":{
        "username":"user",
        "password":"secret"
    }
}
</pre>
</postscript>
</documentation>
*/
class WorkflowEmailContainer extends Container
{
    const TYPE = c\T::WORKFLOWEMAILCONTAINER;

/**
<documentation><description><p>The constructor.</p></description>
</documentation>
*/
    public function __construct( 
        aohs\AssetOperationHandlerService $service, \stdClass $identifier )
    {
        parent::__construct( $service, $identifier );
    }
}
?>