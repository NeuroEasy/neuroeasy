<?php



App::uses('PaginatorHelper', 'View/Helper');



/*

* Essa classe foi criado para criar funções e extender a classe Paginator para se adequar com o bootstrap

* Thomas Kanzig :: thomas.kanzig@gmail.com

*/



class MyPaginatorHelper extends PaginatorHelper{

	

	public function numbers($options = array()) {

		if ($options === true) {

			$options = array(

				'before' => ' | ', 'after' => ' | ', 'first' => 'first', 'last' => 'last'

			);

		}



		$defaults = array(

			'tag' => 'span', 'before' => null, 'after' => null, 'model' => $this->defaultModel(), 'class' => null,

			'modulus' => '8', 'separator' => ' | ', 'first' => null, 'last' => null, 'ellipsis' => '...', 'currentClass' => 'current'

		);

		$options += $defaults;



		$params = (array)$this->params($options['model']) + array('page' => 1);

		unset($options['model']);



		if ($params['pageCount'] <= 1) {

			return false;

		}



		extract($options);

		unset($options['tag'], $options['before'], $options['after'], $options['model'],

			$options['modulus'], $options['separator'], $options['first'], $options['last'],

			$options['ellipsis'], $options['class'], $options['currentClass']

		);



		$out = '';



		if ($modulus && $params['pageCount'] > $modulus) {

			$half = intval($modulus / 2);

			$end = $params['page'] + $half;



			if ($end > $params['pageCount']) {

				$end = $params['pageCount'];

			}

			$start = $params['page'] - ($modulus - ($end - $params['page']));

			if ($start <= 1) {

				$start = 1;

				$end = $params['page'] + ($modulus - $params['page']) + 1;

			}



			if ($first && $start > 1) {

				$offset = ($start <= (int)$first) ? $start - 1 : $first;

				if ($offset < $start - 1) {

					$out .= $this->first($offset, compact('tag', 'separator', 'ellipsis', 'class'));

				} else {

					$out .= $this->first($offset, compact('tag', 'separator', 'class', 'ellipsis') + array('after' => $separator));

				}

			}



			$out .= $before;



			for ($i = $start; $i < $params['page']; $i++) {

				$out .= $this->Html->tag($tag, $this->link($i, array('page' => $i), $options), compact('class')) . $separator;

			}



			if ($class) {

				$currentClass .= ' ' . $class;

			}

			$out .= $this->Html->tag($tag, $params['page'], array('class' => $currentClass));

			if ($i != $params['pageCount']) {

				$out .= $separator;

			}



			$start = $params['page'] + 1;

			for ($i = $start; $i < $end; $i++) {

				$out .= $this->Html->tag($tag, $this->link($i, array('page' => $i), $options), compact('class')) . $separator;

			}



			if ($end != $params['page']) {

				$out .= $this->Html->tag($tag, $this->link($i, array('page' => $end), $options), compact('class'));

			}



			$out .= $after;



			if ($last && $end < $params['pageCount']) {

				$offset = ($params['pageCount'] < $end + (int)$last) ? $params['pageCount'] - $end : $last;

				if ($offset <= $last && $params['pageCount'] - $end > $offset) {

					$out .= $this->last($offset, compact('tag', 'separator', 'ellipsis', 'class'));

				} else {

					$out .= $this->last($offset, compact('tag', 'separator', 'class', 'ellipsis') + array('before' => $separator));

				}

			}



		} else {

			$out .= $before;



			for ($i = 1; $i <= $params['pageCount']; $i++) {

				if ($i == $params['page']) {

					if ($class) {

						$currentClass .= ' ' . $class;

					}

					//$out .= $this->Html->tag($tag, $i, array('class' => $currentClass));

					$out .= "<".$tag." class=\"".$currentClass."\">".$i."</".$tag.">";

				} else {

					$out .= $this->Html->tag($tag, $this->link($i, array('page' => $i), $options), compact('class'));

				}

				if ($i != $params['pageCount']) {

					$out .= $separator;

				}

			}



			$out .= $after;

		}



		return $out;

	}





	public function _pagingLink($which, $title = null, $options = array(), $disabledTitle = null, $disabledOptions = array()) {

		$check = 'has' . $which;

		$_defaults = array(

			'url' => array(), 'step' => 1, 'escape' => true,

			'model' => null, 'tag' => 'span', 'class' => strtolower($which)

		);

		$options = array_merge($_defaults, (array)$options);

		$paging = $this->params($options['model']);

		if (empty($disabledOptions)) {

			$disabledOptions = $options;

		}



		if (!$this->{$check}($options['model']) && (!empty($disabledTitle) || !empty($disabledOptions))) {

			if (!empty($disabledTitle) && $disabledTitle !== true) {

				$title = $disabledTitle;

			}

			$options = array_merge($_defaults, (array)$disabledOptions);

		} elseif (!$this->{$check}($options['model'])) {

			return null;

		}



		foreach (array_keys($_defaults) as $key) {

			${$key} = $options[$key];

			unset($options[$key]);

		}

		$url = array_merge(array('page' => $paging['page'] + ($which == 'Prev' ? $step * -1 : $step)), $url);



		if ($this->{$check}($model)) {

			return $this->Html->tag($tag, $this->link($title, $url, array_merge($options, compact('escape', 'model'))), compact('class'));

		} else {

			unset($options['rel']);

			return "<li class=\"".$disabledOptions['class']."\"><a href=\"#\">".$title."</a></li>"; 

			//return $this->Html->tag($tag, $this->link($title), array_merge($options, compact('escape', 'class')));

		}

	}	

	

}







?>