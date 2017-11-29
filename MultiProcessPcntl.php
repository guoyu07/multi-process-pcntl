<?php

/**
 * 
 * MultiProcessPcntl
 * 基于PCNTL的PHP并发处理 封装类
 * 
 * @author 			于立（wx/yulichenr）
 * 
 */
class MultiProcessPcntl {

	function __construct($task = array(), $workers = 5) {

		// 一个需要执行的任务数组参数，以此作为切分进程的量化依据，默认被调用方法的第一个参数
		$this->task = $task;

		// 进程数，默认为 5
		$this->workers = $workers;

	}

	/**
	 * 执行需要调用的函数
	 * @param callable $func The function to execute
	 */
	public function call(callable $func, ...$args) {

		if (!is_array($this->task) || empty($this->task)) {
			return;
		}

		$forks = $this->fork($this->task, $this->workers);

		$processIds = [];
		foreach ($forks as $i => $fork) {
			$processIds[$i] = pcntl_fork();

			switch ($processIds[$i]) {
			case -1:
				echo "fork failed : {$i} " . PHP_EOL;
				exit;
			case 0:
		        try {
					$func($fork, ...$args);
		        } catch (\Throwable $e) {
		        	echo $e->getMessage() . PHP_EOL;
		        }
				exit;
			default:
				break;
			}
		}

		while (count($processIds) > 0) {
			$mypid = pcntl_waitpid(-1, $status, WNOHANG);
			
			foreach ($processIds as $key => $pid) {
				if ($mypid == $pid || $mypid == -1) {
					unset($processIds[$key]);
				}
			}
		}

	}

	/**
	 * 将任务切成N份子任务
	 * @param  array $task    需要切分的任务
	 * @param  int $workers 切分数
	 * @return array
	 */
	protected function fork($task, $workers) {

		$forks = array();

		$num = $workers;
		for ($i = 0; $i < $workers; $i++) {
			if (!is_array($task) || empty($task)) break;

			$subForks = array();
			$avg = ceil(count($task) / $num--);
			for ($j = 0; $j < $avg; $j++) {
				array_push($subForks, array_shift($task));
			}

			array_push($forks, $subForks);
		}

		return $forks;

	}

}
?>