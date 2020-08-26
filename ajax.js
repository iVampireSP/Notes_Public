var $$ = mdui.$;
document.getElementById("topload").style.display = "none";
// 夜间代码 来自https://www.mdui.org/questions/196
// 提前定义好事件
var changeStyle = new CustomEvent('change_style', {
    detail: {}
});
//注册监听器
window.addEventListener('change_style', function(event) {
    $$('body').toggleClass('mdui-theme-layout-dark');
});

// 公告
// mdui.alert('请注意：我们的许可条款已更新。<br />改动内容：分享记事本时，记事本将会出现在“分享广场”上。<br />请熟知！');



function change_style() {
    // 在对应的元素上触发该事件
    if (window.dispatchEvent) {
        window.dispatchEvent(changeStyle);
    } else {
        //ie8兼容
        window.fireEvent(changeStyle);
    }
}
// 夜间代码结束
function showloading() {
    var abutton = document.getElementById("a-but");
    // document.getElementById("topload").style.display = "block";
    if (abutton !== null) {
        document.getElementById("a-but").style.display = 'none';
    }
    // document.getElementById("mainContent").style.filter = "blur(1px)";
}

function disableload() {
    var abutton = document.getElementById("a-but");
    // document.getElementById("topload").style.display = "none";
    if (abutton !== null) {
        document.getElementById("a-but").style.display = 'block';
    }
    // document.getElementById("mainContent").style.filter = "unset";
}

function changeUrl(url, title) {
    var stateObject = {};
    var newUrl = url;
    document.title = title;
    history.pushState(stateObject, title, newUrl);
}

function mainAnime() {
    $("#mainContent").animate({ width: '0px' });
}

function mainAnime_end() {
    $("#mainContent").animate({ width: '100%' });
}

loadCategorymenu();

// 获取ID
function getID() {
    changeUrl(null, '正在获取ID...');
    showloading();
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            subTitle(xmlhttp.responseText);
            mdui.alert('您的用户ID：' + xmlhttp.responseText);
            changeUrl(null, xmlhttp.responseText);
            disableload();
        }
    }
    xmlhttp.open("GET", "/api/getID.php", true);
    xmlhttp.send();
}

function sharenote(noteid) {
    showloading();
    changeUrl(null, '正在切换分享状态...');
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            if (xmlhttp.responseText == null || xmlhttp.responseText == '') {
                changeUrl(null, '操作失败。');
                mdui.dialog({
                    title: '执行结果',
                    content: '操作失败。您没有足够的权限或者正在尝试越权操作。',
                    buttons: [{
                        text: '确认',
                        onClick: function(inst) {
                            changeUrl(null, '继续浏览记事本。');
                            disableload();
                        }
                    }]
                });
            } else {
                changeUrl(null, '操作已完成。');
                mdui.dialog({
                    title: '执行结果',
                    content: xmlhttp.responseText,
                    buttons: [{
                        text: '确认',
                        onClick: function(inst) {
                            changeUrl(null, '继续浏览记事本。');
                            disableload();
                        }
                    }]
                });
            }
        }
    }
    xmlhttp.open("POST", "note.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(`noteid=${noteid}&action=share`);
}

function delAcc() {
    changeUrl(null, '正在删除账号...');
    showloading();
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            subTitle('账号已删除。');
            mdui.alert('账号已删除。');
            changeUrl(null, '账号已删除。');
            disableload();
        }
    }
    xmlhttp.open("GET", "delAcc.php", true);
    xmlhttp.send();
}

function loadWelcome() {
    changeUrl(null, '正在加载欢迎界面...');
    showloading();
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            subTitle('欢迎新用户');
            document.getElementById("mainContent").innerHTML = xmlhttp.responseText;
            changeUrl(null, '欢迎新用户。');
            disableload();
        }
    }
    xmlhttp.open("GET", "welcome.html", true);
    xmlhttp.send();
}


