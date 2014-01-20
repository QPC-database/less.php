<?php


class Less_Tree_Assignment extends Less_Tree{

	public $key;
	public $value;
	public $type = 'Assignment';

	function __construct($key, $val) {
		$this->key = $key;
		$this->value = $val;
	}

	function accept( $visitor ){
		$this->value = $visitor->visitObj( $this->value );
	}

	public function compile($env) {
		return new Less_Tree_Assignment( $this->key, $this->value->compile($env));
	}

	public function genCSS( $env, $output ){
		$output->add( $this->key . '=' );
		$this->value->genCSS( $env, $output );
	}

	public function toCss($env = null){
		return $this->key . '=' . $this->value->toCSS();
	}
}
