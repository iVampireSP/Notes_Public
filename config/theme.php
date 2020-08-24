<?php
function mduiHeader($subtitle)
{
    $sitename = SITENAME;
    echo <<<EOF
    <header class="mdui-appbar-fixed mdui-appbar">
    <div class="mdui-color-theme mdui-toolbar" style="background-color:white">
        <span onclick="loadBar()" style="border-radius: 100%" class="mdui-btn mdui-btn-icon mdui-ripple mdui-ripple-white"
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
        <ul class="mdui-list">
            <div id="menu">
                <li class="mdui-list-item mdui-ripple mdui-list-item-active"><i class="mdui-list-item-icon mdui-icon material-icons">event_note</i><div class="mdui-list-item-content" onclick="loadIndex()">记事本</div></li>
                <li class="mdui-list-item mdui-ripple">
                    <i class="mdui-list-item-icon mdui-icon material-icons">add</i>
                    <div class="mdui-list-item-content" onclick="loadAdd()">新增记事本</div>
                </li>
                <li class="mdui-list-item mdui-ripple">
                    <i class="mdui-list-item-icon mdui-icon material-icons">share</i>
                    <div class="mdui-list-item-content" onclick="loadShareground()">分享广场</div>
                </li>
            </div>
            <li class="mdui-subheader">分类</li>
            <div id="categorys">
                
            </div>
            <li class="mdui-subheader">组列表</li>
            <div id="groups">
            
            </div>
        </ul>
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
    <link rel="stylesheet" href="/editor.md/css/editormd.preview.min.css" />
    <link rel="stylesheet" href="/editor.md/css/editormd.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/mdui@1.0.0/dist/css/mdui.min.css" integrity="sha384-2PJ2u4NYg6jCNNpv3i1hK9AoAqODy6CdiC+gYiL2DVx+ku5wzJMFNdE3RoWfBIRP" crossorigin="anonymous" />
    <link rel="icon" href="https://nwl.im/avatar" />
    <link rel="apple-touch-icon" href="https://nwl.im/avatar" />
    
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
    echo '<body class="mdui-container mdui-drawer-body-left mdui-appbar-with-toolbar mdui-theme-primary-white mdui-theme-accent-blue">';
}

function mduiFooter()
{
    echo <<<EOF
    <!-- <script src='//cdn.bootcss.com/socket.io/1.3.7/socket.io.js'></script> -->
    <script src="https://cdn.jsdelivr.net/npm/mdui@1.0.0/dist/js/mdui.min.js" integrity="sha384-aB8rnkAu/GBsQ1q6dwTySnlrrbhqDwrDnpVHR2Wgm8pWLbwUnzDcIROX3VvCbaK+" crossorigin="anonymous"></script>
    <script src="https://cdn.bootcss.com/jquery/3.4.1/jquery.min.js"></script>
    <!-- <script src="https://cdn.bootcdn.net/ajax/libs/jquery.pjax/2.0.1/jquery.pjax.min.js"></script> -->
    <script src="/editor.md/editormd.js"></script>
    <script src="/editor.md/lib/marked.min.js"></script>
    <script src="/editor.md/lib/prettify.min.js"></script>
    <script src="ajax.js"></script>
EOF;
}