function userReg() {
    changeUrl(null, '正在注册...');
    showloading();
    var password = $('#password').val();
    // 先判断是否为空，请：
    if (password == null || password == "") {
        mdui.snackbar({
            message: '能不能好好填啊Kora!',
            position: 'bottom'
        });
        disableload();
        return false;
    }
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            mdui.alert(xmlhttp.responseText);
            changeUrl('/', 'Sweet Home -> Note');
            loadWelcome();
            disableload();
        }
    }
    xmlhttp.open("POST", "register.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(`password=${password}`);
}

function userLogin() {
    changeUrl(null, '正在登录...');
    showloading();
    var userid = $('#userid').val();
    var password = $('#password').val();
    // 先判断是否为空，请：
    if (userid == null || userid == "" || password == null || password == "") {
        mdui.snackbar({
            message: '能不能好好填啊Kora!',
            position: 'bottom'
        });
        disableload();
        return false;
    }
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            changeUrl('/', 'Sweet Home -> Login');
            // window.location.replace("index.php");
            mdui.snackbar({
                message: xmlhttp.responseText,
                position: 'bottom'
            });
            loadIndex();
            loadBar();
            disableload();
        }
    }
    xmlhttp.open("POST", "login.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(`userid=${userid}&password=${password}`);
}

function subTitle(title) {
    document.getElementById("subTitle").innerHTML = title;
}

function loadNote(noteid, title) {
    changeUrl(null, `正在加载记事本：${title}...`);
    showloading();
    mainAnime();
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            changeUrl(null, title);
            subTitle(title);
            $(function() {
                var View = editormd.markdownToHTML("markdown-view", {
                    // markdown : "[TOC]\n### Hello world!\n## Heading 2", // Also, you can dynamic set Markdown text
                    htmlDecode: true, // Enable / disable HTML tag encode.
                    // htmlDecode : "style,script,iframe",  // Note: If enabled, you should filter some dangerous HTML tags for website security.
                });
            });
            document.getElementById("mainContent").innerHTML = xmlhttp.responseText;
            //<li class=\"mdui-list-item mdui-ripple\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">delete</i><div class=\"mdui-list-item-content\" onclick=\"loadDelnote(" + noteid + ")\">删除记事本</div></li>
            document.getElementById("menu").innerHTML = "<li class=\"mdui-list-item mdui-ripple mdui-list-item-active\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">event_note</i><div class=\"mdui-list-item-content\" onclick=\"loadIndex()\">记事本</div></li><li class=\"mdui-list-item mdui-ripple\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">add</i><div class=\"mdui-list-item-content\" onclick=\"loadAdd()\">新增记事本</div></li><li class=\"mdui-list-item mdui-ripple\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">edit</i><div class=\"mdui-list-item-content\" onclick=\"loadEdit(" + noteid + ")\">编辑记事本</div></li><li class=\"mdui-list-item mdui-ripple\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">delete</i><div class=\"mdui-list-item-content\" onclick=\"loadDelnote(" + noteid + ")\">删除记事本</div></li><li class=\"mdui-list-item mdui-ripple\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">share</i><div class=\"mdui-list-item-content\" onclick=\"loadShareground()\">分享广场</div></li>";
            disableload();
            mainAnime_end();
        }
    }
    xmlhttp.open("GET", "note.php?noteid=" + noteid, true);
    xmlhttp.send();
}

