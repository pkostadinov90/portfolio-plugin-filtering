<?php
/**
 * Render frontend
 *
 * @package Filtering
 */

namespace Filtering;

class Render {

	public function __construct() {
	}

	public function render() {
		include FILTERING_DIR . '/templates/widget.php';
	}

}
