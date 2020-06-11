<?php
namespace lipowei\smallTools;
class Page{

    /**
     * @var url的模式，0：pathInfo模式，如：page/2    1：普通模式，如：page=2  默认pathInfo模式
     */
    public $urlType = 0;
    private $url = null;

    /**
     * @var 样式，可选：flickr、blackRed、youtube、viciao
     */
    public $pageType = 'flickr';
    /**
     * @var string，鼠标移上按钮时按钮区域背景颜色 默认 #00a0e9
     */
    public $hoverBgColor = null;
    /**
     * @var string，鼠标移上按钮时按钮文字颜色 默认：#ffffff
     */
    public $hoverFontColor = null;
    /**
     * @var string，当前页码的文字颜色 默认 ff0084
     */
    public $nowPageFontColor = null;
    /**
     * @var 导航位置，如：left、center、right；可不设置，使用默认位置
     */
    public $pageAlign = null;

    private $autoAddUrlInfo = null;
    private $requestUri = null;
    /**
     * Page constructor.
     * @param bool $autoAddUrlInfo PATHINFO模式下是否自动补全路由参数，默认补全； 补全：如 www.xxx.com/admin 会自动补全成 www.xxx.com/admin/index/index  不足3个路由参数的后面自动补全3个路由参数，如果要识别补全的数量不是3，传入其他数字即可
     * @param null $requestUri 如果是swoole生成的服务，需要传入 $request->server['request_uri']，非swoole则无需理会该参数
     */
    public function __construct($autoAddUrlInfo = 3, $requestUri = null){
        if($autoAddUrlInfo){
            $this->autoAddUrlInfo = $autoAddUrlInfo;
        }else{
            $this->autoAddUrlInfo = false;
        }
        if($requestUri !== null){
            $this->requestUri = $requestUri;
        };
    }