function loadShareNote(noteid, title) {
    changeUrl(null, `正在加载记事本：${title}...`);
    showloading();
    mainAnime();
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            changeUrl('/share.php?noteid=' + noteid, title);
            subTitle(title);
            $(function() {
                var View = editormd.markdownToHTML("markdown-view", {
                    // markdown : "[TOC]\n### Hello world!\n## Heading 2", // Also, you can dynamic set Markdown text
                    htmlDecode: true, // Enable / disable HTML tag encode.
                    // htmlDecode : "style,script,iframe",  // Note: If enabled, you should filter some dangerous HTML tags for website security.
                });
            });
            document.getElementById("mainContent").innerHTML = xmlhttp.responseText;
            //<li class=\"mdui-list-item mdui-ripple\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">delete</i><div class=\"mdui-list-item-content\" onclick=\"loadDelnote(" + noteid + ")\">删除记事本</div></li>
            document.getElementById("menu").innerHTML = "<li class=\"mdui-list-item mdui-ripple mdui-list-item-active\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">event_note</i><div class=\"mdui-list-item-content\" onclick=\"loadIndex()\">记事本</div></li><li class=\"mdui-list-item mdui-ripple\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">add</i><div class=\"mdui-list-item-content\" onclick=\"loadAdd()\">新增记事本</div></li><li class=\"mdui-list-item mdui-ripple\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">share</i><div class=\"mdui-list-item-content\" onclick=\"loadShareground()\">分享广场</div></li>";
            disableload();
            mainAnime_end();
        }
    }
    xmlhttp.open("GET", "note.php?noteid=" + noteid, true);
    xmlhttp.send();
}


function loadIndex() {
    changeUrl(null, '正在加载概览...');
    showloading();
    mainAnime();
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            changeUrl('/', 'Sweet Home -> Note');
            subTitle('记事本');
            document.getElementById("mainContent").innerHTML = xmlhttp.responseText;
            mainAnime_end();
            document.getElementById("menu").innerHTML = "<li class=\"mdui-list-item mdui-ripple mdui-list-item-active\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">event_note</i><div class=\"mdui-list-item-content\" onclick=\"loadIndex()\">记事本</div></li><li class=\"mdui-list-item mdui-ripple\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">add</i><div class=\"mdui-list-item-content\" onclick=\"loadAdd()\">新增记事本</div></li><li class=\"mdui-list-item mdui-ripple\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">share</i><div class=\"mdui-list-item-content\" onclick=\"loadShareground()\">分享广场</div></li>";
            disableload();
        }
    }
    xmlhttp.open("GET", "main.php", true);
    xmlhttp.send();
}

function loadAdd() {
    changeUrl(null, '正在加载编辑器...');
    showloading();
    mainAnime();
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            subTitle('新增记事本');
            changeUrl('/', 'Sweet Home -> New');
            document.getElementById("mainContent").innerHTML = xmlhttp.responseText;
            mainAnime_end();
            document.getElementById("menu").innerHTML = "<li class=\"mdui-list-item mdui-ripple\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">event_note</i><div class=\"mdui-list-item-content\" onclick=\"loadIndex()\">记事本</div></li><li class=\"mdui-list-item mdui-ripple mdui-list-item-active\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">add</i><div class=\"mdui-list-item-content\" onclick=\"loadAdd()\">新增记事本</div></li><li class=\"mdui-list-item mdui-ripple\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">share</i><div class=\"mdui-list-item-content\" onclick=\"loadShareground()\">分享广场</div></li>";
            mdui.mutation();
            disableload();
        }
    }
    xmlhttp.open("GET", "addnote.php", true);
    xmlhttp.send();
}

function newNote() {
    changeUrl(null, '正在添加记事本...');
    showloading();
    setTimeout(function() {
        changeUrl(null, '正在加载概览.');
        setTimeout(function() {
            changeUrl(null, '正在加载概览..');
            setTimeout(function() {
                changeUrl(null, '正在加载概览...');
                setTimeout(function() {
                    changeUrl(null, 'Sweet Home -> Note');
                }, 200);
            }, 200);
        }, 200);
    }, 200);
    var title = $('#title').val();
    var content = $('#content').val();
    var cgid = $('#cgid').val();

    // 先判断是否为空，请：
    if (title == null || content == "" || title == null || content == "" || cgid == null || cgid == "") {
        mdui.snackbar({
            message: '能不能好好填啊Kora!',
            position: 'bottom'
        });
        changeUrl(null, '能不能好好填啊Kora!');
        setTimeout(function() {
            changeUrl(null, 'Sweet Home -> New');
        }, 2000);
        disableload();
        return false;
    }
    $.ajax({
        url: '/api/addNote.php',
        type: 'post',
        contentType: "application/json;charset=utf-8",
        data: JSON.stringify({ "title": $('#title').val(), "content": $('#content').val(), "cgid": $('#cgid').val() }),
        dataType: 'json',
        success: function() {
            mdui.snackbar({
                message: '创建成功。',
                position: 'bottom'
            });
            loadIndex();
            disableload();
        },
        error: function(message) {
            mdui.snackbar({
                message: '无法创建记事本。',
                position: 'bottom'
            });
            loadIndex();
            disableload();
        }
    });
}

