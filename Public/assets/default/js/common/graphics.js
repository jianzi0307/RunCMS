var Graphics = function () {
    /**
     * 画连线
     * @param renderer
     * @param x 中心点坐标x
     * @param y 中心点坐标y
     * @param d 方向　H 横向 V纵向
     * @param len 长度
     * @param style 线条样式
     */
    var drawLine = function(renderer, x, y ,d,len,style){
        style = (style == undefined) ? 'solid' : style;
        var line = null;
        if(d == 'H') {
            line = ['M',0,0,'L',len,0];
        } else if(d == 'V') {
            line = ['M',0,0,'L',0,len];
        }
        renderer.path(line)
            .attr({
                'stroke-width': 1,
                stroke: 'black',
                dashstyle: style
            })
            .translate(x, y)
            .add();
    };

    /**
     * 画箭头
     * @param renderer
     * @param x 中心点坐标x
     * @param y 中心点坐标y
     * @param d 方向　U D L R 上下左右
     * @param len 长度
     * @param style 线条样式
     */
    var drawArrow = function(renderer, x, y, d, len, style){
        style = (style == undefined) ? 'solid' : style;
        var arrow = null;
        //调整箭头形状
        var arrlen = 14;
        var arrh = 3;
        var arrhelf = 10;

        if(d == 'L') {
            arrow = ['M', len, 0, 'L', 0, 0, 'L', arrlen, arrh, 'L',arrhelf,0, 'L',0,0,'M', 0, 0, 'L', arrlen, -arrh,'L',arrhelf,0, 'L',0,0];
        } else if(d == 'R') {
            arrow = ['M', 0, 0, 'L', len, 0, 'L', len-arrlen, arrh, 'L',len-arrhelf,0, 'L',len,0, 'M', len, 0, 'L', len-arrlen, -arrh,'L',len-arrhelf,0, 'L',len,0];
        } else if(d == 'D') {
            arrow = ['M', 0, 0, 'L', 0, len, 'L', -arrh, len-arrlen, 'L',0,len-arrhelf, 'L',0,len-arrlen, 'M', 0, len, 'L', arrh, len-arrlen,'L',0,len-arrhelf, 'L',0,len-arrlen];
        } else if(d == 'U') {
            arrow = ['M', 0, 0, 'L', 0, -len, 'L', -arrh, -len+arrlen, 'L',0,-len+arrhelf, 'L',0,-len,'M', 0, -len, 'L', arrh, -len + arrlen,'L',0,-len+arrhelf, 'L',0,-len];
        }
        renderer.path(arrow)
            .attr({
                'stroke-width': 1,
                stroke: 'black',
                dashstyle: style,
                fill:'black'
            })
            .translate(x, y)
            .add();
    };

    /**
     * 画粗箭头
     * @param renderer
     * @param x 中心点坐标x
     * @param y 中心点坐标y
     * @param angle 旋转弧度
     * @param scaleX 缩放X
     * @param scaleY 缩放Y
     * @param style 线条样式
     */
    var drawThArrow = function(renderer,x,y,angle,scaleX,scaleY,style) {
        scaleX = scaleX == undefined ? 1 : scaleX;
        scaleY = scaleY == undefined ? 1 : scaleY;
        var arrow = ['M', -5, 0, 'L', 5, 0, 5, 25, 10, 25, 0, 40, -10, 25,-5,25,-5,0];
        renderer.path(arrow)
            .attr({
                'stroke-width': 1,
                stroke: 'black',
                dashstyle: style,
                rotation: angle,
                scaleX:scaleX,
                scaleY:scaleY
            })
            .translate(x, y)
            .add();
    };

    /**
     * 画圆形节点
     * @param renderer
     * @param x 中心点坐标x
     * @param y 中心点坐标y
     * @param r 半径
     * @param label 文本
     */
    var drawCircle = function(renderer, x, y, r, label) {
        renderer.circle(x, y, r)
            .attr({
                fill: 'white',
                stroke: 'black',
                'stroke-width': 1
            })
            .add();
        renderer.label(label,x-25,y-13)
            .add();
    };

    /**
     * 画矩形节点
     * @param renderer
     * @param x 中心点坐标x
     * @param y 中心点坐标y
     * @param label 文本
     */
    var drawRect = function(renderer,x,y,label) {
        renderer.label(label, x, y)
            .attr({
                r: 0,
                'stroke-width': 1,
                stroke: 'black',
                fill: 'white',
                padding:10
            })
            .css({
                color: 'black'
            })
            .add();
    };

    /**
     * 标签
     * @param renderer
     * @param x 中心点坐标x
     * @param y 中心点坐标y
     * @param label 文本
     * @param color 文本颜色
     * @param weight 粗体
     */
    var drawLabel = function(renderer,x,y,label,color,weight) {
        color = color == undefined ? '#000000' : color;
        weight = weight == undefined ? 300 : weight;
        renderer.label(label, x, y)
            .css({
                color:color,
                fontWeight: weight
            })
            .add();
    };

    /**
     * 画扇形、环形
     * @param renderer
     * @param x 中心点坐标x
     * @param y 中心点坐标y
     * @param or 外圆半径
     * @param ir 内圆半径
     * @param start
     * @param end
     * @param style 线条样式
     */
    var drawArc = function(renderer,x,y,or,ir,start,end,style) {
        start = start == undefined ? 0 : start;
        end = end == undefined ? 0 : end;
        style = style == undefined ? 'solid' : style;
        renderer.arc(x, y, or, ir, Math.PI, 0,0).attr({
            fill: 'white',
            stroke: 'black',
            'stroke-width': 1,
            dashstyle: style
        }).add();
    };

    /**
     * 画弧线
     * @param renderer
     * @param x 原点位置x （全局坐标）
     * @param y 原点位置y
     * @param sx 起始点x（本地坐标）
     * @param sy 起始点y
     * @param mx 调节点x（本地坐标）
     * @param my 调节点y
     * @param ex 结束点x（本地坐标）
     * @param ey 结束点y
     * @param style 线条样式
     */
    var drawArcLine = function(renderer,x,y,sx,sy,mx,my,ex,ey,style) {
        style = style == undefined ? 'solid' : style;
        renderer.path(['M', 0, 0, 'C', sx, sy, mx, my, ex, ey])
            .attr({
                stroke: 'black',
                'stroke-width': 1,
                dashstyle: style
            })
            .translate(x, y)
            .add();
    };
    return {
        draw:function(id,title,conf,data){
            var PST = conf.PST;
            var ExC = conf.ExC;
            var Digester = conf.Digester;
            var Dewatering = conf.Dewatering;

            //用户输入的x，y
            var x = data.x;
            var y = data.y;
            var exc = data.ExC;
            var Raw = data.Raw;
            var CONVT = data.CONVT;
            var Eff = data.Eff;
            var WAS = data.WAS;
            var PS = data.PS;
            var TWAS = data.TWAS;
            var TWASRecy = data.TWASRecy;
            var AnDS = data.AnDS;
            var Biogas = data.Biogas;
            var DWRecy = data.DWRecy;
            var DWRecyA = data.DWRecyA;
            var Biosolids = data.Biosolids;

            $('#' + id).highcharts({
                chart: {
                    backgroundColor: 'white',
                    events: {
                        load: function () {
                            var ren = this.renderer;
                            drawLabel(ren,10,105,'进水 <br/>'+ Raw +'%');
                            //初沉池
                            if (PST) {
                                drawArrow(ren, 20, 145, 'R', 75);
                                drawCircle(ren, 130, 145, 35, '初沉池');
                                drawArrow(ren, 165, 145, 'R', 40);
                                //初沉污泥
                                if (Digester && x == 100) {
                                    drawLine(ren, 130, 180, 'V', 136);
                                    drawArrow(ren, 240,385,'R',155);
                                } else {
                                    drawLine(ren, 130, 180, 'V', 206);
                                    drawArrow(ren, 130,385,'R',265);
                                }
                                drawLabel(ren, 135, 195, '初沉污泥<br/>'+ PS +'%');
                            } else {
                                drawArrow(ren, 20, 145, 'R', 185);
                            }
                            if (Digester && PST && x > 0) {
                                drawLabel(ren, 150, 295, (PS * x/100) + '%');
                                drawArrow(ren, 130, 315, 'R', 75);
                            }
                            drawLine(ren, 175,145,'V',45);
                            drawLine(ren, 175,190,'H',185);
                            //外加碳源
                            if (ExC) {
                                var excLabel = (exc == 0) ? '外加碳源' : '外加碳源<br/>' + exc + '%';
                                drawLabel(ren, 165, 65, excLabel);
                                drawArrow(ren, 220, 85, 'D', 40);
                            }
                            //生物反应去除
                            drawThArrow(ren,260,125,180);
                            drawLabel(ren, 270, 65, '生物反应去除<br/>' + CONVT + '%');
                            drawRect(ren, 205,128,'生物反应池');

                            drawArrow(ren, 285, 145, 'R', 40);
                            drawCircle(ren, 360, 145, 35, '二沉池');

                            //出水
                            drawLabel(ren, 415, 105, '出水<br/>'+ Eff +'%');
                            drawArrow(ren, 395, 145, 'R', 40);

                            drawLabel(ren, 375, 175, '剩余污泥<br/>'+ WAS +'%');
                            drawArrow(ren, 360, 180, 'D', 30);
                            drawCircle(ren, 360, 245, 35, '浓缩池');

                            drawLabel(ren, 225, 210, '浓缩滤液<br/>'+ TWASRecy +'%');
                            if (PST) {
                                drawArrow(ren, 55, 245, 'L', 65, 'dash');
                                drawArcLine(ren, 120, 245, 0, 0, 10, -25, 20, 0, 'dash');
                                drawLine(ren, 140, 245, 'H', 185, 'dash');
                            } else {
                                drawArrow(ren, 55, 245, 'L', 270, 'dash');
                            }

                            if (!PST && !Digester) {
                                drawLine(ren, 360, 280, 'V', 140);
                                //drawArrow(ren, 360, 384, 'R', 35);
                            } else {
                                if (Digester && y == 100) {
                                    drawLine(ren, 360, 280, 'V', 36);
                                } else {
                                    drawArrow(ren, 360, 280, 'D', 105);
                                }
                            }

                            //厌氧消化
                            if (Digester) {
                                drawCircle(ren, 240, 315, 35, '厌氧消化');
                                drawThArrow(ren,275,290,230,1,0.5);
                                drawLabel(ren, 290, 250, '沼气<br/>'+ Biogas +'%');
                                if (y > 0) {
                                    drawLabel(ren, 310, 295, (TWAS * y/100) + '%');
                                    drawArrow(ren, 275, 315, 'L', 85);
                                }
                            }
                            if (Digester && PST ) {
                                drawLabel(ren, 240, 352, AnDS + '%');
                                if (x==100) {
                                    drawLine(ren, 240, 350, 'V', 35);
                                } else {
                                    drawArrow(ren, 240, 350, 'D', 35);
                                }
                            }
                            if (!PST && Digester) {
                                drawLine(ren, 240, 350, 'V', 35);
                                drawLabel(ren, 240, 352, AnDS + '%');
                                drawArrow(ren, 239,385,'R',156);
                            }

                            //调整脱水污泥位置，显示效果更好些
                            if (!Digester && !PST) {
                                drawRect(ren, 330,420,'脱水污泥');
                                drawArrow(ren, 398, 440, 'R', 50);
                                drawLabel(ren,450,420,'泥饼<br/>'+ Biosolids +'%');
                            } else {
                                drawRect(ren, 395,370,'脱水污泥');
                                drawArrow(ren, 463, 390, 'R', 50);
                                drawLine(ren, 425,410,'V',30,'dash');
                                drawLabel(ren,515,370,'泥饼<br/>'+ Biosolids +'%');
                            }

                            //侧流处理
                            if (Dewatering) {
                                drawRect(ren, 205, 420, '侧流处理');
                                if (!Digester && !PST) {
                                    drawLine(ren, 328,440,'H',-55,'dash');
                                } else {
                                    drawLine(ren, 425,440,'H',-150,'dash');
                                }
                                drawLabel(ren,100,405,'脱水滤液<br/>'+ DWRecyA +'%');
                                drawLabel(ren,278,405,'脱水滤液<br/>'+ DWRecy +'%');
                            } else {
                                if (!Digester && !PST) {
                                    drawLine(ren, 328, 440, 'H', -120, 'dash');
                                } else {
                                    drawLine(ren, 425, 440, 'H', -220, 'dash');
                                }
                                drawLabel(ren,240,405,'脱水滤液<br/>'+ DWRecy +'%');
                            }
                            drawLine(ren, 205,440,'H',-150,'dash');
                            drawArrow(ren, 55, 440, 'U', 293,'dash');
                        }
                    }
                },
                title: {
                    text: title,
                    style: {
                        color: 'black'
                    }
                },
                credits: { //去水印
                    enabled: false
                },
                exporting: { //去导出
                    enabled: false
                }
            });
        }

    }
}();
