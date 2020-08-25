<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    return '*';
}
require_once('config/theme.php');
require_once('config/config.php');
if(!empty($_POST['title']) || !empty($_POST['content']) || !empty($_POST['cgid'])) {
    require_once('config/database.php');
    require_once('class/User.class.php');
    $user = new User();
    $user->db_con = $db_con;
    $user->addNote($_POST['title'], $_POST['content'], $_POST['cgid']);
    header('Location: /');
}
?>
<!DOCTYPE html>
<html>

<head>
    <?php mduiHead('记事本'); ?>
    <style type="text/css">
        @import 'https://cdn.jsdelivr.net/gh/pandao/editor.md@1.5.0/css/editormd.min.css';

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

        #categorys .mdui-list-item-content {
            font-size: unset
        }

        #groups .mdui-list-item,
        .mdui-collapse-item-header {
            border-radius: 0px 50px 50px 0px;
        }

        #groups .mdui-list-item-content {
            font-size: unset
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


        .mdui-list-item-active {
            color: rgb(27, 116, 232);
        }

        .mdui-list-item-active * {
            color: rgb(27, 116, 232);
        }
    </style>
</head>
<?php mduiBody();
mduiHeader('Markdown');
mduiMenu(); ?>

<div id="mainContent">
    <button class="mdui-btn" style="position: relative;top:10px" onclick="loadIndex();"><i class="mdui-icon material-icons">arrow_back</i>返回首页</button><button class="mdui-btn" style="position: relative;top:10px;left:1rem" onclick="loadAdd();">或者使用原始编辑器</button>
    <h1 style="font-weight: 400;"><span style="font-size: 50px;">M</span><span style="font-size: 45px;">a</span><span style="font-size: 40px;">r</span><span style="font-size: 35px;">k</span>down<i class="mdui-icon material-icons">arrow_downward</i></h1>
    <form name="addnote" method="post" action="add_note_markdown.php">
        <div class="mdui-textfield mdui-textfield-floating-label">
            <label class="mdui-textfield-label">标题</label>
            <input name="title" class="mdui-textfield-input" type="text" autocomplete="off" autofocus required />
        </div>
        <div class="mdui-textfield">
            <label class="mdui-textfield-label">内容</label>
            <div id="editor">
                <!-- Tips: Editor.md can auto append a `<textarea>` tag -->
                <textarea name="content" id="content" style="display:none;">
## I'm Loli
### Sweet Home Note
#### 支持Markdown啦！
##### 今天也要元气满满！</textarea>
            </div>
        </div>
        <script src="https://cdn.bootcss.com/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://cdn.bootcdn.net/ajax/libs/editor-md/1.5.0/editormd.js"></script>
        <script type="text/javascript">
            $(function() {
                var editor = editormd("editor", {
                    width: "100%",
                    height: 750,
                    markdown: "",
                    emoji: true,
                    path: '/editor.md/lib/',
                    //dialogLockScreen : false,   // 设置弹出层对话框不锁屏，全局通用，默认为 true
                    //dialogShowMask : false,     // 设置弹出层对话框显示透明遮罩层，全局通用，默认为 true
                    //dialogDraggable : false,    // 设置弹出层对话框不可拖动，全局通用，默认为 true
                    //dialogMaskOpacity : 0.4,    // 设置透明遮罩层的透明度，全局通用，默认值为 0.1
                    //dialogMaskBgColor : "#000", // 设置透明遮罩层的背景颜色，全局通用，默认为 #fff
                    imageUpload: false,
                });
            });
        </script>
        选择分类：
        <select class="mdui-select" name="cgid" id="cgid" mdui-select="{position: 'top'}" required>
            <?php $user->getCategorylistselect(); ?>
        </select>
        <br /><br /><br />
        <div class="mdui-col">
            <button class="mdui-fab mdui-fab-fixed mdui-color-theme-accent" type="submit"><i class="mdui-icon material-icons">add</i></button>
        </div>
    </form>
</div>
<?php
mduiFooter();
?>
</body>

</html>