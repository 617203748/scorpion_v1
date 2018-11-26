$(function () {
    var data = [//四行五列
        [, , , ,],
        ["2001", 10, 11, 12],
    ];
    var container = document.getElementById('example');
    var hot = new Handsontable(container,
        {
            data: data,
            minSpareRows: 10,	//空出多少行
            width: "100%",
            stretchH: "all",
            minCols: 26, // 最小列数
            minRows: 30,
            colHeaders: true,	//显示列表头 可取 true/fals/数组 ，当值为数组时，列头为数组的值
            rowHeaders: true,	//显示行表头 可取 true/fals/数组 ，当值为数组时，列头为数组的值
            columnSorting: false, // 点击列表头可进行当前列单元格排序
            manualColumnResize: true,//当值为true时，允许拖动，当为false时禁止拖动
            manualRowResize: true,//当值为true时，允许拖动，当为false时禁止拖动
            //  columnSorting manualColumnFreeze 不能同时设置为true
            mergeCells: true,
            wordWrap: true, //默认
            autoColumnSize: false,
            //readOnly:true,
            //显示表头下拉菜单 可取 true/false/自定义数组 右键任意单元格触发
            //汉化下拉菜单
            mergeCells: [       //是否允许单元格合并操作
                {row: 1, col: 1, rowspan: 1, colspan: 2}
            ],//设置单元格合并情况,
            contextMenu: {
                items: {
                    'mergeCells': {name: '合并单元格',},
                    'row_above': {name: '上方添加一行',},
                    'row_below': {name: '下方添加一行',},
                    'col_left': {name: '左侧添加一列',},
                    'col_right': {name: '右侧添加一列',},
                    'remove_row': {name: '移除此行',},
                    'remove_col': {name: '移除此列',},
                    'copy': {name: '复制',},
                    'cut': {name: '剪切',},
                    'make_read_only': {name: '禁止编辑选中项',},
                    'alignment': {},
                    'undo': {name: '还原上次操作',},
                    'redo': {name: '重复上次动作',},
                    'setAlias': {
                        name: '设置别名',
                        callback: function () {
                            if ($(Ccell) != undefined) {
                                addAliasDialog();
                            } else {
                                alert("请先选择单元格...");
                            }
                        }
                    }
                }
            }
        });

    function addAliasDialog() {
        var html = '<div class="alias" style="text-align:center;margin-top:20px;"><label>请输入别名：<input type="text" id="aliasVal" /></label></div>';
        layer.open({
            type: 1,
            btn: ['确认', '取消'],
            shadeClose: true,
            title: "设置别名",
            area: ['420px', '240px'], //宽高
            content: html,
            yes: function (index, layero) {
                var val = $("#aliasVal").val();
                var cellMeta = hot.getCellMeta(Crow, Ccol);
                if (val != "") {
                    hot.setCellMeta(Crow, Ccol, "alias", val);
                    layer.msg('设置成功', {
                        icon: 1,
                        time: 1000 //1秒关闭（如果不配置，默认是3秒）
                    }, function () {
                        console.log(cellMeta);
                        layer.close(index);
                    });
                } else {
                    alert("别名不能为空!");
                }
            },
            cancel: function (index, layero) {
                layer.close(index);
            }, btn2: function (index, layero) {
                layer.confirm('确认取消设置别名吗？', {icon: 3, title: '提示'}, function (index) {
                    layer.close(index);
                }, function (index) {
                    addAliasDialog();
                });
            }
        });
    }

    // 列出全局变量
    var Crow, Ccol, Ccell, valT, selectRange, selectRangeArr = [];
    // 获取所选区域单元格数组 当前高亮
    hot.addHook('afterOnCellMouseDown', function (event, cellCoords) {
        Crow = cellCoords.row,
            Ccol = cellCoords.col;
        selectRangeArr = []; // 所选区域所有单元格数组
        Ccell = hot.getCell(Crow, Ccol)
        selectRange = hot.getSelected(); // 获取所选区域范围
        console.log(selectRange);
        var txt = hot.getDataAtCell(selectRange[0], selectRange[1]); // 获取所选区域第一个单元格值
        // 单击任意单元格取消编辑状态
        $(".handsontableInputHolder").css({
            "display": "none"
        });
        $("#templateCellInput").val(txt);
        var rangeRowArr = []; // 所选区域行数组
        var rangeColArr = []; // 所选区域列数组
        for (var i = selectRange[0]; i < selectRange[2] + 1; i++) {
            rangeRowArr.push(i);
        }
        for (var i = selectRange[1]; i < selectRange[3] + 1; i++) {
            rangeColArr.push(i);
        }
        for (var i = 0; i < rangeRowArr.length; i++) {
            for (var n = 0; n < rangeColArr.length; n++) {
                var selectRangeCell = {row: rangeRowArr[i], col: rangeColArr[n]};
                selectRangeArr.push(selectRangeCell);
            }
        }
        // 添加表格失去焦点时的当前单元格类
        $("td").removeClass("currentTd");
        for (var i = 0; i < selectRangeArr.length; i++) {
            var rangeCell = hot.getCell(selectRangeArr[i].row, selectRangeArr[i].col);
            $(rangeCell).addClass("currentTd");
        }
    });

    // 所选单元格的值和input同步
    $("#templateCellInput").keyup(function () {
        var val = $(this).val();
        if (selectRangeArr.length > 0) {
            for (var i = 0; i < selectRangeArr.length; i++) {
                hot.setDataAtCell(selectRangeArr[i].row, selectRangeArr[i].col, val)
            }
        }
    });

    $("#example").on("blur", "textarea.handsontableInput", function (e) {
        valT = $(this).val();
        hot.setDataAtCell(Crow, Ccol, valT);
    })
    // 修改单元格样式
    $(".btn-group label.btn").click(function (e) {
        console.log(e.target);
        var _index = $(this).index();
        var styleType = $(this).parent();
        var StyleClassName = '';
        // 修改单元格文本样式
        var toogleSwitch = true;
        if (styleType.hasClass("fontStyle")) {
            var fontClass = "";
            switch (_index) {
                case 0 :
                    fontClass = "htBold"; // 加粗
                    break;
                case 1 :
                    fontClass = "htItalic"; // 斜体
                    break;
                case 2 :
                    fontClass = "htUnderline"; // 下划线
                    break;
            }
            StyleClassName = fontClass;
        }
        // 修改单元格对齐方式
        if (styleType.hasClass("alignStyle")) {
            var alignClass = "";
            switch (_index) {
                case 0 :
                    alignClass = "htLeft"; // 左对齐
                    break;
                case 1 :
                    alignClass = "htCenter"; // 居中对齐
                    break;
                case 2 :
                    alignClass = "htRight"; // 右对齐
                    break;
                case 3 :
                    alignClass = "htJustify"; // 两端对齐
                    break;
            }
            StyleClassName = alignClass;
        }
        // 修改所选区域所有单元格样式并赋予属性
        for (var i = 0; i < selectRangeArr.length; i++) {
            var rangeCell = hot.getCell(selectRangeArr[i].row, selectRangeArr[i].col);
            var checkMergeCell = $(rangeCell).attr("rowspan");
            $(rangeCell).removeClass("htLeft htCenter htRight htJustify");
            // 定义修改类名 创建对应属性方法
            var setRangeCellClass = function () {
                $(rangeCell).toggleClass(StyleClassName);
                var cellClass = $(rangeCell)[0].className;
                hot.setCellMeta(selectRangeArr[i].row, selectRangeArr[i].col, "cellClass", cellClass);
            }
            if (checkMergeCell != undefined) {
                if (toogleSwitch) {
                    setRangeCellClass();
                    toogleSwitch = false;
                } else {
                    continue;
                }
            } else {
                setRangeCellClass();
            }
        }
    });
    $(".ColorStyle input").each(function () {
        $(this).colorpicker();
    })
    $(".ColorStyle input").blur(function () {
        var val = $(this).val();
        var _index = $(this).parent().index();
        $(this).css("cssText", "background:" + val + "!important;color:" + val + "!important;");
        // 定义改变样式方法
        var changeCellStyle = function () {
            if (_index == 0) {
                $(rangeCell).css({"background": val});
                hot.setCellMeta(selectRangeArr[i].row, selectRangeArr[i].col, "bkColor", val);
            }
            if (_index == 1) {
                $(rangeCell).css({"color": val});
                hot.setCellMeta(selectRangeArr[i].row, selectRangeArr[i].col, "ftColor", val);
            }
            if (_index == 2) {
                $(rangeCell).css({"border": "solid 1px " + val});
                hot.setCellMeta(selectRangeArr[i].row, selectRangeArr[i].col, "bdColor", val);
            }
        };
        for (var i = 0; i < selectRangeArr.length; i++) {
            var rangeCell = hot.getCell(selectRangeArr[i].row, selectRangeArr[i].col);
            var checkMergeCell = $(rangeCell).attr("rowspan");
            if (checkMergeCell != undefined) {
                if (toogleSwitch) {
                    changeCellStyle();
                    toogleSwitch = false;
                } else {
                    continue;
                }
            } else {
                changeCellStyle();
            }
        }
    });

    //重新加载数据
    $("#loadData").click(function () {
        hot.loadData(data1);
        hot.updateSettings({
            mergeCells: [
                {row: 1, col: 1, rowspan: 3, colspan: 3},
                {row: 3, col: 4, rowspan: 2, colspan: 2},
                {row: 5, col: 6, rowspan: 3, colspan: 3},
                {row: 0, col: 0, rowspan: 1, colspan: 2}
            ]
        });
    })
})

