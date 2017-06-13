<?php
/**
  * Author: Wing Ming Chan
  * Copyright (c) 2017 Wing Ming Chan <chanw@upstate.edu>
  * MIT Licensed
  * Modification history:
  * 6/13/2017 Added WSDL.
  * 12/27/2016 Changed return type of getSiteName to mixed.
  * 12/9/2016 Added empty path in reloadProperty for Destination.
  * 11/11/2016 Added default value for $type to getAudits.
  * 9/29/2016 Added text color to $service->getMessage().
  * 8/30/2016 Added XML documentation.
  * 4/13/2016 Added more initialization of stdClass objects.
  * 3/8/2016 Added get_class to dump.
  * 2/11/2016 Added more code to constructor so getIdentifier can return more info.
  * 9/29/2015 Changed line 334, using isset, per Mark Nokes's request.
  * 5/28/2015 Added namespaces.
  * 9/9/2014 Added exception in copy.
  * 8/14/2014 Started using style in error messages.
  * 8/1/2014 Added getAudits, but unable to test with user, group, role.
  * 7/1/2014 Added copy.
 */

/**
 * Abstract Asset class, inherited by all classes representing assets
 *
 * @link http://www.upstate.edu/cascade-admin/web-services/api/asset-classes/  asset.php
 */
namespace cascade_ws_asset;

use cascade_ws_constants as c;
use cascade_ws_AOHS as aohs;
use cascade_ws_utility as u;
use cascade_ws_exception as e;
use cascade_ws_property as p;

