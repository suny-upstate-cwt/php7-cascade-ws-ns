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
<p>A <code>WorkflowEmail</code> object represents a workflow email asset.</p>
<h2>Structure of <code>workflowEmail</code></h2>
<pre>workflowEmail
  id
  name
  parentContainerId
  parentContainerPath
  path
  siteId
  siteName
  subject
  body
</pre>
<h2>WSDL</h2>";
$doc_string .=
    $service->getXMLFragments( array(
        array( "getComplexTypeXMLByName" => "workflowEmail" )
    ) );
return $doc_string;
?>
</description>
<postscript><h2>Test Code</h2><ul><li><a href="https://github.com/wingmingchan/php-cascade-ws-ns-examples/blob/master/asset-class-test-code/workflow_email.php">workflow_definition.php</a></li></ul>
<h2>JSON Dump</h2>
<pre>http://mydomain.edu:1234/api/v1/read/workflowemail/dd1cc5488b7ffea932e15180b524fa3c

{
  "asset":{
    "workflowDefinition":{
        "subject": "A new subject line",
        "body": "A new body",
        "parentContainerId": "dcee71f28b7ffea932e15180ae5fe835",
        "parentContainerPath": "test-container",
        "path": "test-container\/test-email",
        "siteId": "61885ac08b7ffe8377b637e83a86cca5",
        "siteName": "_brisk",
        "name": "test-email",
        "id": "dd1cc5488b7ffea932e15180b524fa3c"
    }
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
class WorkflowEmail extends ContainedAsset
{
    const DEBUG = false;
    const TYPE  = c\T::WORKFLOWEMAIL;
    
    // Placeholder keys
    const PLACEHOLDER_ASSET_NAME                 = "{{ASSET_NAME}}";
    const PLACEHOLDER_ASSET_NAME_LIVE            = "{{ASSET_NAME_LIVE}}";
    const PLACEHOLDER_CURRENT_DATE               = "{{CURRENT_DATE}}";
    const PLACEHOLDER_WORKFLOW_CURRENT_STEP_NAME = "{{WORKFLOW_CURRENT_STEP_NAME}}";
    const PLACEHOLDER_WORKFLOW_DUE_DATE          = "{{WORKFLOW_DUE_DATE}}";
    const PLACEHOLDER_WORKFLOW_EMAIL_RECIPIENT   = "{{WORKFLOW_EMAIL_RECIPIENT}}";
    const PLACEHOLDER_LINK_TO_ASSET              = "{{LINK_TO_ASSET}}";
    const PLACEHOLDER_LINK_TO_WORKFLOW           = "{{LINK_TO_WORKFLOW}}";
    const PLACEHOLDER_LIVE_LINK_TO_ASSET         = "{{LIVE_LINK_TO_ASSET}}";
    const PLACEHOLDER_WORKFLOW_NEXT_STEP_NAME    = "{{WORKFLOW_NEXT_STEP_NAME}}";
    const PLACEHOLDER_WORKFLOW_OWNER             = "{{WORKFLOW_OWNER}}";
    const PLACEHOLDER_WORKFLOW_SITE_LINK         = "{{WORKFLOW_SITE_LINK}}";
    const PLACEHOLDER_WORKFLOW_SITE_NAME         = "{{WORKFLOW_SITE_NAME}}";
    const PLACEHOLDER_WORKFLOW_START_DATE        = "{{WORKFLOW_START_DATE}}";
    const PLACEHOLDER_WORKFLOW_STEP_OWNER        = "{{WORKFLOW_STEP_OWNER}}";
    const PLACEHOLDER_WORKFLOW_NAME              = "{{WORKFLOW_NAME}}";
    
    // Placeholder key descriptions
    const PLACEHOLDER_DESC_ASSET_NAME                 = "Asset Name with Link";
    const PLACEHOLDER_DESC_ASSET_NAME_LIVE            = "Asset Name with Live Link";
    const PLACEHOLDER_DESC_CURRENT_DATE               = "Current Date";
    const PLACEHOLDER_DESC_WORKFLOW_CURRENT_STEP_NAME = "Current Step Name";
    const PLACEHOLDER_DESC_WORKFLOW_DUE_DATE          = "Due Date";
    const PLACEHOLDER_DESC_WORKFLOW_EMAIL_RECIPIENT   = "Email Recipient";
    const PLACEHOLDER_DESC_LINK_TO_ASSET              = "Link to Asset";
    const PLACEHOLDER_DESC_LINK_TO_WORKFLOW           = "Link to the Workflow";
    const PLACEHOLDER_DESC_LIVE_LINK_TO_ASSET         = "Live Link to the Asset";
    const PLACEHOLDER_DESC_WORKFLOW_NEXT_STEP_NAME    = "Next Step Name";
    const PLACEHOLDER_DESC_WORKFLOW_OWNER             = "Owner";
    const PLACEHOLDER_DESC_WORKFLOW_SITE_LINK         = "Site Link";
    const PLACEHOLDER_DESC_WORKFLOW_SITE_NAME         = "Site Name with Link";
    const PLACEHOLDER_DESC_WORKFLOW_START_DATE        = "Start Date";
    const PLACEHOLDER_DESC_WORKFLOW_STEP_OWNER        = "Step Owner";
    const PLACEHOLDER_DESC_WORKFLOW_NAME              = "Workflow Name";
    
/**
<documentation><description><p>The constructor, overriding the parent method to parse the XML definition.</p></description>
<example></example>
<return-type></return-type>
<exception></exception>
</documentation>
*/
    public function __construct( 
        aohs\AssetOperationHandlerService $service, \stdClass $identifier )
    {
        parent::__construct( $service, $identifier );
    }
    
    public function getSubject()
    {
        return $this->getProperty()->subject;
    }
    
    public function getBody()
    {
        return $this->getProperty()->body;
    }
    
    public function setSubject( string $string ) : Asset
    {
        if( trim( $string ) == "" )
        {
            throw new e\EmptyValueException( 
                S_SPAN . c\M::EMPTY_VALUE . E_SPAN );
        }
        
        $this->getProperty()->subject = $string;
        return $this;
    }
    
    public function setBody( string $string ) : Asset
    {
        if( trim( $string ) == "" )
        {
            throw new e\EmptyValueException( 
                S_SPAN . c\M::EMPTY_VALUE . E_SPAN );
        }
        
        $this->getProperty()->body = $string;
        return $this;
    }
    
    public function edit(
        p\Workflow $wf=NULL, 
        WorkflowDefinition $wd=NULL, 
        string $new_workflow_name="", 
        string $comment="",
        bool $exception=true 
    ) : Asset
    {
        $asset                                    = new \stdClass();
        $asset->{ $p = $this->getPropertyName() } = $this->getProperty();
        
        // edit asset
        $service = $this->getService();
        $service->edit( $asset );
        
        if( !$service->isSuccessful() )
        {
            throw new e\EditingFailureException( 
                S_SPAN . c\M::EDIT_WORKFLOW_EMAIL_FAILURE . E_SPAN . $service->getMessage() );
        }
        return $this->reloadProperty();
    }
}
?>
