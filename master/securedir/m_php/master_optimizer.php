<?php
/**
 *
 * CONTAINS OPTIMIZING FUNCTIONS
 * @author T.V.VIGNESH
 *
 */
class ta_optimizer
{
	public function css_compress($buffer)
	{
			/* remove comments */
			$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
			/* remove tabs, spaces, newlines, etc. */
			$buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
			return $buffer;
	}

	public function serverpathtourl($file, $path=ROOT_SERVER)
	{
		if(strpos($file, $path) !== BOOL_FAILURE){return substr($file, strlen($path));}
	}
	
	public function constants_css($css)
	{
		preg_match_all("/\\$(\w+).*=.*\'(.*)\'/",$css,$constants);
		for($i=0;$i<sizeof($constants[1]);$i++)
		{
			$css=preg_replace('/\\$'.$constants[1][$i].'/',$constants[2][$i],$css);
		}
		//$css=preg_replace("/\\#.*=.*?;\s+/s",'',$css);
		return $css;
	}
	
	public function minify_css($css)
	{
		$minobj=new CSSmin();
		$css=$minobj->run($css);
		return $css; 
	}

	public function optimize_css($buffer)
	{
		$optobj=new ta_optimizer();
		$buffer=$optobj->constants_css($buffer);
		$buffer=$optobj->css_compress($buffer);
		$buffer=$optobj->minify_css($buffer);
		return $buffer;
	}
	
	public function compress_html($text)
	{
		$re = '%		# Collapse ws everywhere but in blacklisted elements.
        (?>             # Match all whitespans other than single space.
          [^\S ]\s*     # Either one [\t\r\n\f\v] and zero or more ws,
        | \s{2,}        # or two or more consecutive-any-whitespace.
        ) # Note: The remaining regex consumes no text at all...
        (?=             # Ensure we are not in a blacklist tag.
          (?:           # Begin (unnecessary) group.
            (?:         # Zero or more of...
              [^<]++    # Either one or more non-"<"
            | <         # or a < starting a non-blacklist tag.
              (?!/?(?:textarea|pre)\b)
            )*+         # (This could be "unroll-the-loop"ified.)
          )             # End (unnecessary) group.
          (?:           # Begin alternation group.
            <           # Either a blacklist start tag.
            (?>textarea|pre)\b
          | \z          # or end of file.
          )             # End alternation group.
        )  # If we made it here, we are not in a blacklist tag.
        %ix';
		$text = preg_replace($re, " ", $text);
		$text = preg_replace('~<!--(?!<!)[^\[>].*?-->~s','', $text);
		return $text;
	}
	
	/**
	 *
	 * SANITIZE HTML OUTPUT INCLUDING PRE TAGS
	 * @param unknown_type $buffer
	 * @return mixed
	 */
	public function sanitize_output($buffer)
	{
		$search = array(
				'/\>[^\S ]+/s', //strip whitespaces after tags, except space
				'/[^\S ]+\</s', //strip whitespaces before tags, except space
				'/(\s)+/s'  // shorten multiple whitespace sequences
		);
		$replace = array(
				'>',
				'<',
				'\\1'
		);
		$buffer = preg_replace($search, $replace, $buffer);
	
		return $buffer;
	}
}
?>