    /**
     * 假设有99页，下面的参数说明：
     * @param int $totle 信息总行数 count()
     * @param int $onePageDisplayNum 每页显示条数
     * @param int $showNumList 是否显示中间的 1 ... 4 5 6 ... 99； 0不显示，必须大于2，填写多少则显示多少个页码按钮，单数
     * @param bool $showNumListType 决定 $showNumList 的模式，true时，会在 $showNumList 后面显示...尾页按钮，默认显示
     * @param bool $showText 是否显示行数页数等文字信息，默认不显示
     * @param bool $showPrevNext 是否显示 上一页、下一页 俩个按钮，默认显示
     * @param bool $showHome 是否显示 首页、尾页 俩个按钮，默认显示
     * @param bool $showSelect 是否显示下拉选择页码，默认不显示
     * @param string $url 自定义跳转URL，如： /users.php?page=     /users/page/   方法会在自定义的URL后面追加页码数，所以不要在后面带上page参数
     * @return string 若只有一页，则返回空字符串
     */
    public function getPageHtml($totle, $onePageDisplayNum = 10, $showNumList = 7, $showNumListType = true, $showText = false, $showPrevNext = true , $showHome = true, $showSelect = false, $url = null){
        $this->url = $url;

        $pageType = $this->pageType;
        $pageHtml = $this->styleSelect($pageType);

        if($url == null) {
            $url = $this->getUrl();
        }
        //获取当前页码
        $nowPage = $this->getNowPage();
        //上一页码数
        $prevPage = $nowPage - 1;
        //下一页码数
        $nextPage = $nowPage + 1;
        //计算总页码数
        $allPageNum = ceil($totle/$onePageDisplayNum);
        if($allPageNum > 1) {
            //当前页码
            $nowPage = min($allPageNum, $nowPage);
            //查看是否显示，行数页数等文字信息
            if ($showText) {
                //当前页数显示的开始行数
                $limitStart = ($nowPage - 1) * $onePageDisplayNum;
                $pageHtml .= '<span class="disabled">' . ($totle ? ($limitStart + 1) : 0) .
                    '-' . min($limitStart + $onePageDisplayNum, $totle) .
                    "/$totle 记录</span>";
                //"/$totle 记录</span><span class='disabled'>$nowPage/$allPageNum 页</span>";
            };
            if($showHome) {
                if ($nowPage > 1) {
                    $pageHtml .= "<a href='{$url}1'>首页</a>";
                } else {
                    $pageHtml .= '<span class="disabled">首页</span>';
                }
            };
            //判断是否显示 上一页、下一页 按钮
            if($showPrevNext) {
                if ($nowPage > 1) {
                    $pageHtml .= "<a href='$url{$prevPage}'>上一页</a>";
                } else {
                    $pageHtml .= '<span class="disabled">上一页</span>';
                }
            };

            //判断是否显示中间数字按钮，默认显示
            if($showNumList){
                //强制大于等于3
                $showNumList = $showNumList<3?3:$showNumList;
                //强制为单数
                $showNumList = $showNumList%2?$showNumList:$showNumList+1;

                //计算俩侧页码按钮数
                $leftRgihtButton = ($showNumList - 1)/2;
                //根据当前页码数计算起始页码数
                $startPage = $nowPage - $leftRgihtButton;
                $startPage = $startPage>$allPageNum-$showNumList?$allPageNum-$showNumList+1:$startPage;
                $startPage = $startPage>0?$startPage:1;
                $forStartPage = $startPage;
                for($i=0;$i<$showNumList;$i++){
                    if($forStartPage == $nowPage){
                        $pageHtml .= '<span class="current">'.$forStartPage.'</span>';
                    }else{
                        $pageHtml .= '<a href="'.$url.$forStartPage.'">'.$forStartPage.'</a>';
                    }
                    $forStartPage++;
                    //若已经没有更多页数，直接退出拼接
                    if($forStartPage > $allPageNum){
                        break;
                    }
                }
                if($showNumListType) {
                    $forStartPage--;
                    if ($forStartPage < $allPageNum) {
                        if ($forStartPage + 1 < $allPageNum) {
                            //带有...
                            $pageHtml .= '...<a href="' . $url . $allPageNum . '">' . $allPageNum . '</a>';
                        } else {
                            //直接最近最后页码的按钮
                            $pageHtml .= '<a href="' . $url . $allPageNum . '">' . $allPageNum . '</a>';
                        }
                    };
                }
            }

            //判断是否显示上一页下一页
            if($showPrevNext) {
                if ($nextPage <= $allPageNum) {
                    $pageHtml .= "<a href=\"$url{$nextPage}\">下一页</a>";
                } else {
                    $pageHtml .= '<span class="disabled">下一页</span>';
                }
            };
            //默认显示首页、尾页按钮
            if($showHome) {
                if ($nextPage <= $allPageNum) {
                    $pageHtml .= "<a href=\"{$url}$allPageNum\">尾页</a>";
                } else {
                    $pageHtml .= '<span class="disabled">尾页</span>';
                }
            }
            //判断是否显示下拉列表
            if($showSelect){
                $pageHtml .= '<span class="weiSelect">跳至 <select name="topage" onchange="window.location=\''.$url.'\' + this.value" >\n';
                for($i=1; $i<=$allPageNum; $i++){
                    if($i == $nowPage){
                        $pageHtml .= "<option value=\"$i\" selected>$i</option>\n";
                    }else{
                        $pageHtml .= "<option value=\"$i\">$i</option>\n";
                    }
                }
                $pageHtml .= "</select> 页</span>";
            }
            $pageHtml .= '</div></div>';
            return $pageHtml;

        }else{
            return '';
        }
    }