function loadEdit(noteid) {
    changeUrl(null, '正在加载编辑器...');
    showloading();
    mainAnime();
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            subTitle('编辑记事本');
            changeUrl('/', 'Sweet Home -> Edit');
            document.getElementById("mainContent").innerHTML = xmlhttp.responseText;
            mainAnime_end();
            document.getElementById("menu").innerHTML = "<li class=\"mdui-list-item mdui-ripple\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">event_note</i><div class=\"mdui-list-item-content\" onclick=\"loadIndex()\">记事本</div></li><li class=\"mdui-list-item mdui-ripple\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">add</i><div class=\"mdui-list-item-content\" onclick=\"loadAdd()\">新增记事本</div></li><li class=\"mdui-list-item mdui-ripple mdui-list-item-active\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">edit</i><div class=\"mdui-list-item-content\" onclick=\"loadEdit(" + noteid + ")\">编辑记事本</div></li><li class=\"mdui-list-item mdui-ripple\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">delete</i><div class=\"mdui-list-item-content\" onclick=\"loadDelnote(" + noteid + ")\">删除记事本</div></li><li class=\"mdui-list-item mdui-ripple\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">share</i><div class=\"mdui-list-item-content\" onclick=\"loadShareground()\">分享广场</div></li>";
            disableload();

        }
    }
    xmlhttp.open("GET", "editnote.php?noteid=" + noteid, true);
    xmlhttp.send();
}

function editNote(noteid) {
    changeUrl(null, '正在提交更改...');
    showloading();
    var title = $('#title').val();
    var content = $('#content').val();
    // 先判断是否为空，请：
    if (title == null || content == "" || title == null || content == "") {
        mdui.snackbar({
            message: '能不能好好填啊Kora!',
            position: 'bottom'
        });
        changeUrl(null, '能不能好好填啊Kora!');
        setTimeout(function() {
            changeUrl(null, 'Sweet Home -> Edit');
        }, 2000);
        return false;
    }
    $.ajax({
        url: '/api/editNote.php',
        type: 'post',
        contentType: "application/json;charset=utf-8",
        data: JSON.stringify({ "noteid": noteid, "title": $('#title').val(), "content": $('#content').val() }),
        dataType: 'json',
        success: function() {
            mdui.snackbar({
                message: '已修改记事本。',
                position: 'bottom'
            });
            loadIndex();
            disableload();
        },
        error: function(message) {
            mdui.snackbar({
                message: '无法修改记事本。',
                position: 'bottom'
            });
            loadIndex();
            disableload();
        }
    });
}

function delNote(noteid) {
    changeUrl(null, `正在删除: ${noteid}...`);
    showloading();
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            mdui.snackbar({
                message: '已删除。',
                position: 'bottom'
            });
            changeUrl('/', 'Sweet Home -> Note');
            loadIndex();
            disableload();
        }
    }
    xmlhttp.open("GET", "delnote.php?noteid=" + noteid, true);
    xmlhttp.send();
}

function loadDelnote(noteid) {
    mdui.dialog({
        title: '确认删除？',
        content: '您当前正在删除一个记事本，是否确认？',
        buttons: [{
                text: '取消'
            },
            {
                text: '确认',
                onClick: function(inst) {
                    showloading();
                    delNote(noteid);
                    disableload();
                }
            }
        ]
    });
}

