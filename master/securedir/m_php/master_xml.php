<?php
/**
 *
 * CONTAINS FUNCTIONS WHICH HELP IN XML CREATION AND PROCESSING
 * @author T.V.VIGNESH
 *
 */
class ta_xmloperations
{
	/**
	 *
	 * Get an XML object when XML string is passed
	 * @param unknown_type $xmlstring XML String
	 * @return DOMDocument The XML object
	 */
	public function getxmlobj($xmlstring)
	{
		$dom = new DOMDocument();
		$dom->loadXML($xmlstring);
		return $dom;
	}

	/**
	 *
	 * Creates an element(NODE)
	 * @param unknown_type $domObj The node object after which node has to be created
	 * @param unknown_type $tag_name The Name of the new tag (Defaults to NULL)
	 * @param unknown_type $value The value of the new tag (Defaults to NULL)
	 * @param unknown_type $attributes The attributes being passed as an array (Defaults to NULL)
	 * @return unknown XML object returned
	 */
	public function createElement($domObj, $tag_name, $value = NULL, $attributes = NULL)
	{
		$element = ($value != NULL ) ? $domObj->createElement($tag_name, $value) : $domObj->createElement($tag_name);

		if( $attributes != NULL )
		{
			foreach ($attributes as $attr=>$val)
			{
				$element->setAttribute($attr, $val);
			}
		}

		return $element;
	}

	/**
	 *
	 * Generate XML
	 * @param unknown_type $node Key of the node
	 * @param unknown_type $value The value of the node
	 * @param unknown_type $attrarray The attribute being passed as an array
	 * @param unknown_type $oldxml The old XML string (if it has to be appended)
	 * @param unknown_type $parent The parent object of the node
	 * @param unknown_type $parentindex The index of the parent
	 * @param unknown_type $version
	 * @param unknown_type $encoding
	 * @return string
	 */
	public function generatexml($node,$value,$attrarray=NULL,$oldxml="",$parent=NULL,$parentindex=0,$version="1.0",$encoding="utf-8")
	{
		if($oldxml=="")
		{
			$dom = new DOMDocument($version,$encoding);
		}
		else
		{
			$dom = DOMDocument::loadXML($oldxml);
		}
		$dom->formatOutput = BOOL_SUCCESS;

		$xmlobj=new ta_xmloperations();
		$elm = $xmlobj->createElement($dom, $node, $value, $attrarray);//CREATE AN ELEMENT FIRST

		if($parent==NULL)
		{
			$dom->appendChild($elm);//APPEND IT AS DIRECT CHILD OF DOCUMENT
		}
		else
		{
			$dom->documentElement->getElementsByTagName($parent)->item($parentindex)->appendChild($elm);//APPEND IT AS SUB CHILD OF THE NODE SPECIFIED
		}

		return $dom->saveXML();
	}

	/**
	 *
	 * Converts an XML file to string
	 * @param unknown_type $path Complete path of the XML file
	 * @return string The XML string
	 */
	public function xmlfiletostring($path)
	{
		$dom = new DOMDocument();
		if (!@$dom->load($path)){$loaderror="Unable to Load XML File!";return FAILURE;}
		return $dom->saveXML();
	}

	/**
	 *
	 * CONVERT AN XML STRING TO FILE
	 * @param unknown_type $xmlstring XML String
	 * @param unknown_type $destination Destination of the XML file (Without XML file Name)
	 * @param unknown_type $filename Name of the XML File
	 * @param unknown_type $version Defaults to 1.0
	 * @param unknown_type $encoding Defaults to utf-8
	 * @return number
	 */
	public function xmlstringtofile($xmlstring,$destination,$filename,$version="1.0",$encoding="utf-8")
	{
		$dom = new DOMDocument($version,$encoding);
		$dom->loadXML($xmlstring);
		return $dom->save($destination."/".$filename);//RETURNS 0 if NOTHING WRITTEN
	}

	/**
	 *
	 * ACCESS VALUE FROM A NODE XML FILE WHEN KEY IS GIVEN
	 * @param unknown_type $xmlstring
	 * @param unknown_type $key
	 * @param unknown_type $version
	 * @param unknown_type $encoding
	 * @return DOMNodeList
	 */
	public function accessval($xmlstring,$key,$version="1.0",$encoding="utf-8")
	{
		$dom = new DOMDocument($version,$encoding);
		$dom->formatOutput = BOOL_SUCCESS;
		$dom->preserveWhiteSpace = BOOL_FAILURE;
		$dom->loadXML($xmlstring);
		$items=$dom->getElementsByTagName($key);
		return $items;
	}

	/**
	 *
	 * @param unknown_type $xmlstring XML String
	 * @param unknown_type $key Key of the node to be deleted
	 * @param unknown_type $index Index of the Node
	 * @param unknown_type $version
	 * @param unknown_type $encoding
	 * @return string
	 */
	public function delnode($xmlstring,$key,$index=NULL,$version="1.0",$encoding="utf-8")
	{
		$dom = new DOMDocument($version,$encoding);
		$dom->formatOutput = BOOL_SUCCESS;
		$dom->preserveWhiteSpace = BOOL_FAILURE;
		$dom->loadXML($xmlstring);
		if($index==NULL)
		{
			$nodeList = $dom->getElementsByTagName($key);
		}
		else
		{
			$nodeList = $dom->getElementsByTagName($key)->item($index);
		}
		$lasterror="";
		for ($i = 0; $i < $nodeList->length; $i++)
		{
		    try
		    {
		        $old_child = $nodeList->item($i)->parentNode->removeChild($nodeList->item($i));
		    }
		    catch (DOMException $e)
		    {
		        $lasterror.=$e->getMessage();
		    }
		}
		if($lasterror!="")
		{
			throw new Exception('#ta@0000000_0000087');
			return FAILURE;
		}
		else
		{
			return SUCCESS;
		}
	}

	/**
	 *
	 * Converts XML to JSON
	 * @param unknown_type $xmlstring XML String
	 * @param unknown_type $version
	 * @param unknown_type $encoding
	 * @return string JSON String
	 */
	public function xmltojson($xmlstring,$version="1.0",$encoding="utf-8")
	{
		$dom = new DOMDocument($version,$encoding);
		$dom->formatOutput = BOOL_SUCCESS;
		$dom->preserveWhiteSpace = BOOL_FAILURE;
		$dom->loadXML($xmlstring);
		$json= '('.json_encode($dom).');'; //must wrap in parens and end with semicolon
		return $json;
	}

	/**
	 *
	 * Converts an array to XML
	 * @param unknown_type $arrayelem Associate array to be converted
	 * @param unknown_type $version
	 * @param unknown_type $encoding
	 */
	public function array_to_xml($arrayelem,$version="1.0",$encoding="utf-8") {
		$dom = new DOMDocument($version,$encoding);
		array_walk_recursive($test_array, array ($dom, 'createElement'));
		return $dom->asXML();
	}

	/**
	 *
	 * Converts JSON to XML
	 * @param unknown_type $jsonstring The JSON string to be converted to XML
	 */
	public function jsontoxml($jsonstring)
	{
		$xmlobj=new ta_xmloperations();
		return $xmlobj->array_to_xml(json_decode($jsonstring, BOOL_SUCCESS));
	}
}