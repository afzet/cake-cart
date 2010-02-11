<?php
/**
* Ini parser
*
* Parse Ini files into a workable object
*
* @author mcvdmvs <mick@vandermostvanspijk.nl>
* @version 0.0.1
* @package sys
* @copyright GPL
* @see http://cloanto.com/specs/ini.html
*/
class Ini {
	/**
	* _object->sectionname->key] = value
	* _object->sectionname->key[0] = value_one
	* _object->sectionname->key[1] = value_two
	*/
	var $_data;

	/**
	* store filename
	*/
	var $file;

	/**
	* keep track of last section
	*/
	var $_last_section = '';

	/**
	* Start Ini and set file
	*/
	function Ini ($file) {
		$this->file = $file;
	}

	/**
	* Open file and load in object
	*/
	function parse() {
		$fp = fopen ($this->file, 'r');

		while ($data = fgets($fp, 4096)) {
			$this->_parseIni($data);
		}

		return $this->_data;
	}

	/**
	* Internal function to handle different section
	*/
	function _parseIni($data) {
		// trim beginning and ending spaces
		$data = trim($data);

		// skip white lines
		if (empty($data))
			return;

		// skip comment lines
		if (substr($data, 0, 1) == ';')
			return;

		// section
		if ((substr($data, 0, 1) == '[') AND (substr($data, -1) == ']')) {
			$this->_last_section = substr($data, 1, (strlen($data) - 1));
			return;
		}

		// entry
		$pos = strpos($data, '=');
		if ($pos !== FALSE) { // boolean false
			// set name
			$name = substr($data, 0, $pos);

			// set value
			$value = substr ($data, ($pos + 1), (strlen($data) - $pos - 1));

			// check for comma's and spaces in value, if so make array of it
			if (strpos($value, ", ")) {
				$list = explode (",", $value);

				// unset value
				$value = array();

				// loop through values and add them to array
				foreach ($list as $val) {
					$value[] = trim($val);
				}
			}

			// store value
			$this->_data->{$this->_last_section}->$name = $value;
		}
	}

	/**
	* return parsed ini file
	*/
	function get() {
		return $this->_data;
	}
}
?>