function loadCategorymenu() {
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById("categorys").innerHTML = xmlhttp.responseText;
        }
    }
    xmlhttp.open("GET", "api/getCategory.php", true);
    xmlhttp.send();
}

function loadAddCategory() {
    changeUrl(null, '正在加载编辑器...');
    showloading();
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            subTitle('新增分类');
            changeUrl('/', 'Sweet Home -> New Category');
            document.getElementById("mainContent").innerHTML = xmlhttp.responseText;
            disableload();
        }
    }
    xmlhttp.open("GET", "addCategory.php", true);
    xmlhttp.send();
}

function newCategory() {
    changeUrl(null, 'Sweet Home');
    showloading();
    var name = $('#name').val();
    // 先判断是否为空，请：
    if (name == null || name == "") {
        mdui.snackbar({
            message: '能不能好好填啊Kora!',
            position: 'bottom'
        });
        changeUrl(null, '能不能好好填啊Kora!');
        setTimeout(function() {
            changeUrl(null, 'Sweet Home -> New Category');
        }, 2000);
        disableload();
        return false;
    }
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            mdui.snackbar({
                message: '分类添加成功。',
                position: 'bottom'
            });
            loadCategorymenu();
            disableload();
        }
    }
    xmlhttp.open("POST", "addCategory.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(`name=${name}`);
}


// 加载分类内容
function loadCategory(id, name) {
    changeUrl(null, name);
    showloading();
    mainAnime();
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            subTitle(name);
            document.getElementById("mainContent").innerHTML = xmlhttp.responseText;
            mainAnime_end();
            disableload();
        }
    }
    xmlhttp.open("GET", "category.php?id=" + id, true);
    xmlhttp.send();
}

function delCg(id) {
    changeUrl(null, '删除分类：' + id);
    showloading();
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            loadCategorymenu();
            disableload();
        }
    }
    xmlhttp.open("GET", "delCg.php?id=" + id, true);
    xmlhttp.send();
}

function loadMore() {
    showloading();
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById('loadMore-btn').parentNode.removeChild(document.getElementById('loadMore-btn'));
            document.getElementById('loadMore-btn').parentNode.removeChild(document.getElementById('loadMore-btn'));
            document.getElementById("mainContent").innerHTML = document.getElementById("mainContent").innerHTML + xmlhttp.responseText;
            disableload();
        }
    }
    xmlhttp.open("GET", "api/getSharepageplus.php", true);
    xmlhttp.send();
}


// Shareground.php

function loadShareground() {
    showloading();
    mainAnime();
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            subTitle('分享广场');
            document.getElementById("mainContent").innerHTML = xmlhttp.responseText;
            mainAnime_end();
            document.getElementById("menu").innerHTML = "<li class=\"mdui-list-item mdui-ripple\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">event_note</i><div class=\"mdui-list-item-content\" onclick=\"loadIndex()\">记事本</div></li><li class=\"mdui-list-item mdui-ripple\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">add</i><div class=\"mdui-list-item-content\" onclick=\"loadAdd()\">新增记事本</div></li><li class=\"mdui-list-item mdui-ripple mdui-list-item-active\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">share</i><div class=\"mdui-list-item-content\" onclick=\"loadShareground()\">分享广场</div></li>";
            loadMore();
            disableload();
        }
    }
    xmlhttp.open("GET", "shareground.php", true);
    xmlhttp.send();
}

function loadBar() {
    loadCategorymenu();
    loadGroupmenu();
}

// 组
loadGroupmenu();

function loadGroupmenu() {
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById("groups").innerHTML = xmlhttp.responseText;
        }
    }
    xmlhttp.open("GET", "api/getGroups.php", true);
    xmlhttp.send();
}

