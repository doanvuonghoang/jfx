<?php
namespace lib\jf\portlet;

/**
 *
 * @author Hoàng
 */
class Portlet implements IPortlet {
	
	/**
	 * @var IPortletContext
	 */
	protected $context;
	
	/**
	 * @var IPortletConfig
	 */
	private $config;
	
	private $render;
	
	function __construct(IPortletConfig $cfg) {
		$this->config = $cfg;
	}
	
	/**
	 * Get id of portlet instance.
	 */
	function getPortletID() {
		return $this->context->getPortletID();
	}

	/**
	 * @param IPortletContext $ctx
	 */
	function init(IPortletContext $ctx) {
		$this->context = $ctx;
		$this->__fireEvent(new PortletEvent($this, 'portlet_inited'));
		$this->__checkRunConditional();
	}
	
	protected function __checkRunConditional() {
		if(!in_array($this->context->getContentType(), $this->config->getSupportedContentTypes()) ||
				!in_array($this->context->getLocale(), $this->config->getSupportedLocales()))
			throw new \Exception();
	}

	/**
	 * Process action.
	 */
	function processAction() {
		$this->__fireEvent(new PortletEvent($this, 'portlet_beforeAction'));
		$action = 'action_'.$this->context->getAction();
		
		$this->$action();
		
		$this->render = $this->config->getActionMap($action);
		
		$this->__fireEvent(new PortletEvent($this, 'portlet_afterAction'));
	}

	/**
	 * Render portlet.
	 */
	function render() {
		$this->__fireEvent(new PortletEvent($this, 'portlet_beforeRender'));
		if($this->render == NULL) $this->render = 'default';
		
		$data = $this->config->getRenderMap($this->render);
		
		if($data == NULL) return NULL;
		
		//
		$compiled = array();
		foreach($data['fields'] as $key => $value) {
			$value = 'render_'.$value;
			$compiled[$key] = $this->$value();
		}
		
		$this->context->getRenderer()->flush($compiled, $data['tpl']);
		
		$this->__fireEvent(new PortletEvent($this, 'portlet_afterRender'));
	}
	
	protected function __fireEvent(PortletEvent $e) {
		try {
			$svc = jf_Context::getContext()->getService('jf/event');

			$svc->notify($e);
		} catch (NoServiceException $nse) {
		}
	}

}

?>
