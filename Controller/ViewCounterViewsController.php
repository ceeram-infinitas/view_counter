<?php
	class ViewCounterViewsController extends ViewCounterAppController {
		public $helpers = array(
			'Charts.Charts' => array(
				'Google.GoogleStatic'
			)
		);

		public function admin_custom(){
			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'start_date',
				'end_date',
				'model' => $this->ViewCounterView->uniqueList('model'),
				'year' => $this->ViewCounterView->uniqueList('year'),
				'month' => $this->ViewCounterView->uniqueList('month'),
				'day' => $this->ViewCounterView->uniqueList('day'),
				'hour' => $this->ViewCounterView->uniqueList('hour'),
				'continent_code' => $this->ViewCounterView->uniqueList('continent_code'),
				'country_code' => $this->ViewCounterView->uniqueList('country_code'),
				'country' => $this->ViewCounterView->uniqueList('country'),
				'city'
			);

			$this->set(compact('filterOptions'));
		}

		/**
		 * Generate reports on views
		 *
		 * Create pretty graphs of all the data collected for the
		 */
		public function admin_reports(){
			$overview = $yearOnYear = $monthOnMonth = $weekOnWeek = $byDay =
			$dayOfWeek = $hourOnHour = $relatedModel = $byRegion = $foreignKeys =
			$allModels = null;

			$conditions = array();
			
			
			if(isset($this->params['named']['ViewCount.foreign_key'])){
				$conditions['ViewCount.foreign_key'] = $this->params['named']['ViewCount.foreign_key'];
			}

			if(isset($this->params['named']['ViewCount.model'])){
				$conditions['ViewCount.model'] = $this->params['named']['ViewCount.model'];
			}

			$overview = $this->ViewCounterView->reportOverview($conditions);

			$yearOnYear = $this->ViewCounterView->reportYearOnYear($conditions);
			$monthOnMonth = $this->ViewCounterView->reportMonthOnMonth($conditions);
			$weekOnWeek = $this->ViewCounterView->reportWeekOnWeek($conditions);
			$byDay  = $this->ViewCounterView->reportByDayOfMonth($conditions);
			$dayOfWeek = $this->ViewCounterView->reportDayOfWeek($conditions);
			$hourOnHour = $this->ViewCounterView->reportHourOnHour($conditions);
			$byRegion = $this->ViewCounterView->reportByRegion($conditions);
			
			$this->set(compact('overview', 'yearOnYear', 'monthOnMonth', 'weekOnWeek', 'byWeek', 'byDay', 'dayOfWeek', 'hourOnHour', 'byRegion'));

			if(isset($this->params['named']['ViewCount.model']) && isset($this->params['named']['ViewCount.foreign_key'])){
				$relatedModel = $this->ViewCounterView->reportPopularRows($conditions, $this->params['named']['ViewCount.model']);
				if(isset($relatedModel[0])){
					$relatedModel = $relatedModel[0];
				}

				$byRegion = $this->ViewCounterView->reportByRegion($conditions);
				$this->set(compact('relatedModel', 'byRegion'));
			}

			else if(isset($this->params['named']['ViewCount.model']) && !isset($this->params['named']['ViewCount.foreign_key'])){
				$foreignKeys = $this->ViewCounterView->reportPopularRows($conditions, $this->params['named']['ViewCount.model']);
				$this->set(compact('foreignKeys', 'byRegion'));
			}
			
			else if(!isset($this->params['named']['ViewCount.model']) && !isset($this->params['named']['ViewCount.foreign_key'])){
				$allModels = $this->ViewCounterView->reportPopularModels();
				$this->set(compact('allModels'));
			}
		}
	}