/**
<documentation>
<description><h2>Introduction</h2>
<p>The <code>Asset</code> class is the ancestor of all other asset classes.
It is an abstract class and contains implementation of all methods shared by its descendants.</p>
<h2>WSDL</h2>
<pre>&lt;complexType name="entity-type">
  &lt;sequence>
    &lt;element maxOccurs="1" minOccurs="1" name="name" type="impl:entityTypeString"/>
  &lt;/sequence>
&lt;/complexType>

&lt;simpleType name="entityTypeString">
  &lt;restriction base="xsd:string">
    &lt;enumeration value="assetfactory"/>
    &lt;enumeration value="assetfactorycontainer"/>
    &lt;enumeration value="block"/>
    &lt;enumeration value="block_FEED"/>
    &lt;enumeration value="block_INDEX"/>
    &lt;enumeration value="block_TEXT"/>
    &lt;enumeration value="block_XHTML_DATADEFINITION"/>
    &lt;enumeration value="block_XML"/>
    &lt;enumeration value="block_TWITTER_FEED"/>
    &lt;enumeration value="connectorcontainer"/>
    &lt;enumeration value="twitterconnector"/>
    &lt;enumeration value="facebookconnector"/>
    &lt;enumeration value="wordpressconnector"/>
    &lt;enumeration value="googleanalyticsconnector"/>
    &lt;enumeration value="contenttype"/>
    &lt;enumeration value="contenttypecontainer"/>
    &lt;enumeration value="destination"/>
    &lt;enumeration value="editorconfiguration"/>
    &lt;enumeration value="file"/>
    &lt;enumeration value="folder"/>
    &lt;enumeration value="group"/>
    &lt;enumeration value="message"/>
    &lt;enumeration value="metadataset"/>
    &lt;enumeration value="metadatasetcontainer"/>
    &lt;enumeration value="page"/>
    &lt;enumeration value="pageconfigurationset"/>
    &lt;enumeration value="pageconfiguration"/>
    &lt;enumeration value="pageregion"/>
    &lt;enumeration value="pageconfigurationsetcontainer"/>
    &lt;enumeration value="publishset"/>
    &lt;enumeration value="publishsetcontainer"/>
    &lt;enumeration value="reference"/>
    &lt;enumeration value="role"/>
    &lt;enumeration value="datadefinition"/>
    &lt;enumeration value="datadefinitioncontainer"/>
    &lt;enumeration value="format"/>
    &lt;enumeration value="format_XSLT"/>
    &lt;enumeration value="format_SCRIPT"/>
    &lt;enumeration value="site"/>
    &lt;enumeration value="sitedestinationcontainer"/>
    &lt;enumeration value="symlink"/>
    &lt;enumeration value="target"/>
    &lt;enumeration value="template"/>
    &lt;enumeration value="transport"/>
    &lt;enumeration value="transport_fs"/>
    &lt;enumeration value="transport_ftp"/>
    &lt;enumeration value="transport_db"/>
    &lt;enumeration value="transportcontainer"/>
    &lt;enumeration value="user"/>
    &lt;enumeration value="workflow"/>
    &lt;enumeration value="workflowdefinition"/>
    &lt;enumeration value="workflowdefinitioncontainer"/>
  &lt;/restriction>
&lt;/simpleType>

&lt;complexType name="asset">
  &lt;sequence>
    &lt;element maxOccurs="1" minOccurs="0" name="workflowConfiguration" type="impl:workflow-configuration"/>
    &lt;choice>
      &lt;element maxOccurs="1" minOccurs="1" name="feedBlock" nillable="true" type="impl:feedBlock"/>
      &lt;element maxOccurs="1" minOccurs="1" name="indexBlock" nillable="true" type="impl:indexBlock"/>
      &lt;element maxOccurs="1" minOccurs="1" name="textBlock" nillable="true" type="impl:textBlock"/>
      &lt;element maxOccurs="1" minOccurs="1" name="xhtmlDataDefinitionBlock" nillable="true" type="impl:xhtmlDataDefinitionBlock"/>
      &lt;element maxOccurs="1" minOccurs="1" name="xmlBlock" nillable="true" type="impl:xmlBlock"/>
      &lt;element maxOccurs="1" minOccurs="1" name="file" nillable="true" type="impl:file"/>
      &lt;element maxOccurs="1" minOccurs="1" name="folder" nillable="true" type="impl:folder"/>
      &lt;element maxOccurs="1" minOccurs="1" name="page" nillable="true" type="impl:page"/>
      &lt;element maxOccurs="1" minOccurs="1" name="reference" nillable="true" type="impl:reference"/>
      &lt;element maxOccurs="1" minOccurs="1" name="xsltFormat" nillable="true" type="impl:xsltFormat"/>
      &lt;element maxOccurs="1" minOccurs="1" name="scriptFormat" nillable="true" type="impl:scriptFormat"/>
      &lt;element maxOccurs="1" minOccurs="1" name="symlink" nillable="true" type="impl:symlink"/>
      &lt;element maxOccurs="1" minOccurs="1" name="template" nillable="true" type="impl:template"/>
      &lt;element maxOccurs="1" minOccurs="1" name="user" nillable="true" type="impl:user"/>
      &lt;element maxOccurs="1" minOccurs="1" name="group" nillable="true" type="impl:group"/>
      &lt;element maxOccurs="1" minOccurs="1" name="role" nillable="true" type="impl:role"/>
      &lt;element maxOccurs="1" minOccurs="1" name="assetFactory" nillable="true" type="impl:assetFactory"/>
      &lt;element maxOccurs="1" minOccurs="1" name="assetFactoryContainer" nillable="true" type="impl:assetFactoryContainer"/>
      &lt;element maxOccurs="1" minOccurs="1" name="contentType" nillable="true" type="impl:contentType"/>
      &lt;element maxOccurs="1" minOccurs="1" name="contentTypeContainer" nillable="true" type="impl:contentTypeContainer"/>
      &lt;element maxOccurs="1" minOccurs="1" name="connectorContainer" nillable="true" type="impl:connectorContainer"/>
      &lt;element maxOccurs="1" minOccurs="1" name="twitterConnector" nillable="true" type="impl:twitterConnector"/>
      &lt;element maxOccurs="1" minOccurs="1" name="facebookConnector" nillable="true" type="impl:facebookConnector"/>
      &lt;element maxOccurs="1" minOccurs="1" name="wordPressConnector" nillable="true" type="impl:wordPressConnector"/>
      &lt;element maxOccurs="1" minOccurs="1" name="googleAnalyticsConnector" nillable="true" type="impl:googleAnalyticsConnector"/>
      &lt;element maxOccurs="1" minOccurs="1" name="pageConfigurationSet" nillable="true" type="impl:pageConfigurationSet"/>
      &lt;element maxOccurs="1" minOccurs="1" name="pageConfigurationSetContainer" nillable="true" type="impl:pageConfigurationSetContainer"/>
      &lt;element maxOccurs="1" minOccurs="1" name="dataDefinition" nillable="true" type="impl:dataDefinition"/>
      &lt;element maxOccurs="1" minOccurs="1" name="dataDefinitionContainer" nillable="true" type="impl:dataDefinitionContainer"/>
      &lt;element maxOccurs="1" minOccurs="1" name="metadataSet" nillable="true" type="impl:metadataSet"/>
      &lt;element maxOccurs="1" minOccurs="1" name="metadataSetContainer" nillable="true" type="impl:metadataSetContainer"/>
      &lt;element maxOccurs="1" minOccurs="1" name="publishSet" nillable="true" type="impl:publishSet"/>
      &lt;element maxOccurs="1" minOccurs="1" name="publishSetContainer" nillable="true" type="impl:publishSetContainer"/>
      &lt;element maxOccurs="1" minOccurs="1" name="target" nillable="true" type="impl:target"/>
      &lt;element maxOccurs="1" minOccurs="1" name="siteDestinationContainer" nillable="true" type="impl:siteDestinationContainer"/>
      &lt;element maxOccurs="1" minOccurs="1" name="destination" nillable="true" type="impl:destination"/>
      &lt;element maxOccurs="1" minOccurs="1" name="fileSystemTransport" nillable="true" type="impl:fileSystemTransport"/>
      &lt;element maxOccurs="1" minOccurs="1" name="ftpTransport" nillable="true" type="impl:ftpTransport"/>
      &lt;element maxOccurs="1" minOccurs="1" name="databaseTransport" nillable="true" type="impl:databaseTransport"/>
      &lt;element maxOccurs="1" minOccurs="1" name="transportContainer" nillable="true" type="impl:transportContainer"/>
      &lt;element maxOccurs="1" minOccurs="1" name="workflowDefinition" nillable="true" type="impl:workflowDefinition"/>
      &lt;element maxOccurs="1" minOccurs="1" name="workflowDefinitionContainer" nillable="true" type="impl:workflowDefinitionContainer"/>
      &lt;element maxOccurs="1" minOccurs="1" name="twitterFeedBlock" nillable="true" type="impl:twitterFeedBlock"/>
      &lt;element maxOccurs="1" minOccurs="1" name="site" nillable="true" type="impl:site"/>
      &lt;element maxOccurs="1" minOccurs="1" name="editorConfiguration" nillable="true" type="impl:editorConfiguration"/>
    &lt;/choice>
  &lt;/sequence>
&lt;/complexType>

&lt;complexType name="workflow-configuration">
  &lt;sequence>
    &lt;element maxOccurs="1" minOccurs="1" name="workflowName" type="xsd:string"/>
    &lt;element maxOccurs="1" minOccurs="0" name="workflowDefinitionId" type="xsd:string"/>
    &lt;element maxOccurs="1" minOccurs="0" name="workflowDefinitionPath" type="xsd:string"/>
    &lt;element maxOccurs="1" minOccurs="1" name="workflowComments" type="xsd:string"/>
    &lt;element maxOccurs="1" minOccurs="0" name="workflowStepConfigurations" type="impl:workflow-step-configurations"/>
    &lt;element maxOccurs="1" minOccurs="0" name="endDate" type="xsd:dateTime"/>
  &lt;/sequence>
&lt;/complexType>

&lt;complexType name="workflow-step-configurations">
  &lt;sequence>
    &lt;element maxOccurs="unbounded" name="workflowStepConfiguration" type="impl:workflow-step-configuration"/>
  &lt;/sequence>
&lt;/complexType>

&lt;complexType name="workflow-step-configuration">
  &lt;sequence>
    &lt;element maxOccurs="1" minOccurs="1" name="stepIdentifier" type="xsd:string"/>
    &lt;element maxOccurs="1" minOccurs="1" name="stepAssignment" type="xsd:string"/>
  &lt;/sequence>
&lt;/complexType>

&lt;complexType name="base-asset">
  &lt;sequence>
    &lt;element maxOccurs="1" minOccurs="0" name="id" type="xsd:string"/>
  &lt;/sequence>
&lt;/complexType>

&lt;complexType name="named-asset">
  &lt;complexContent>
    &lt;extension base="impl:base-asset">
      &lt;sequence>
        &lt;element maxOccurs="1" minOccurs="1" name="name" type="xsd:string"/>
      &lt;/sequence>
    &lt;/extension>
  &lt;/complexContent>
&lt;/complexType>

&lt;complexType name="expiring-asset">
  &lt;complexContent>
    &lt;extension base="impl:dublin-aware-asset">
      &lt;sequence>
        &lt;element maxOccurs="1" minOccurs="0" name="expirationFolderId" type="xsd:string"/>
        &lt;element maxOccurs="1" minOccurs="0" name="expirationFolderPath" type="xsd:string"/>
        &lt;element maxOccurs="1" minOccurs="0" name="expirationFolderRecycled" type="xsd:boolean"/>
      &lt;/sequence>
    &lt;/extension>
  &lt;/complexContent>
&lt;/complexType>

&lt;complexType name="dublin-aware-asset">
  &lt;complexContent>
    &lt;extension base="impl:folder-contained-asset">
      &lt;sequence>
        &lt;element maxOccurs="1" minOccurs="0" name="metadata" type="impl:metadata"/>
        &lt;element maxOccurs="1" minOccurs="0" name="metadataSetId" type="xsd:string"/>
        &lt;element maxOccurs="1" minOccurs="0" name="metadataSetPath" type="xsd:string"/>
      &lt;/sequence>
    &lt;/extension>
  &lt;/complexContent>
&lt;/complexType>

&lt;complexType name="publishable-asset">
  &lt;complexContent>
    &lt;extension base="impl:expiring-asset">
      &lt;sequence>
        &lt;element maxOccurs="1" minOccurs="0" name="shouldBePublished" type="xsd:boolean"/>
        &lt;element maxOccurs="1" minOccurs="0" name="shouldBeIndexed" type="xsd:boolean"/>
        &lt;element maxOccurs="1" minOccurs="0" name="lastPublishedDate" nillable="true" type="xsd:dateTime"/>
        &lt;element maxOccurs="1" minOccurs="0" name="lastPublishedBy" nillable="true" type="xsd:string"/>
      &lt;/sequence>
    &lt;/extension>
  &lt;/complexContent>
&lt;/complexType>
</pre>
</description>
<postscript><h2>Test Code</h2><ul><li><a href="https://github.com/wingmingchan/php-cascade-ws-ns-examples/blob/master/asset-class-test-code/asset.php">asset.php</a></li></ul></postscript>
</documentation>
*/
abstract class Asset
{
    const DEBUG      = false;
    const DUMP       = false;
    const NAME_SPACE = "cascade_ws_asset";
    
/**
<documentation><description><p>The constructor. Since <code>Asset</code> is an abstract class, the constructor cannot be called.</p></description>
<exception>NullServiceException, NullIdentifierException, NullAssetException</exception>
</documentation>
*/
    protected function __construct(
        aohs\AssetOperationHandlerService $service, \stdClass $identifier )
    {
        if( $service == NULL )
            throw new e\NullServiceException( c\M::NULL_SERVICE );
            
        if( $identifier == NULL )
            throw new e\NullIdentifierException( c\M::NULL_IDENTIFIER );
        
        if( self::DEBUG && self::DUMP ) { u\DebugUtility::dump( $identifier ); }
        
        // get the property
        $property = $service->retrieve( 
            $identifier, c\T::$type_property_name_map[ $identifier->type ] );
            
        if( $property == NULL )
        {
            if( isset( $identifier->id ) )
                $id = $identifier->id;

            if( isset( $identifier->path ) )
            {
                $path = $identifier->path->path;
                
                if( isset( $identifier->path->siteName ) )
                    $site_name = $identifier->path->siteName;
            }
            
            if( !isset( $id ) )
                $id = $path;
            
            throw new e\NullAssetException(
                S_SPAN . "The " . 
                c\T::$type_property_name_map[ $identifier->type ] . 
                " cannot be retrieved. ID/Path: " . $id . ". " .
                ( isset( $site_name ) ? "Site: " . $site_name . ". "  : "" ) . E_SPAN .
                BR . S_SPAN . $service->getMessage() . E_SPAN );
        }
            
        // store information
        $this->service       = $service;
        $this->identifier    = $identifier; //stdClass
        $this->type          = $identifier->type;
        $this->property_name = c\T::$type_property_name_map[ $this->type ];
        $this->property      = $property;
        
        if( isset( $property->id ) )
        {
            $this->id            = $property->id;
            
            if( !isset( $this->identifier->id ) )
                $this->identifier->id = $this->id;
        }
        
        if( isset( $property->name ) )
            $this->name          = $property->name;
            
        if( isset( $property->path ) )
        {
            $this->path          = $property->path;
            
            if( !isset( $this->identifier->path ) )
            {
                $this->identifier->path = new \stdClass();
                $this->identifier->path->path = $this->path;
            }
        }
        
        if( isset( $property->siteId ) )
        {
            $this->site_id       = $property->siteId;
            
            if( !isset( $this->identifier->path ) )
                $this->identifier->path = new \stdClass();
                
            $this->identifier->path->siteId = $this->site_id;
        }
        
        if( isset( $property->siteName ) )
        {
            $this->site_name     = $property->siteName;
            
            if( !isset( $this->identifier->path ) )
                $this->identifier->path = new \stdClass();
                
            $this->identifier->path->siteName = $this->site_name;
        }
    }
    
/**
<documentation><description><p>Copies the calling object and creates a new asset of the same type in the supplied parent container, and returns an object representing the newly created asset.</p></description>
<example>$page->copy(
    $cascade->getAsset( 
        a\Folder::TYPE, "3890a3f88b7ffe83164c931457a2709c" ), // the target folder
    "test-asset" // new name
);
</example>
<return-type>Asset</return-type>
<exception>EmptyNameException, CopyErrorException</exception>
</documentation>
*/
    public function copy( Container $parent, string $new_name ) : Asset
    {
        if( $new_name == "" )
        {
            throw new e\EmptyNameException( c\M::EMPTY_NAME );
        }
        
        $service         = $this->getService();
        $self_identifier = $service->createId( $this->getType(), $this->getId() );
        
        $service->copy( $self_identifier, $parent->getIdentifier(), $new_name, false );
        
        if( $service->isSuccessful() )
        {
            $parent->reloadProperty(); // get info of new child
            $parent      = $parent->getProperty();
            $children    = $parent->children->child;
            $child_count = count( $children );
            
            if( $child_count == 1 )
            {
                $children = array( $children );
            }
            
            // look for the new child
            foreach( $children as $child )
            {
                $child_path = $child->path->path;
                $child_path_array = explode( '/', $child_path );
                
                if( in_array( $new_name, $child_path_array ) )
                {
                    $child_found = $child;
                    break;
                }
            }
            // get the digital id of child
            $child_id = $child_found->id;
            
            // return new block object
            return Asset::getAsset( $service, $this->getType(), $child_id );
        }
        else
        {
            throw new e\CopyErrorException(
                c\M::COPY_ASSET_FAILURE . $service->getMessage() );
        }
    }
    
/**
<documentation><description><p>Displays some basic information of the calling object and returns it.</p></description>
<example>$page->display();</example>
<return-type>Asset</return-type>
<exception></exception>
</documentation>
*/
    public function display() : Asset
    {
    	$id   = $this->getId();
    	$name = $this->getName();
    	
        echo S_H2 . "Asset::display" . E_H2 .
             c\L::ID .            $id .                  BR .
             c\L::NAME .          $name .                BR .
             c\L::PATH .          $this->path .          BR .
             c\L::SITE_ID .       $this->site_id .       BR .
             c\L::SITE_NAME .     $this->site_name .     BR .
             c\L::PROPERTY_NAME . $this->property_name . BR .
             c\L::TYPE .          $this->type .          BR .
             HR;
        return $this;
    }

/**
<documentation><description><p>Dumps the property of the calling object and returns the object.</p></description>
<example>$page->dump();</example>
<return-type>Asset</return-type>
<exception></exception>
</documentation>
*/
    public function dump( bool $formatted=true ) : Asset
    {
        if( $formatted ) echo S_H2 . get_class( $this ) . " " . c\L::READ_DUMP . E_H2 . S_PRE;
        var_dump( $this->property );
        if( $formatted ) echo E_PRE . HR;
        
        return $this;
    }
    
/**
<documentation><description><p>Calls <code>reloadProperty</code> and returns the calling object. This method is normally overridden by descendant classes for editable assets. The various parameters are required by classes like <code>Page</code> and <code>DataDefinitionBlock</code>.</p></description>
<example>$page->setText( "main-content-content", "Test content" )->
    edit();</example>
<return-type>Asset</return-type>
<exception></exception>
</documentation>
*/
    public function edit(
        p\Workflow $wf=NULL, 
        WorkflowDefinition $wd=NULL, 
        string $new_workflow_name="", 
        string $comment="",
        bool $exception=true 
    ) : Asset
    {
        return $this->reloadProperty();
    }
    
/**
<documentation><description><p>Returns an array of <a href="http://www.upstate.edu/cascade-admin/web-services/api/audit.php"><code>Audit</code></a> objects. This method has not been successfully tested with <code>$type</code> equals to <code>group</code> or <code>role</code>. <code>$start_time</code> and <code>$end_time</code>, when not <code>NULL</code>, are used to filter the output.</p></description>
<example>$audits = $page->getAudits();</example>
<return-type>array</return-type>
<exception>NoSuchTypeException, Exception</exception>
</documentation>
*/
    public function getAudits(
        string $type=c\T::EDIT, \DateTime $start_time=NULL, 
        \DateTime $end_time=NULL ) : array
    {
        if( !is_string( $type ) || !c\AuditTypes::isAuditType( $type ) )
        {
            if( self::DEBUG && !is_string( $type ) ) { u\DebugUtility::out( "Not a string" ); }
            throw new e\NoSuchTypeException( c\M::WRONG_AUDIT_TYPE );
        }

        $start = false;
        $end   = false;
        
        if( isset( $start_time ) )
        {
            if( isset( $end_time ) )
            {
                if( $end_time < $start_time )
                    throw new \Exception( c\M::SMALLER_END_TIME );
                    
                $end = true;
            }
            $start = true;
        }

        $a_std = new \stdClass();
        
        // unable to test with group, role
        if( $this->getType() == User::TYPE )
        {
            $a_std->username = $this->getName();
        }
        else if( $this->getType() == Group::TYPE )
        {
            $a_std->groupname = $this->getName();
        }
        else if( $this->getType() == Role::TYPE )
        {
            $a_std->rolename = $this->getName();
        }
        else
        {
            $a_std->identifier       = new \stdClass();
            $a_std->identifier->id   = $this->getId();
            $a_std->identifier->type = $this->getType();
        }
        
        if( $type != "" )
            $a_std->auditType  = $type;
            
        $service = $this->getService();
        $service->readAudits( $a_std );
        $audits  = array();
        
        if( $service->isSuccessful() )
        {
            if( self::DEBUG ) { u\DebugUtility::dump( $service->getAudits() ); }
        
            if( isset( $service->getAudits()->audit ) )
                $audit_stds = $service->getAudits()->audit;
            
            if( isset( $audit_stds ) && !is_array( $audit_stds ) )
            {
                $audit_stds = array( $audit_stds );
            }
            
            if( isset( $audit_stds ) && is_array( $audit_stds ) )
                $count = count( $audit_stds );
            
            if( isset( $count ) && $count > 0 )
            {
                foreach( $audit_stds as $audit_std )
                {
                    if( self::DEBUG && self::DUMP ) { u\DebugUtility::dump( $audit_std ); }
        
                    $audit = new Audit( $service, $audit_std );
            
                    if( $start && $audit->getDate() >= $start_time )
                    {
                        if( $end && $audit->getDate() <= $end_time )
                        {
                            $audits[] = $audit;
                        }
                        else if( !$end )
                        {
                            $audits[] = $audit;
                        }
                    }
                    else if( !$start )
                    {
                        if( $end && $audit->getDate() <= $end_time )
                        {
                            $audits[] = $audit;
                        }
                        else if( !$end )
                        {
                            $audits[] = $audit;
                        }
                    }
                }
                usort( $audits, self::NAME_SPACE . "\\" . 'Audit::compare' );
            }
        }
        else
        {
            echo $service->getMessage();
        }

        return $audits;
    }
    
/**
<documentation><description><p>Returns <code>id</code>.</p></description>
<example>echo $page->getId(), BR;</example>
<return-type>string</return-type>
<exception></exception>
</documentation>
*/
    public function getId() : string
    {
        return $this->id;
    }
    
/**
<documentation><description><p>Returns the identifier passed into the constructor.</p></description>
<example>u\DebugUtility::dump( $page->getIdentifier() );</example>
<return-type>stdClass</return-type>
<exception></exception>
</documentation>
*/
    public function getIdentifier() : \stdClass
    {
        return $this->identifier;
    }
    
/**
<documentation><description><p>Returns <code>name</code>.</p></description>
<example>echo $page->getName(), BR;</example>
<return-type>string</return-type>
<exception></exception>
</documentation>
*/
    public function getName() : string
    {
        return $this->name;
    }
    
/**
<documentation><description><p>Returns <code>path</code>.</p></description>
<example>echo $page->getPath(), BR;</example>
<return-type>string</return-type>
<exception></exception>
</documentation>
*/
    public function getPath() : string
    {
        return $this->path;
    }
    
/**
<documentation><description><p>Returns the property.</p></description>
<example>u\DebugUtility::dump( $page->getProperty() );</example>
<return-type>stdClass</return-type>
<exception></exception>
</documentation>
*/
    public function getProperty() : \stdClass
    {
        return $this->property;
    }
    
/**
<documentation><description><p>Returns the property name.</p></description>
<example>echo $page->getPropertyName(), BR;</example>
<return-type>string</return-type>
<exception></exception>
</documentation>
*/
    public function getPropertyName() : string
    {
        if( self::DEBUG ) { u\DebugUtility::out( "From Asset::getPropertyName " . $this->property_name ); }
        return $this->property_name;
    }
    
/**
<documentation><description><p>Returns the <code>$service</code> object passed into the constructor.</p></description>
<example>u\DebugUtility::dump( $page->getService() );</example>
<return-type>AssetOperationHandlerService</return-type>
<exception></exception>
</documentation>
*/
    public function getService() : aohs\AssetOperationHandlerService
    {
        return $this->service;
    }
    
/**
<documentation><description><p>Returns <code>siteId</code>.</p></description>
<example>echo $page->getSiteId(), BR;</example>
<return-type>string</return-type>
<exception></exception>
</documentation>
*/
    public function getSiteId() : string
    {
        return $this->site_id;
    }
  
/**
<documentation><description><p>Returns <code>siteName</code>.</p></description>
<example>echo $page->getSiteName(), BR;</example>
<return-type>mixed</return-type>
<exception></exception>
</documentation>
*/
    public function getSiteName()
    {
        return $this->site_name;
    }
    
/**
<documentation><description><p>Returns an array of subscribers (<a href="http://www.upstate.edu/cascade-admin/web-services/api/property-classes/identifier.php"><code>Identifier</code></a> objects).</p></description>
<example>$subscribers = $page->getSubscribers(); // array of Identifier objects
echo "There are " . count( $subscribers ) . " subscribers.", BR;</example>
<return-type>array</return-type>
<exception></exception>
</documentation>
*/
    public function getSubscribers() : array
    {
        $results = array();
        $this->service->listSubscribers( $this->identifier );
            
        if( $this->service->isSuccessful() )
        {
            if( self::DEBUG ) { u\DebugUtility::out( "Successfully listing subscribers" ); }
            
            // there are subscribers
            if ( isset( $this->service->getReply()->listSubscribersReturn->subscribers->assetIdentifier ) )
            {
                $subscribers = 
                    $this->service->getReply()->listSubscribersReturn->subscribers->assetIdentifier;
                
                if( !is_array( $subscribers ) )
                    $subscribers = array( $subscribers );
                    
                foreach( $subscribers as $subscriber )
                {
                    $identifier = new p\Identifier( $subscriber );
                    $results[] = $identifier;
                }
            }
        }
        else
        {
            echo $this->service->getMessage();
        }
        
        return $results;
    }
    
/**
<documentation><description><p>Returns the type string.</p></description>
<example>echo $page->getType(), BR;</example>
<return-type>string</return-type>
<exception></exception>
</documentation>
*/
    public function getType() : string
    {
        return $this->type;
    }
    
/**
<documentation><description><p>Publishes all publishable subscribers to the supplied destination, or to all destinations if none supplied, and returns the calling object.</p></description>
<example>$page->publishSubscribers( 
    $cascade->getAsset( a\Destination::TYPE, "388fd57b8b7ffe83164c9314b3e7eef4" ) 
);</example>
<return-type>Asset</return-type>
<exception></exception>
</documentation>
*/
    public function publishSubscribers( Destination $destination=NULL ) : Asset
    {
        $subscriber_ids = $this->getSubscribers();
        
        if( isset( $destination ) )
        {
            $destination_std           = new \stdClass();
            $destination_std->id       = $destination->getId();
            $destination_std->type     = $destination->getType();
        }
        
        if( isset( $subscriber_ids ) )
        {
            foreach( $subscriber_ids as $subscriber_id )
            {
                if( self::DEBUG ) { u\DebugUtility::out( "Publishing " . $subscriber_id->getId() ); }
                
                if( isset( $destination_std ) )
                    $this->getService()->publish( $subscriber_id->toStdClass(), $destination_std );
                else
                    $this->getService()->publish( $subscriber_id->toStdClass() );
            }
        }
        return $this;
    }
    
/**
<documentation><description><p>Reads the asset and returns the calling object.</p></description>
<example>$page = $page->reloadProperty();</example>
<return-type>Asset</return-type>
<exception></exception>
</documentation>
*/
    public function reloadProperty() : Asset
    {
        // 2016/12/09 added path for Destination
        if( !isset( $this->identifier->path->path ) )
        {
        	$this->identifier->path = new \stdClass();
            $this->identifier->path->path = "";
        }
    
        $this->property = 
            $this->service->retrieve( $this->identifier, $this->property_name );
        return $this;
    }
    
/**
<documentation><description><p>Returns a new object of the named type bearing the supplied identity information. The <code>$id_path</code> parameter can be an id string like <code>d7b47ebb8b7f085600a0fcdc2149931f</code> or a path string. When it is a path string, the site name must also be supplied. Note that all asset classes inherit this static method.</p></description>
<example>$page = a\Asset::getAsset( 
    $service, a\Page::TYPE, $page_id );</example>
<return-type>Asset</return-type>
<exception>NullAssetException, NoSuchTypeException, Exception</exception>
</documentation>
*/
    public static function getAsset( 
        aohs\AssetOperationHandlerService $service, string $type, string $id_path, string $site_name=NULL ) : Asset
    {
        if( !in_array( $type, c\T::getTypeArray() ) )
            throw new e\NoSuchTypeException( S_SPAN . "The type $type does not exist." . E_SPAN );   
            
        $class_name = c\T::$type_class_name_map[ $type ]; // get class name
        
        try
        {
            $class_name = self::NAME_SPACE . "\\" . $class_name;
            
            return new $class_name( // call constructor
                $service, 
                $service->createId( $type, $id_path, $site_name ) );
        }
        catch( \Exception $e )
        {
            if( self::DEBUG ) { u\DebugUtility::out( $e->getMessage() ); }
            throw $e;
        }
    }
    
    /** @var AssetOperationHandlerService The service object */
    private $service;
    /** @var stdClass The identifier object */
    private $identifier;
    /** @var string The type */
    private $type;
    /** @var string The property name */
    private $property_name;
    /** @var stdClass The property */
    private $property;
    /** @var string The 32-digit id */
    private $id;
    /** @var string The name */
    private $name;
    /** @var string The path */
    private $path;
    /** @var string The 32-digit site id */
    private $site_id;
    /** @var string The site name */
    private $site_name;
}
?>