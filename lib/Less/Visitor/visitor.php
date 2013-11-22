<?php

class Less_visitor{

	function visit($nodes){

		if( is_array($nodes) ){
			return $this->visitArray($nodes);
		}

		if( !is_object($node) ){
			continue;
		}

		$class = get_class($node);
		$funcName = 'visit'.substr($class,10); //remove 'Less_Tree_' from the class name

		if( method_exists($this,$funcName) ){
			$this->$funcName( $node );
		}

		$deeper_property = $funcName.'Deeper';
		if( !isset($this->$deeper_property) && Less_Parser::is_method($node,'accept') ){
			$node->accept($this);
		}

		$funcName = $funcName . "Out";
		if( method_exists($this,$funcName) ){
			$this->$funcName( $node );
		}
		return $node;
	}

	function visitArray( $nodes ){
		$newNodes = array();
		for($i = 0; $i < count($nodes); $i++ ){
			$evald = $this->visit($nodes[$i]);
			if( is_array($evald) ){
				$evald = self::flatten($evald);
				$newNodes = array_merge($newNodes,$evald);
			}else{
				$newNodes[] = $evald;
			}
		}
		if( $this->isReplacing ){
			return $newNodes;
		}
		return $nodes;
	}

	function doAccept($node){
		$node->accept($this);
	}

	static function flatten($array) {
	    $result = array();
	    foreach($array as $value){
			if( is_array($value) ){
				$result = array_merge($result, self::flatten($value));
			}else{
				$result[] = $value;
			}
	    }
	    return $result;
	}
}

