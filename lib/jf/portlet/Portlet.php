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
	
	protected $render;
	
	function __construct(IPortletConfig $cfg) {
		$this->config = $cfg;
	}

	/**
	 * @param IPortletContext $ctx
	 */
	function init(IPortletContext $ctx) {
		$this->context = $ctx;
		$this->__processEvent(new PortletEvent($this, 'portlet_inited'));
		$this->__processAfterInited();
	}
	
	protected function __processAfterInited() {
		if(!in_array($this->context->getContentType(), $this->config->getSupportedContentTypes()) ||
				!in_array($this->context->getLocale(), $this->config->getSupportedLocales()))
				// @TODO: fix this
			throw new \Exception();
	}

	/**
	 * Process action.
	 */
	function processAction() {
		$this->__processBeforeAction();
		
		$this->__doAction();
		
		$this->__processAfterAction();
	}
	
	protected function __doAction() {
		$action = 'action_'.$this->context->getAction();
		$this->$action();
	}
	
	protected function __processBeforeAction() {
		$this->__processEvent(new PortletEvent($this, 'portlet_beforeAction'));
	}
	
	protected function __processAfterAction() {
		$this->render = $this->config->getActionMap($action);

		$this->__processEvent(new PortletEvent($this, 'portlet_afterAction'));
	}

	/**
	 * Render portlet.
	 */
	function render() {
		$this->__processBeforeRender();
		
		$this->__doRender();
		
		$this->__processAfterRender();
	}
	
	protected function __doRender() {
		$data = $this->config->getRenderMap($this->render);
		
		if($data == NULL) return NULL;
		
		$compiled = array();
		foreach($data['fields'] as $key => $value) {
			$value = 'fetch_'.$value;
			$compiled[$key] = $this->$value();
		}
		
		$this->context->getRenderer()->flush($compiled, $data['tpl']);
	}
	
	protected function __processBeforeRender() {
		$this->__processEvent(new PortletEvent($this, 'portlet_beforeRender'));
		if($this->render == NULL) $this->render = 'default';
	}
	
	protected function __processAfterRender() {
		$this->__processEvent(new PortletEvent($this, 'portlet_afterRender'));
	}
	
	protected function __processEvent(PortletEvent $e) {
		try {
			$svc = jf_Context::getContext()->getService('jf/event');

			$svc->notify($e);
		} catch (NoServiceException $nse) {
		}
	}

}

?>
