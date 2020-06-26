# PHP分页类库，支持ajax模式和普通的跳转模式，内含demo例子
## 介绍
没有任何依赖，可在任何环境下使用，开箱即用 
简单灵活好用，多个样式可选，支持 ajax 模式 和 普通的跳转模式，如：[?|&]page=1 和 pathInfo /page/1 等模式  
点击跳转分页时不会漏掉其他url原有的参数   

## 安装
```
1、使用 composer 命令安装：composer require tcwei/page
2、直接在 src 找到 Page.php 类库文件，直接拖到你的类目录内，include 该文件可直接使用
```
## 使用
```
//可打开demo目录下查看不同模式的使用示例 如果使用ajax模式，最好先看下demo-ajax
require_once 'vendor/autoload.php'; //这个是composer模式，如果不是composer则无需这行代码
use tcwei\smallTools\Page;
$pageClass = new Page();
//0 pathInfo 模式， 1是get模式  
$pageClass->urlType = 0;
$totle = 100;//总条数
$pageHtml = $pageClass->getPageHtml($totle);//这个pageHtml直接输出到html就可以显示分页
```
```
例子1 pathInfo模式：
$pageClass = new Page();
//选择分页样式： 可选用样式：flickr、blackRed、youtube、viciao
$pageClass->pageType = 'flickr';
//分页位置，如：左、中、右；可不设置，使用默认位置
$pageClass->pageAlign = 'center';
//鼠标移动到按钮时按钮的背景颜色设置,可不设置使用默认值
$pageClass->hoverBgColor = '#00a0e9';
//鼠标移动到按钮时按钮的页码数字颜色设置,可不设置使用默认值
$pageClass->hoverFontColor = '#fff';
//当前页码的字体颜色
$pageClass->nowPageFontColor = '#ff0084';
$totle = 100; 
//第2个参数是每页显示多少条数据，第3个参数是显示多少个分页按钮，第4个参数是显示...和最后一个页码
$pageHtml = $pageClass->getPageHtml($totle, 10, 7, true);
```
```
例子2 ajax 模式：请到 demo-ajax 中查看完整示例
include "../vendor/autoload.php";
use tcwei\smallTools\Page;
$pageClass = new Page();
//开启 ajax 模式
$pageClass->isAajx = true;
//前端进行ajax分页请求数据的函数名，需要前端定义该函数； 要在 ajax 的回调内执行 pageAjaxLock = true; 进行解锁，解锁后才能进行下一次ajax分页触发，这是防止用户多次重复点击
$pageClass->ajaxFunctionName = 'getList()';
//假设有100列数据
$totle = 100;
$pageHtml = $pageClass->getPageHtml($totle);
```  

## 参数介绍
* $pageClass->autoAddUrlInfo
```
默认为3
是否开启自动补全路由信息，仅在 pathInfo 模式下有效，即自动补全路由参数
不足3个的在后面自动追加index，如：/admin/index 跳转页码时会自动变成 /admin/index/index/page/X 会多添加一个/index补全3个路由参数
如果不需要自动补全传入0；
```
* $pageClass->requestUri
```
如果是 swoole 启动的服务，需要传入$pageClass->requestUri = $request->server['request_uri'] ，不是swoole直接忽略该参数
```
* $pageClass->isAjax
```
是否开启ajax模式，默认为false
```
* $pageClass->urlType
```
默认是0
设置URL的类型，是 pathInfo 模式，即 .../page/X
设置为1为普通模式，page是$_GET获取，即 ?page=X 或 &page=X
```
* $pageClass->pageType
```
默认 flickr
分页样式选择，可选样式：flickr、blackRed、youtube、viciao
也可以自定义样式，如：$pageClass->pageType = 'myStyle';
前端写样式：
.tcweiPageMain .myStyle{}//分页main样式
.tcweiPageMain .myStyle a{}//跳转按钮样式
.tcweiPageMain .myStyle a:hover{}//鼠标移到按钮时的样式
.tcweiPageMain .myStyle span.current{}//当前页码的样式
.tcweiPageMain .myStyle a:active{}//点击按钮时的样式
.tcweiPageMain .myStyle span.disabled{}//不可点击的按钮样式
```
* $pageClass->pageAlign
```
每个样式的默认位置不一样
分页组件的位置，可选：left 、 center 、 right 
```
* $pageClass->hoverBgColor
```
鼠标移动到按钮时按钮的背景颜色设置,可不设置使用默认值
```
* $pageClass->hoverFontColor
```
鼠标移动到按钮时按钮的页码数字颜色设置,可不设置使用默认值
```
* $pageClass->nowPageFontColor
```
当前页码的字体颜色
```
* $pageClass->getPageHtml() 共有9个参数
```
getPageHtml($totle, $onePageDisplayNum = 10, $showNumList = 7, $showNumListType = true, $showText = false, $showPrevNext = true, $showHome = true, $showSelect = false, $url = null)
1)$totle int 传入数据的总条数
2)$onePageDisplayNum int 每页显示条数
3)$showNumList int 数字页码按钮显示几个
4)$showNumListType bool 决定 $showNumList 的模式，true时，会在 $showNumList 后面显示...尾页按钮，默认显示
5)$showText bool 是否显示行数页数等文字信息，默认不显示，如上面说的 “1-10/100 记录” 这个数据显示
6)$showPrevNext bool 是否显示 上一页、下一页 俩个按钮，默认显示
7)$showHome bool 是否显示 首页、尾页 俩个按钮，默认显示
8)$showSelect bool 是否显示下拉选择页码的部分，默认不显示
9)$url string 传入自定义跳转的url，如传入： https://www.baidu.com?page=  或者 https://www.baidu.com/page/ 后面的页码会自动添加
```
# 结语
如果喜欢，到 https://github.com/ITzhiwei/page 给个Star吧~ >.<