<?php

require_once(__DIR__ . '/src/MultiProcessPcntl.php');

// 任务数组参数，以此作为切分进程的量化依据，默认被调用方法的第一个参数
$task = range(1, 12);

// 默认 5 个进程，可以进行配置
// 设置的进程数是最大可以取到的进程数
// 会根据任务量 和 进程数进行灵活设定，会根据 count($task)/5 对每个进程内的任务数进行由多到少的分配，后面不足的将不再启动新的进程了
$sync = new yuli\pcntl\MultiProcessPcntl($task);
// $sync = new MultiProcessPcntl($task, 6);

// 支持调用类方法
// 支持传参
$sync->call('test', 'append arg');

function test($task, $arg) {
	var_dump($task, $arg);
	sleep(2);
}