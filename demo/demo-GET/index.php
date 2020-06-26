<?php


    include "../vendor/autoload.php";
    use tcwei\smallTools\Page;

    $pageClass = new Page();
    //urlTtpe = 1 即是选择GET模式
    $pageClass->urlType = 1;
    //分页组件的样式 可选：flickr、blackRed、youtube、viciao 默认是 flickr
    //$pageClass->pageType = 'flickr';
    //鼠标移动到分页按钮时按钮的背景颜色
    //$pageClass->hoverBgColor = '#ff0000';
    //当前页码的颜色
    //$pageClass->nowPageFontColor = '#ff0084';
    //组件位置 left center right
    //$pageClass->pageAlign = 'center';
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
    <title>demo - GET模式</title>
</head>
<body>
    <?=$pageHtml?>
</body>
</html>