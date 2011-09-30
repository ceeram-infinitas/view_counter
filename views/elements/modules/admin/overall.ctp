<?php
	/**
	 * Generats a graph for overall view stas.
	 * 
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.view_counter
	 * @subpackage Infinitas.view_counter.modules
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 *
	 * @author dogmatic69
	 * 
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	$viewStats = ClassRegistry::init('ViewCounter.ViewCount')->getGlobalTotalCount();
	if(empty($viewStats)){
		return;
	}
		?>
			<div class="dashboard half">
				<h1><?php echo sprintf(__('Overall Usage', true)); ?></h1>
				<?php
					$a = 'A';
					$labels = array();
					$count = count($viewStats);
					for($i = 0; $i < $count; $i++){
						$labels[] = $a++;
					}
					
					echo $this->Charts->draw(
						array(
							'bar' => array(
								'type' => 'vertical',
								'bar' => 'vertical'
							)
						),
						array(
							'data' => array_values($viewStats),
							'axes' => array(
								'x' => $labels,
								'y' => array('0', 100)
							),
							'size' => array(
								280,
								130
							),
							'color' => array(
								'background' => 'FFFFFF',
								'fill' => 'FFCC33',
								'text' => '989898',
								'lines' => '989898',
							),
							'spacing' => array(
								'padding' => 6
							),
							'tooltip' => 'Something Cool :: figure1: %s<br/>figure1: %s<br/>figure3: %s',
							'html' => array(
								'class' => 'chart'
							)
						)
					);
				?>
				<ul class="info">
					<?php
						$i = 0;
						foreach($viewStats as $model => $count){
							$model = prettyName(str_replace('.', '', Inflector::singularize($model)));
							?><li><?php echo sprintf(__('%s: %s %s views', true), $labels[$i], $count, $model); ?></li><?php
							++$i;
						}
					?>
					<li><?php echo sprintf(__('%s views in total', true), array_sum($viewStats)); ?></li>
				</ul>
			</div>
		<?php
?>