function loadAddGroup() {
    changeUrl(null, '正在加载编辑器...');
    showloading();
    mainAnime();
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            subTitle('新增组');
            changeUrl('/', 'Sweet Home -> New Group');
            document.getElementById("mainContent").innerHTML = xmlhttp.responseText;
            mainAnime_end();
            disableload();
        }
    }
    xmlhttp.open("GET", "addGroup.php", true);
    xmlhttp.send();
}

function newGroup() {
    changeUrl(null, 'Sweet Home');
    showloading();
    var nickname = $('#nickname').val();
    var name = $('#name').val();
    var ip_port = $('#group_ip_port').val();
    var pwd = $('#group_pwd').val();
    // 先判断是否为空，请：
    if (name == null || name == "" || nickname == null || nickname == "" || ip_port == null || ip_port == "" || pwd == null || pwd == "") {
        mdui.snackbar({
            message: '能不能好好填啊Kora!',
            position: 'bottom'
        });
        changeUrl(null, '能不能好好填啊Kora!');
        setTimeout(function() {
            changeUrl(null, 'Sweet Home -> New Group');
        }, 2000);
        disableload();
        return false;
    }
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            mdui.snackbar({
                message: '组添加成功。',
                position: 'bottom'
            });
            loadGroupmenu();
            disableload();
        }
    }
    xmlhttp.open("POST", "addGroup.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(`name=${name}&nickname=${nickname}&ip_port=${ip_port}&password=${pwd}`);
}


// 加载组内容
function loadGroup(id, name, ip_port) {
    changeUrl(null, name);
    showloading();
    mainAnime();
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            subTitle(name);
            document.getElementById("mainContent").innerHTML = xmlhttp.responseText;
            mainAnime_end();
            mdui.snackbar({
                message: '正在连接到组服务器...',
                position: 'bottom'
            });
            disableload();
            Connect_Group(ip_port);
        }
    }
    xmlhttp.open("GET", "group.php?id=" + id, true);
    xmlhttp.send();
}

function delGroup(id) {
    changeUrl(null, '删除组：' + id);
    showloading();
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            loadGroupmenu();
            loadIndex();
            disableload();
        }
    }
    xmlhttp.open("GET", "delGroup.php?id=" + id, true);
    xmlhttp.send();
}

// 加载组编辑器
function loadGroupEditor() {
    changeUrl(null, '正在加载组编辑器...');
    showloading();
    mainAnime();
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            subTitle('组记事本');
            changeUrl('/', 'Sweet Home -> New');
            document.getElementById("mainContent").innerHTML = xmlhttp.responseText;
            mainAnime_end();
            document.getElementById("menu").innerHTML = "<li class=\"mdui-list-item mdui-ripple\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">event_note</i><div class=\"mdui-list-item-content\" onclick=\"loadIndex()\">记事本</div></li><li class=\"mdui-list-item mdui-ripple mdui-list-item-active\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">add</i><div class=\"mdui-list-item-content\" onclick=\"loadAdd()\">新增记事本</div></li><li class=\"mdui-list-item mdui-ripple\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">share</i><div class=\"mdui-list-item-content\" onclick=\"loadShareground()\">分享广场</div></li>";
            disableload();
            // #content变化时提交信息
            $("#content").change(function() {
                ws.send($('#content').val());
            });
        }
    }
    xmlhttp.open("GET", "addnote.php", true);
    xmlhttp.send();
}

// 组实时编辑
function Connect_Group(ip_port) {
    ws = new WebSocket("wss://" + ip_port);
    ws.onopen = function() {
        mdui.snackbar({
            message: '已建立连接',
            position: 'bottom'
        });
        loadGroupEditor();
    };
    ws.onclose = function(event) {
        mdui.snackbar({
            message: '与组服务器断开，请及时保存。',
            position: 'bottom'
        });
    };
    ws.onmessage = function(e) {
        $('#content').val(e.data);
        mdui.snackbar({
            message: '内容有更新',
            position: 'bottom'
        });
    };
}