    /**
     * 获取url
     * @param int $page 设置页码，默认为0，无页码，获取返回的url再在后面加上页码即可。如：$url = getUrl().$page;
     * @return string url
     */
    public function getUrl($page = 0){
        $url = $this->url;
        $urlType = $this->urlType;
        if($url == null){
            if($this->requestUri == null) {
                $url = $_SERVER["REQUEST_URI"];
            }else{
                $url = $this->requestUri;
            }
        };
        $parse_url = parse_url($url);
        if($urlType){
            //GET模式，URL分析
            if(!empty($parse_url['query'])){
                //检测是否存在page参数，存在清空
                $url_query = $parse_url['query'];
                $url_query = preg_replace("/(^|&)page=\d*?/Ui","",$url_query);
                //将处理后的URL的查询字串替换原来的URL的查询字串：
                $url = str_replace($parse_url["query"], $url_query, $url);
                $parse_url = parse_url($url);
                if(!empty($parse_url['query'])){
                    $url .= '&page=';
                }else{
                    $url .= 'page=';
                }
                $urlString = $url;
            }else{
                $urlString = $url.'?page=';
            }
        }else{
            //PATHINFO模式,URL分析,如果关闭了自动补全路由，那么URL不能缺少路由参数，如要访问 /article/list/index 时，不能省略index写成 /article/list
            $auto = $this->autoAddUrlInfo;
            //检测是否存在page参数，存在清空
            $url_query = $parse_url['path'];
            $url_query = preg_replace("/\/page\/\d*?/Ui","",$url_query);
            $url = str_replace($parse_url["path"], $url_query, $url);
            if($auto){
                //检测补全路由
                $pathInfoArr = explode('/', $url);
                for($i=1;$i<=$auto;$i++){
                    if(empty($pathInfoArr[$i])){
                        $pathInfoArr[$i] = 'index';
                    }
                }
                $url = implode('/', $pathInfoArr);
            }
            $urlString = $url.'/page/';
        }
        if(!$page){
            return $urlString;
        }else{
            return $urlString.$page;
        }
    }

    /**
     * 获取当前的页码
     * @return int page
     */
    private function getNowPage(){
        $urlType = $this->urlType;
        if($urlType){
            if(!empty($_GET['page'])){
                $nowPage = (int)$_GET['page'];
                $nowPage = $nowPage<1?1:$nowPage;
            }else{
                $nowPage = 1;
            }
        }else{
            $url = $_SERVER["REQUEST_URI"];
            $urlArr = parse_url($url);
            $pathInfo = $urlArr['path'];
            $pathInfoArr = explode('/', $pathInfo);
            $nowPage = 1;
            foreach ($pathInfoArr as $key=>$value){
                if($value == 'page'){
                    if(!empty($pathInfoArr[$key+1]) && (int)$pathInfoArr[$key+1] > 0){
                        //执行到这里也不中断执行，防止路由参数与page重复
                        $nowPage = (int)$pathInfoArr[$key+1];
                    }
                }
            }
        }
        return $nowPage;
    }

