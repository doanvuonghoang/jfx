<?php

namespace lib\jf\portlet;

/**
 *
 * @author Hoàng
 */
interface IPortlet {

	/**
	 * Get id of portlet instance.
	 */
	function getPortletID();

	/**
	 * @param IPortletContext $ctx
	 */
	function init(IPortletContext $ctx);

	/**
	 * Process action.
	 */
	function processAction();

	/**
	 * Render portlet.
	 */
	function render();
}

?>
