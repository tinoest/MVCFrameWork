<? 

class xml2array
{

	var $xml = ''; 

	function xml2array($xml) {{{ 
		$this->xml = $xml;    
	}}}

	/**
	 * @param integer $i
	 */
	function _struct_to_array($values, &$i, $cnt = array()) {{{ 
		$child = array();
		if( isset($values[$i]['value']) ) {
			array_push( $child, $values[$i]['value'] ); 
		}
		while( $i++ < count( $values ) ) {
			switch( $values[$i]['type'] ) {
				case 'cdata': 
					array_push( $child, $values[$i]['value'] ); 
					break; 
				case 'complete': 
						$name = $values[$i]['tag'];
						if( !empty($name) ) {
							$child[$name] = ($values[$i]['value']) ? ($values[$i]['value']) : ''; 
						}    
					break; 
				case 'open': 
					$name = $values[$i]['tag'];
					if( isset($child[$name]) ) {
						if( array_key_exists( $name, $child ) ) {
							if( !array_key_exists( $name, $cnt ) ) {
								$tmp				= array_count_values( array_keys( $child ) );
								$cnt[$name] = $tmp[$name];
								$tmp				= $child[$name];
								unset($child[$name]);
								$child[$name][$cnt[$name]] = $tmp;
								$child[$name][$cnt[$name] + 1] = $this->_struct_to_array( $values, $i, $cnt ); 
							}
							else {
								$tmp = $child[$name];
								unset($child[$name]);
								for( $j = 1; $j <= count( $tmp ); $j++ ) {
									$child[$name][$j] = $tmp[$j];
								}
								$child[$name][$j] = $this->_struct_to_array( $values, $i, $cnt ); 
							}
						}
						else {
							$child[$name][0] = $child[$name];
							$child[$name][count( $child[$name] )] = $this->_struct_to_array( $values, $i ); 
						}
					}
					else {
						$child[$name] = $this->_struct_to_array( $values, $i ); 
					}
					break; 

				case 'close': 
					return $child; 
					break; 
			} 
		} 
		return $child; 
	}}}

	function create_array() {{{ 

			$xml    = $this->xml; 
			$values = array(); 
			$index  = array(); 
			$array  = array(); 
			$parser = xml_parser_create(); 
			xml_parser_set_option( $parser, XML_OPTION_SKIP_WHITE, 1 ); 
			xml_parser_set_option( $parser, XML_OPTION_CASE_FOLDING, 0 ); 
			xml_parse_into_struct( $parser, $xml, $values, $index ); 
			xml_parser_free( $parser ); 
			$i = 0;
			$name = $values[$i]['tag']; 
			$array[$name] = isset($values[$i]['attributes']) ? $values[$i]['attributes'] : ''; 
			$array[$name] = $this->_struct_to_array( $values, $i ); 
			return $array; 
	}}}

	}
?>
