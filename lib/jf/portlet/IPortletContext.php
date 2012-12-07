<?php

namespace lib\jf\portlet;

/**
 *
 * @author Hoàng
 */
interface IPortletContext {

	function getStaticResource($resName);

	function getRelativeResource($resName);

	/**
	 * @param string $locale
	 * @return IPortletBundles
	 */
	function getBundles($locale = NULL);

	function isAjaxRequest();

	/**
	 * Return mode of user requested.
	 * @return string VIEW | EDIT | DESIGN
	 */
	function getMode();
	
	function getAction();
	
	function getRender();
	
	function getInteractivePortletID();
	
	function getTheme();

	function getContentType();

	function getLocale();
	
	/**
	 * @param string $name parameter name
	 * @param string $scope NULL: global | 'render' | 'action'
	 */
	function getParameter($name, $scope = NULL);

	/**
	 * @param string $name parameter name
	 * @param string $value parameter value
	 * @param string $scope NULL: global | 'render' | 'action'
	 */
	function setParameter($name, $value, $scope = NULL);
	
	/**
	 * @param string $name session data key name
	 * @param string $scope 'portlet' | NULL
	 */
	function getSessionData($name, $scope = 'portlet');

	function setSessionData($name, $value, $scope = 'portlet');
	
	function getPortletData($name=NULL);

	function setPortletData($name, $value);
	
	function getPortletSetting($name=NULL);
	
	function setPortletSetting($name, $value);

	/**
	 * @param string $portletID
	 * @param array $params
	 * @param array $keep_get
	 */
	function createURL($portletID, $params = array(), $keep_get = array());

	/**
	 * @param string $kind NULL | 'action' | 'render'
	 * @param string $kindName
	 * @param array $kindParams
	 * @param array $params
	 * @param array $keep_get
	 */
	function createPortletURL($kind = NULL, $kindName = NULL, $kindParams = array(), $params = array(), $keep_get = array());
	
	/**
	 * @return IPortletRenderer
	 */
	function getRenderer();
}

?>
