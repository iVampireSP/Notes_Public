<?php
function mduiHeader($subtitle)
{
    $sitename = SITENAME;
    echo <<<EOF
    <header class="mdui-appbar-fixed">
    <div class="mdui-color-theme mdui-appbar mdui-toolbar">
        <span style="border-radius: 100%" class="mdui-btn mdui-btn-icon mdui-ripple mdui-ripple-white"
            mdui-drawer="{target: '#main-drawer', swipe: true}"><i class="mdui-icon material-icons">menu</i></span>
        <span class="mdui-typo-headline mdui-hidden-xs" onclick="loadIndex()">$sitename</span>
        <span class="mdui-typo-title" id="subTitle">$subtitle</span>
        <span onclick="change_style()" style="position: absolute; right: 5px; border-radius: 100%" mdui-tooltip="{content: '日夜配色', position: 'bottom'}" class="mdui-btn mdui-btn-icon mdui-ripple mdui-ripple-white"><i class="mdui-icon material-icons">&#xe3a9;</i></span>
        <span onclick="getID()" style="position: absolute; right: 65px; border-radius: 100%" mdui-tooltip="{content: '获取ID', position: 'bottom'}" class="mdui-btn mdui-btn-icon mdui-ripple mdui-ripple-white"><i class="mdui-icon material-icons">&#xe853;</i></span>
    </div>
    <div id="topload" style="display: none; position: absolute; buttom: 10px;" class="mdui-progress">
        <div class="mdui-progress-indeterminate"></div>
    </div>
</header>
EOF;
}
function mduiMenu()
{
    $username = $_SESSION['user'];
    if (isset($_REQUEST['noteid'])) {
        $noteid = $_REQUEST['noteid'];
        $editnote = "<a href=\"editnote.php?noteid=$noteid\" class=\"mdui-list-item mdui-ripple\">编辑当前记事本</a>";
        $delnote = "<a href=\"delnote.php?noteid=$noteid\" class=\"mdui-list-item mdui-ripple\">删除当前记事本</a>";
    }
    echo <<<EOF
    <div class="mdui-drawer" id="main-drawer">
        <div class="mdui-list" mdui-collapse="{accordion: true}" style="margin-bottom: 76px;">
            <div class="mdui-collapse-item mdui-collapse-item-open">
                <div class="mdui-collapse-item-header mdui-list-item mdui-ripple">
                    <i class="mdui-list-item-icon mdui-icon material-icons mdui-text-color-blue">home</i>
                    <div class="mdui-list-item-content">菜单</div>
                    <i class="mdui-collapse-item-arrow mdui-icon material-icons">keyboard_arrow_down</i>
                </div>
                <div id="menu" class="mdui-collapse-item-body mdui-list">
                    <span class="mdui-list-item mdui-ripple" onclick="loadIndex()">记事本</span>
                    <span class="mdui-list-item mdui-ripple" onclick="loadAdd()">新增记事本</span>
                    <a href="logout.php" class="mdui-list-item mdui-ripple">注销</a>
                </div>
            </div>
            
            <div class="mdui-collapse-item">
                <div class="mdui-collapse-item-header mdui-list-item mdui-ripple">
                    <i class="mdui-list-item-icon mdui-icon material-icons mdui-text-color-blue">menu</i>
                    <div class="mdui-list-item-content" onclick="loadCategorymenu()">分类</div>
                    <i class="mdui-collapse-item-arrow mdui-icon material-icons">keyboard_arrow_down</i>
                </div>
                <div id="categorys" class="mdui-collapse-item-body mdui-list"><span class="mdui-list-item mdui-ripple" onclick="loadAddCategory()">加载分类</span></div>
            </div>
        </div>
    </div>
EOF;
}
function mduiHead($title)
{
    $sitename = SITENAME;
    echo <<<EOF
    <title>$sitename - $title</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/mdui@1.0.0/dist/css/mdui.min.css" integrity="sha384-2PJ2u4NYg6jCNNpv3i1hK9AoAqODy6CdiC+gYiL2DVx+ku5wzJMFNdE3RoWfBIRP" crossorigin="anonymous" />
    
    <link rel="icon" href="https://ivampiresp.com/wp-content/uploads/2020/02/cropped-illust_78879291_20200207_181713-32x32.jpg" sizes="32x32" />
    <link rel="icon" href="https://ivampiresp.com/wp-content/uploads/2020/02/cropped-illust_78879291_20200207_181713-192x192.jpg" sizes="192x192" />
    <link rel="apple-touch-icon" href="https://ivampiresp.com/wp-content/uploads/2020/02/cropped-illust_78879291_20200207_181713-180x180.jpg" />
    <style type="text/css">
    .link {
        color: blue;
        text-decoration:none
    }
    </style>
EOF;
}
function mduiBody()
{
    echo '<body class="mdui-container mdui-drawer-body-left mdui-appbar-with-toolbar mdui-theme-primary-white mdui-theme-accent-blue mdui-theme-layout-auto">';
}

function mduiFooter() {
    echo <<<EOF
    <script src="https://cdn.jsdelivr.net/npm/mdui@1.0.0/dist/js/mdui.min.js" integrity="sha384-aB8rnkAu/GBsQ1q6dwTySnlrrbhqDwrDnpVHR2Wgm8pWLbwUnzDcIROX3VvCbaK+" crossorigin="anonymous"></script>
    <script src="ajax.js"></script>
    <script src="https://cdn.bootcss.com/jquery/3.4.1/jquery.min.js"></script>
EOF;
}