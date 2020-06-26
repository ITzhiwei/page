<?php
    include "../vendor/autoload.php";
    use tcwei\smallTools\Page;
    $pageClass = new Page();
    //开启 ajax 模式
    $pageClass->isAajx = true;
    //分页组件的样式 可选：flickr、blackRed、youtube、viciao 默认是 flickr
    //$pageClass->pageType = 'flickr';
    //鼠标移动到分页按钮时按钮的背景颜色
    //$pageClass->hoverBgColor = '#ff0000';
    //当前页码的颜色
    //$pageClass->nowPageFontColor = '#ff0084';
    //组件位置 left center right
    //$pageClass->pageAlign = 'center';
    //前端进行ajax分页请求数据的函数名，需要前端定义该函数； 要在 ajax 的回调内执行 pageAjaxLock = true; 进行解锁，解锁后才能进行下一次ajax分页触发，这是防止用户多次重复点击
    $pageClass->ajaxFunctionName = 'getList()';
    //假设有100列数据
    $totle = 100;
    $pageHtml = $pageClass->getPageHtml($totle);
    /**
     * 文档：https://github.com/ITzhiwei/page
     * param int $totle 信息总行数 count()
     * param int $onePageDisplayNum 每页显示条数
     * param int $showNumList 是否显示中间的 1 ... 4 5 6 ... 99； 0不显示，必须大于2，填写多少则显示多少个页码按钮，单数
     * param bool $showNumListType 决定 $showNumList 的模式，true时，会在 $showNumList 后面显示...尾页按钮，默认显示
     * param bool $showText 是否显示行数页数等文字信息，默认不显示
     * param bool $showPrevNext 是否显示 上一页、下一页 俩个按钮，默认显示
     * param bool $showHome 是否显示 首页、尾页 俩个按钮，默认显示
     * param bool $showSelect 是否显示下拉选择页码，默认不显示
     * param string $url 自定义跳转URL，如： /users.php?page=     /users/page/   方法会在自定义的URL后面追加页码数，所以不要在后面带上page参数
     * return string 若只有一页，则返回空字符串
      public function getPageHtml($totle, $onePageDisplayNum = 10, $showNumList = 7, $showNumListType = true, $showText = false, $showPrevNext = true , $showHome = true, $showSelect = false, $url = null){
    */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>demo - ajax 模式</title>
</head>
<body>
    <?=$pageHtml?>
</body>
    <script src="jquery-1.9.1.min.js"></script>
    <script>
        //前端自定义的ajax请求函数
        function getList(){
            console.log('请求的页码：'+pageNum);
            $.post(
                //url连接，写真实需要请求分页的连接
                '',
                {
                    //pageNum 是全局的页码变量，直接使用即可。至于其他参数根据需要前端自己传
                    page:pageNum
                },
                function(res){
                    //需要进行解锁,防止用户多次重复点击的锁
                    pageAjaxLock = true;
                }
            );
        }
    </script>
</html>