    /**
     * 样式选择器
     * @param $type
     * @return string
     */
    private function styleSelect($type){
        $pageHtml = '';
        switch ($type){
            case 'flickr':
                $nowPageFontColor = $this->nowPageFontColor ?: '#ff0084';
                $hoverFontColor = $this->hoverFontColor ?: '#fff';
                $hoverBgColor = $this->hoverBgColor ?: '#00a0e9';
                $align = $this->pageAlign ?: 'center';
                $pageHtml = '
                        <style>
                            DIV.flickr {PADDING: 3px;MARGIN: 3px; TEXT-ALIGN: '.$align.'; font-size:13px;}
                            DIV.flickr A {BORDER: #dedfde 1px solid; PADDING: 10PX 15px; BACKGROUND-POSITION: 50% bottom; COLOR: #666666; MARGIN-RIGHT: 3px; TEXT-DECORATION: none;}
                            DIV.flickr A:hover {BACKGROUND-IMAGE: none; COLOR: '.$hoverFontColor.';  BACKGROUND-COLOR: '.$hoverBgColor.';}
                            DIV.flickr SPAN.current {PADDING: 2px 6px; FONT-WEIGHT: bold; COLOR: '.$nowPageFontColor.'; MARGIN-RIGHT: 3px;}
                            DIV.flickr SPAN.disabled {PADDING:2px 6px; COLOR: #adaaad; MARGIN-RIGHT: 3px;}
                            DIV.flickr .weiSelect{margin-left:2px;}
                        </style>
                        ';
                break;
            case 'blackRed':
                $nowPageFontColor = $this->nowPageFontColor ?: '#fff';
                $hoverFontColor = $this->hoverFontColor ?: '#fff';
                $hoverBgColor = $this->hoverBgColor ?: '#ec5210';
                $align = $this->pageAlign ?: 'left';
                $pageHtml = '
                        <style>
                            DIV.blackRed {FONT-SIZE: 11px; COLOR: #fff; FONT-FAMILY: Tahoma, Arial, Helvetica, Sans-serif; BACKGROUND-COLOR: #3e3e3e; height:30px; line-height:30px;TEXT-ALIGN: '.$align.';}
                            DIV.blackRed A {PADDING:2px 5px; MARGIN: 2px; COLOR: #fff; BACKGROUND-COLOR: #3e3e3e; TEXT-DECORATION: none}
                            DIV.blackRed A:hover {COLOR: '.$hoverFontColor.'; BACKGROUND-COLOR: '.$hoverBgColor.'}
                            DIV.blackRed A:active {COLOR: #fff; BACKGROUND-COLOR: #ec5210}
                            DIV.blackRed SPAN.current {PADDING:2px 5px;FONT-WEIGHT: bold; MARGIN: 2px; COLOR: '.$nowPageFontColor.'; BACKGROUND-COLOR: #313131}
                            DIV.blackRed SPAN.disabled {PADDING: 2px 5px;MARGIN: 2px; COLOR: #868686; BACKGROUND-COLOR: #3e3e3e}
                            DIV.flickr .weiSelect{margin-left:3px;}
                        </style>
                        ';
                break;
            case 'youtube':
                $nowPageFontColor = $this->nowPageFontColor ?: '#000';
                $hoverFontColor = $this->hoverFontColor ?: '#fff';
                $hoverBgColor = $this->hoverBgColor ?: '#00a0e9';
                $align = $this->pageAlign ?: 'right';
                $pageHtml = '
                        <style>
                            DIV.youtube {PADDING:4px 6px 4px 0px;FONT-SIZE: 13px; COLOR: #313031; FONT-FAMILY: Arial, Helvetica, sans-serif; TEXT-ALIGN: '.$align.'}
                            DIV.youtube A {PADDING:1px 3px;FONT-WEIGHT: bold; MARGIN: 0px 1px; COLOR: #0030ce; TEXT-DECORATION: underline}
                            DIV.youtube A:hover {COLOR: '.$hoverFontColor.'; BACKGROUND-COLOR: '.$hoverBgColor.'}
                            DIV.youtube SPAN.current {PADDING:1px 2px; COLOR: '.$nowPageFontColor.'; BACKGROUND-COLOR: #fff}
                            DIV.youtube SPAN.disabled {margin-right:5px;font-size: 13px}
                            DIV.flickr .weiSelect{margin-left:3px;}
                        </style>
                        ';
                break;
            case 'viciao':
                $nowPageFontColor = $this->nowPageFontColor ?: '#000';
                $hoverFontColor = $this->hoverFontColor ?: '';
                $hoverBgColor = $this->hoverBgColor ?: '';
                $align = $this->pageAlign ?: 'center';
                $pageHtml = '
                        <style>
                            DIV.viciao {MARGIN-TOP: 20px; MARGIN-BOTTOM: 10px;TEXT-ALIGN: '.$align.'}
                            DIV.viciao A {BORDER: #8db5d7 1px solid; PADDING:2px 5px;COLOR: #000; MARGIN-RIGHT: 2px; TEXT-DECORATION: none}
                            DIV.viciao A:hover {BACKGROUND-COLOR: '.$hoverBgColor.';COLOR: '.$hoverFontColor.';}
                            DIV.viciao A:active {BORDER: red 1px solid;}
                            DIV.viciao SPAN.current {BORDER: #e89954 1px solid; PADDING:2px 5px;FONT-WEIGHT: bold; COLOR: '.$nowPageFontColor.'; MARGIN-RIGHT: 2px; BACKGROUND-COLOR: #ffca7d}
                            DIV.viciao SPAN.disabled {BORDER: #ccc 1px solid; PADDING:2px 5px;COLOR: #ccc; MARGIN-RIGHT: 2px;}
                            DIV.flickr .weiSelect{margin-left:3px;}
                        </style>
                        ';
                break;
        }
        $pageHtml .= '
                        <div class="lipoweiPageMain" style="user-select:none;"><div class="'.$type.'">
                        ';
        return $pageHtml;

    }


}
