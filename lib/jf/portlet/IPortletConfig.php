<?php

namespace lib\jf\portlet;

/**
 *
 * @author Hoàng
 */
interface IPortletConfig {

	function getSupportedContentTypes();

	function getSupportedLocales();
	
	/**
	 * @param string $name
	 * @return mixed
	 */
	function getActionMap($name=NULL);
	
	function getRenderMap($name=NULL);
}

?>