$(function () {
    $("#example").on("focus", "textarea.handsontableInput", function (e) {
        var init = $(this).attr("init");
        if (init) return;
        $("textarea.handsontableInput").editTips({
            triggerCharacterArr: ['$', '@'],
            levelCharacter: '.',
            dropdownWidth: '150px',
            keyPressAction: function (selectVal, callbacktips) {
                var arr_json;
                if (selectVal == "$" || selectVal == "@") {
                    arr_json = ["a", "ab", "$b", "bb", "a", "ab", "$b", "bb", "a", "ab", "$b", "bb"]
                }
                if (selectVal && selectVal.indexOf("$a") == 0) {
                    arr_json = ["a", "a", "a", "a"]
                }
                if (selectVal && selectVal.indexOf("$a.") == 0) {
                    arr_json = ["b", "bb"];
                }
                if (selectVal && selectVal.indexOf("$a.a") == 0) {
                    arr_json = ["a.a", "a.b", "a.c"];
                }
                if (selectVal && selectVal.indexOf("$a.a.") == 0) {
                    arr_json = ["b.a", "b.b", "b.c"];
                }
                if (selectVal && selectVal.indexOf("ab.") == 0) {
                    arr_json = ["ab.a", "ab.b", "ab.c"];
                }
                if (selectVal && selectVal.indexOf("bb.") == 0) {
                    arr_json = ["bb.a", "bb.b", "bb.c"];
                }
                callbacktips(arr_json);
            }
        });

    })
    /*	function aa(){
            console.log($("textarea.handsontableInput").val());
        }
        setInterval(aa,500);
            */
});

