<?php


    include "../vendor/autoload.php";
    use tcwei\smallTools\Page;

    $pageClass = new Page();
    //使用 pathInfo 模式： www.xxx.com/index/index/index/page/X
    $pageClass->urlType = 0;
    //自动补全路由参数，默认为3，要关闭就填写0； 开启时在 www.xxx.com/index 模式下点击分页会跳转：www.xxx.com/index/index/index/page/X   自动补全后面2个index凑够3个路由参数
    //$pageClass->autoAddUrlInfo = 3;
    //分页组件的样式 可选：flickr、blackRed、youtube、viciao 默认是 flickr
    //$pageClass->pageType = 'flickr';
    //鼠标移动到分页按钮时按钮的背景颜色
    //$pageClass->hoverBgColor = '#ff0000';
    //当前页码的颜色
    //$pageClass->nowPageFontColor = '#ff0084';
    //组件位置 left center right
    //$pageClass->pageAlign = 'center';
    $totle = 100;
    $pageHtml = $pageClass->getPageHtml($totle);

    echo '该demo不能直接运行，需要在支持pathInfo模式下的环境才能使用，例如使用 Thinkphp 框架 、 laravel 框架和 easyPHP';