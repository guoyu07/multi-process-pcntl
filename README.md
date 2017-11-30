## 个人声明

> 作者：于立（wx/yulichenr）  
> 敬告：联系我，请注明来源和来意  
> 本人开发有很多年了，但是很少整理分享，如今趁着清闲就为大家服务了，希望更多地体验到编程带来的快乐。  
> 职场多年下来，技术也算是逐渐地有些积累，但是更重要的是对自身有了更加合理的人生定位。或许，人生的意义，就在于此处的感悟吧。  
> 有对 阅读和写作; 自由工作者; 对大数据信息有领悟者 ，欢迎叨扰。

## multi-process-pcntl

基于PCNTL的PHP并发处理 封装类

***

## 使用说明

整理相关的一些资料，封装了下基于 PCNTL 的多进程

生产环境已经校验了，可以放心大胆地使用

## 引入说明

```bash
$ composer require yuli/multi-process-pcntl
```

## 使用方法

```
// 任务数组参数，以此作为切分进程的量化依据，默认被调用方法的第一个参数
$task = range(1, 12);

// 默认 5 个进程，可以进行配置
// 设置的进程数是最大可以取到的进程数
// 会根据任务量 和 进程数进行灵活设定，会根据 count($task)/5 对每个进程内的任务数进行由多到少的分配，后面不足的将不再启动新的进程了
$sync = new MultiProcessPcntl($task);
// $sync = new MultiProcessPcntl($task, 6);

// 支持调用类方法
// 支持传参
$sync->call('test', 'append arg');
```

![](https://github.com/shxingzhe/multi-process-pcntl/blob/master/multi-demo.png)

## 一些相关知识点

PHP本身不支持多进程，但基于Linux的PHP扩展PCNTL却可以提供多进程编程。

[PCNTL 函数](http://php.net/manual/zh/book.pcntl.php) - PHP 官网手册中对PCNTL的说明，更细化的需求可以研究深化。

***
