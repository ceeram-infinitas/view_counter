<?php
	/**
	 * Events for the views behavior
	 * 
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * 
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas
	 * @subpackage Infinitas.views.events
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8
	 * 
	 * @author dogmatic69
	 * 
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	final class ViewCounterEvents extends AppEvents{
		public function  onSetupCache($event, $data = null) {
			return array(
				'name' => 'view_counts',
				'config' => array(
					'prefix' => 'view_counts.',
				)
			);
		}

		public function onRequireComponentsToLoad(){
			return array(
				'ViewCounter.ViewCounter'
			);
		}

		public function onRequireHelpersToLoad(){
			return array(
				'ViewCounter.ViewCounter'
			);
		}

		/**
		 * Called before blog post is echo'ed
		 */
		public function onBlogBeforeContentRender($event, $data) {
			if(!isset($event->Handler->params['models'][0]) || !in_array('view_count', (array)Configure::read('Blog.before'))){
				return false;
			}

			$Model = ClassRegistry::init(Inflector::camelize($event->Handler->params['plugin']).'.'.$event->Handler->params['models'][0]);
			$Model->Behaviors->attach('ViewCounter.Viewable');
			$views = $Model->getToalViews($class);

			return $this->__views($views);
		}

		/**
		 * Called after blog post is echo'ed
		 */
		public function onBlogAfterContentRender($event, $data) {
			if(!isset($event->Handler->params['models'][0]) || !in_array('view_count', (array)Configure::read('Blog.after'))){
				return false;
			}

			$Model = ClassRegistry::init(Inflector::camelize($event->Handler->params['plugin']).'.'.$event->Handler->params['models'][0]);
			$Model->Behaviors->attach('ViewCounter.Viewable');
			$views = $Model->getToalViews($data['post']['Post']['id']);

			return $this->__views($views, 'Blog.Post');
		}

		public function onAdminMenu($event){
			$menu['main'] = array(
				'Reports' => array('plugin' => 'view_counter', 'controller' => 'view_counts', 'action' => 'reports'),
				'Custom' => array('plugin' => 'view_counter', 'controller' => 'view_counts', 'action' => 'custom'),
			);

			return $menu;
		}

		/**
		 * Include some css
		 */
		public function onRequireCssToLoad($event, $data = null) {
			return array(
				'/view_counter/css/view_counter'
			);
		}

		/**
		 * generate some info for the number of views
		 * 
		 * @param int the number of views
		 * @return string the view text
		 */
		private function __views($views = 0, $model = null){
			$average = ClassRegistry::init('ViewCounter.ViewCount')->getAverage($model);
			
			switch($views){
				case 0:
					$text = __('Go on, be the first to view this post', true);
					break;

				case $views < $average / 10:
					$text = sprintf(__('%s views (new post)', true), $views);
					break;

				default:
					$text = sprintf(
						__('<span class="%s popular">%s views</span>', true),
						$views > $average * 1.25 ? 'extra' : '',
						$views
					);
					break;
			}
			
			return $text;
		}

		/**
		 * attach the reporting behavior for models with views
		 */
		public function onAttachBehaviors($event) {
			if(is_subclass_of($event->Handler, 'Model') && isset($event->Handler->_schema) && is_array($event->Handler->_schema)) {
				if ($event->Handler->hasField('views')) {
					$event->Handler->Behaviors->attach('ViewCounter.ViewableReporting');
				}
			}
		}

		/**
		 * @copydoc AppEvents::onRunCrons()
		 *
		 * The view counter crons clear out views from localhost.
		 * 
		 * @note You should disable crons if you want to test some functionality
		 * on localhost.
		 */
		public function onRunCrons($event) {
			ClassRegistry::init('ViewCounter.ViewCount')->clearLocalhost();
			return true;
		}
	}