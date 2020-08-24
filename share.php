<?php
session_start();
require_once('config/config.php');
$user->noteid = $_REQUEST['noteid'];
if (!isset($_SESSION['user'])) {
    // 判断是否被共享
    if ($user->getShare() == NULL) {
        header("Location: login.php");
        return '*';
    }
}
if (!isset($_REQUEST['noteid'])) {
    header("Location: index.php");
    return '*';
}

require_once('config/theme.php');

if (!empty($_REQUEST['action'])) {
    // 判断是否登录
    if (!isset($_SESSION['user'])) {
        echo '您没有权限。';
        return '*';
    } else {
        echo $user->shareNote();
        return '*';
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <?php mduiHead('浏览：' . $user->getTitle()); ?>
    <style type="text/css">
        img {
            width: 99.5%;
        }

        .texto {
            width: 50%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        #menu .mdui-list-item,
        .mdui-collapse-item-header {
            border-radius: 0px 50px 50px 0px;
        }

        #categorys .mdui-list-item,
        .mdui-collapse-item-header {
            border-radius: 0px 50px 50px 0px;
        }

        ul .mdui-list-item {
            border-radius: 10px;
        }

        .mdui-dialog {
            border-radius: 7px;
        }

        .mdui-btn {
            border-radius: 5px;
        }

        .mdui-appbar {
            box-shadow: 0 1px 6px 0 rgba(32, 33, 36, .28);
        }

        #menu .mdui-list-item-content {
            font-size: unset
        }

        #categorys .mdui-list-item-content {
            font-size: unset
        }

        .mdui-list-item-active {
            color: rgb(27, 116, 232);
        }

        .mdui-list-item-active * {
            color: rgb(27, 116, 232);
        }

        img {
            width: 99.5%;
        }

        h1 {
            font-weight: 300;
        }

        .mdui-card-primary,
        .mdui-card-primary-title,
        .mdui-card-primary-subtitle,
        .mdui-card-content {
            margin: 0 10px 0 5px;
        }

        .editormd-html-preview,
        .editormd-preview-container {
            padding: 0;
        }

        #markdown-view {
            margin: 10px 5px;
        }
    </style>
</head>
<?php mduiBody();
mduiHeader('浏览：' . $user->getTitle());
mduiMenu(); ?>
<div id="mainContent">
    <div id="noteContent">
        <button class="mdui-btn" style="position: relative;top:10px" onclick="loadIndex();"><i class="mdui-icon material-icons mdui-text-color-theme-icon">arrow_back</i>返回首页</button>
        <div class="mdui-card" style="margin-top: 15px;border-radius:10px">
            <div class="mdui-card-primary">
                <div class="mdui-card-primary-title"><?php echo $user->getTitle(); ?></div>
                <div class="mdui-card-primary-subtitle" style="margin-top: 5px">创建时间：<?php echo $user->getTimedate(); ?></div>
            </div>
            <div class="mdui-card-content" style="margin-top: -35px">
                <div id="markdown-view">
                    <!-- Server-side output Markdown text -->
                    <textarea style="display:none;"><?php echo $user->viewNote(); ?></textarea>
                </div>
            </div>
        </div>
        <button class="mdui-fab mdui-fab-fixed mdui-color-theme-accent mdui-ripple" onclick="sharenote(<?php echo $_REQUEST['noteid']; ?>)"><i class="mdui-icon material-icons">share</i></button>
    </div>
    <?php
    mduiFooter();
    ?>
    <script type="text/javascript">
        $(function() {
            var View = editormd.markdownToHTML("markdown-view", {
                // markdown : "[TOC]\n### Hello world!\n## Heading 2", // Also, you can dynamic set Markdown text
                htmlDecode: true, // Enable / disable HTML tag encode.
                // htmlDecode : "style,script,iframe",  // Note: If enabled, you should filter some dangerous HTML tags for website security.
            });
        });
    </script>
    </body>

</html>