<?php 
/**
  * Author: Wing Ming Chan, German Drulyk
  * Copyright (c) 2020 Wing Ming Chan <chanw@upstate.edu>, German Drulyk <drulykg@upstate.edu>
  * MIT Licensed
  * Modification history:
  * 8/13/2020 - drulykg - Added removeTags()
  *                       Added removeAllTags()
  *                       Added private setTagsProperty()
  *                       Fixed $this->tags to be more universal
  * 7/15/2020 - drulykg - Fixed addTag() function so that it doesn't lose previous tag(s)
  * 6/28/2019 Added more documentation.
  * Added addTags and changed code in addTag, using the class variable tags to cache.
  * 10/12/2018 Fixed bugs in addTag, isInTags and removeTag.
  * 5/18/2018 Added addTag, isInTags, hasTag and removeTag.
  * 1/3/2018 Added code to test for NULL.
  * 12/15/2017 Updated documentation.
  * 11/28/2017 Class created.
 */
namespace cascade_ws_asset;

use cascade_ws_constants as c;
use cascade_ws_AOHS      as aohs;
use cascade_ws_utility   as u;
use cascade_ws_exception as e;

/**
<documentation><description>
<?php global $service;
$doc_string = "<h2>Introduction</h2>
<p>The <code>FolderContainedAsset</code> class is an abstract sub-class of <code>ContainedAsset</code> and the superclass of all asset classes representing assets that can be found in folders.</p>
<h2>WSDL</h2>";
$doc_string .=
    $service->getXMLFragments( array(
        array( "getComplexTypeXMLByName" => "folder-contained-asset" ),
        array( "getComplexTypeXMLByName" => "containered-asset" ),
    ) );
return $doc_string;
?>
</description>
</documentation>
*/
abstract class FolderContainedAsset extends ContainedAsset
{
    const DEBUG = false;

/**
<documentation><description><p>The constructor.</p></description>
</documentation>
*/
    protected function __construct( 
        aohs\AssetOperationHandlerService $service, \stdClass $identifier )
    {
        parent::__construct( $service, $identifier );
        
        if( $this->getService()->isSoap() )
        {
        	// not set if no tags exist
        	if( isset( $this->getProperty()->tags->tag ) )
        	{
                // check if single tag or multiple and standardize into array
                if( isset( $this->getProperty()->tags->tag->name ) )
                {
                    $this->tags = array( $this->getProperty()->tags->tag );
                }
        		else
                {
                    $this->tags = $this->getProperty()->tags->tag;
                }
        	}
        }
        elseif( $this->getService()->isRest() )
        {
            // REST is expected to always contain an array
        	$this->tags = $this->getProperty()->tags;
        }
    }

/**
<documentation><description><p>Adds a tag, if it does not exist, and returns the calling object.</p></description>
<example>$page->addTag( "education" )->edit();</example>
<return-type>Asset</return-type>
<exception></exception>
</documentation>
*/
    public function addTag( string $t ) : Asset
    {
        $t = trim( $t );
        
        if( $t !== '' && !$this->isInTags( $t ) )
        {
            $std = new \stdClass();
            $std->name = $t;
            
            $this->tags[] = $std;
            
            $this->setTagsProperty();
        }
        
        return $this;
    }

/**
<documentation><description><p>Adds tags, if they do not exist, and returns the calling object.</p></description>
<example>$page->addTags( array( "education", "healthcare" ) )->edit();</example>
<return-type>Asset</return-type>
<exception></exception>
</documentation>
*/
    public function addTags( array $a ) : Asset
    {
		foreach( $a as $s )
		{
			if( is_string( $s ) )
			{
				$this->addTag( $s );
			}
		}
        return $this;
    }

/**
<documentation><description><p>Returns the parent folder or <code>NULL</code>.</p></description>
<example>u\DebugUtility::dump( $bf->getParentFolder() );</example>
<return-type>mixed</return-type>
<exception></exception>
</documentation>
*/
    public function getParentFolder()
    {
        if( $this->getParentFolderId() != NULL )
        {
            $parent_id    = $this->getParentContainerId();
            $parent_type  = c\T::$type_parent_type_map[ $this->getType() ];
            
            return Asset::getAsset( $this->getService(), $parent_type, $parent_id );
        }
        return NULL;
    }

/**
<documentation><description><p>Returns <code>getParentFolderId</code> or NULL.</p></description>
<example>echo $dd->getParentFolderId(), BR,
     $dd->getParentFolderPath(), BR;</example>
<return-type>mixed</return-type>
<exception>WrongAssetTypeException</exception>
</documentation>
*/
    public function getParentFolderId()
    {
        if( isset( $this->getProperty()->parentFolderId ) )
            return $this->getProperty()->parentFolderId;
        return NULL;
    }

/**
<documentation><description><p>Returns <code>parentFolderPath</code> or NULL.</p></description>
<example>echo $dd->getParentFolderId(), BR,
     $dd->getParentFoldPath(), BR;</example>
<return-type>mixed</return-type>
<exception>WrongAssetTypeException</exception>
</documentation>
*/
    public function getParentFolderPath()
    {
        if( isset( $this->getProperty()->parentFolderPath ) )
            return $this->getProperty()->parentFolderPath;
        return NULL;
    }

/**
<documentation><description><p>Returns <code>tags</code> (an <code>stdClass</code> object for SOAP, and an array for REST).</p></description>
<example>u\DebugUtility::dump( $page->getTags() );</example>
<return-type>mixed</return-type>
<exception></exception>
</documentation>
*/
    public function getTags()
    {
        return $this->getProperty()->tags;
    }

/**
<documentation><description><p>An alias of <code>isInTags</code>.</p></description>
<example>echo u\StringUtility::boolToString( $page->hasTag( "education" ) );</example>
<return-type>bool</return-type>
<exception></exception>
</documentation>
*/
    public function hasTag( string $t ) : bool
    {
    	return $this->isInTags( $t );
    }

/**
<documentation><description><p>An alias of <code>isDescendantOf</code>.</p></description>
<example>if( $page->isInContainer( $test2 ) )
    $page->move( $test1, false );</example>
<return-type>bool</return-type>
<exception></exception>
</documentation>
*/
    public function isInFolder( Folder $folder ) : bool
    {
        return $this->isDescendantOf( $folder );
    }

/**
<documentation><description><p>Returns a bool, indicating whether the tag is already in
the <code>tags</code> property.</p></description>
<example>echo u\StringUtility::boolToString( $page->isInTags( "education" ) );</example>
<return-type>bool</return-type>
<exception></exception>
</documentation>
*/
    public function isInTags( string $t ) : bool
    {
        foreach( $this->tags as $tag )
        {
            if( $tag->name === $t )
            {
                return true;
            }
        }
        
        return false;
    }
    
/**
<documentation><description><p>Removes a tag, if it exists, and returns the calling object.</p></description>
<example>$page->removeTag( "education" )->edit();</example>
<return-type>Asset</return-type>
<exception></exception>
</documentation>
*/
    public function removeTag( string $t ) : Asset
    {
        foreach( $this->tags as $k => $tag )
        {
            if( $tag->name === $t )
            {
                unset( $this->tags[ $k ] );
                
                // tags must be sequential or else edit will fail
                $this->tags = array_values( $this->tags );
            }
        }
        
        $this->setTagsProperty();
    	
    	return $this;
    }
    
/**
<documentation><description><p>Removes specified tags, if they exist, and returns the calling object.</p></description>
<example>$page->removeTags( array( "education", "apple" ) )->edit();</example>
<return-type>Asset</return-type>
<exception></exception>
</documentation>
*/
    public function removeTags( array $a ) : Asset
    {
        foreach( $a as $s )
		{
			if( is_string( $s ) )
			{
				$this->removeTag( $s );
			}
		}
        
        return $this;
    }
    
/**
<documentation><description><p>Removes all tags and returns the calling object.</p></description>
<example>$page->removeAllTags()->edit();</example>
<return-type>Asset</return-type>
<exception></exception>
</documentation>
*/
    public function removeAllTags() : Asset
    {
        $this->tags = array();
        
        $this->setTagsProperty();
        
        return $this;
    }
    
/**
<documentation><description><p>Set the tags property of the object. Normalized regardless of SOAP or REST.</p></description>
<example>$this->setTagsProperty();</example>
<return-type>void</return-type>
<exception></exception>
</documentation>
*/
    private function setTagsProperty()
    {
        if( $this->getService()->isSoap() )
        {
            if( count( $this->tags ) === 0 )
            {
                $this->getProperty()->tags = new \stdClass();
            }
            elseif( count( $this->tags ) === 1 )
            {
                $this->getProperty()->tags->tag = $this->tags[ 0 ];
            }
            else
            {
                $this->getProperty()->tags->tag = $this->tags;
            }
        }
        elseif( $this->getService()->isRest() )
        {
            $this->getProperty()->tags = $this->tags;
        }
    }
    
    private $tags = array();
}
?>