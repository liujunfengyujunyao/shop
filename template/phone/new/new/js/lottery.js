/* 
*    Lottery.js
*    version 1.0
*   @example Lottery(id, start, end, goods, callBack);
*/

var Lottery = (function() {
    var canvas, 
        render2D, 
        start,
        end,
        goods,
        callBack,
        canvasW,
        canvasH, 
        timer = null, 
        goodNum = [], 
        flag,
        curVal;

    function init(arg) {
        start = parseInt(arg[0]) || 1;
        end = parseInt(arg[1]) || 100;
        goods = parseInt(arg[2]) || 10;
        callBack = arg[3] || function(){};

        if (parseInt(start) >= parseInt(end)) {
            return;
        }

        curVal = fandomNum(start, end);
        // clearDraw
        render2D.clearRect(0, 0, canvasW, canvasH);
        drawNum();
        drawMsg();
        canvas.addEventListener('click', initBox, false);
    }

    function randomColor() {
        var d = "0123456789abcdef", c = '', i = 6;
        while(i--) c += d.charAt(Math.round(Math.random()*15));
        return "#"+c;
    }

    function drawNum() {
        var val = start,
            rows = (end - start) / 10;

        render2D.shadowColor = 'rgba(10,10,10,0.8)';
        render2D.shadowOffsetX = 3;
        render2D.shadowOffsetY = 3;
        render2D.shadowBlur = 30;

        for (var i = 0; i < rows; i++) {
            for (var j = 0; j < 10; j++) {
                render2D.fillStyle = randomColor();
                render2D.fillRect(j*(50+3), i*(50+3), 50, 50);
                render2D.fillStyle = "#ffffff";
                render2D.fillText(val++, j*(50+3)+18, i*(50+3)+27);
                if (end == i*10+j+start) return;
            }
        }
    }

    function drawMsg(gn) {
        if (gn) {
            // render2D.fillText(gn.join(' '), canvasW/2-90, canvasH/2-80);
            // render2D.fillText(unescape("%u91CD%u65B0%u5F00%u59CB"), canvasW/2-30, canvasH/2-20);
            render2D.shadowColor = 'rgba(10,10,10,0.8)';
            render2D.shadowOffsetX = 3;
            render2D.shadowOffsetY = 3;
            render2D.shadowBlur = 30;
            render2D.fillStyle = "rgba(0,0,0,.3)";
            render2D.fillRect(0, 0, canvasW, canvasH);
            gn.forEach(function(num, i) {
                var pos = getXYRC(num);
                render2D.fillStyle = randomColor();
                render2D.beginPath();
                render2D.arc(pos.row*(50+3)+24, pos.col*(50+3)+24, 30, 0 , Math.PI*2, true);
                render2D.fill();
            });

            render2D.fillStyle = "#ffffff";
            gn.forEach(function(num, i) {
                var pos = getXYRC(num);
                render2D.fillText(num, pos.row*(50+3)+18, pos.col*(50+3)+27);
            });
        } else {
            flag = render2D.getImageData(canvasW/2-100, canvasH/2-100, 200, 200);
            render2D.shadowColor = undefined;
            render2D.shadowOffsetX = 0;
            render2D.shadowOffsetY = 0;
            render2D.shadowBlur = 10;

            render2D.fillStyle = "rgba(0,0,0,.6)";
            render2D.beginPath();
            render2D.arc(canvasW/2, canvasH/2, 100, 0, Math.PI*2, true);
            render2D.fill();
            // render2D.stroke();

            render2D.shadowColor = 'rgba(255,255,255,0.8)';
            render2D.shadowOffsetX = 5;
            render2D.shadowOffsetY = 5;
            render2D.shadowBlur = 20;

            render2D.beginPath();
            render2D.strokeStyle = "#ffffff";
            render2D.moveTo(canvasW/2-100+60, canvasH/2-100+40);
            render2D.lineTo(canvasW/2-100+160, canvasH/2-100+100);
            render2D.lineTo(canvasW/2-100+60, canvasH/2-100+160);
            render2D.lineTo(canvasW/2-100+60, canvasH/2-100+40);
            render2D.stroke();
            render2D.closePath();
            render2D.fillStyle = "rgba(255,255,255,.8)";
            render2D.fill();

            render2D.shadowColor = undefined;
            render2D.shadowOffsetX = 0;
            render2D.shadowOffsetY = 0;
            render2D.shadowBlur = 0;
        }
    }

    function initBox(e) {
        if (canvasW/2-100 < e.clientX - canvas.offsetLeft 
            && e.clientX - canvas.offsetLeft < canvasW/2+100 
            && canvasH/2-100 < e.clientY - canvas.offsetTop 
            && e.clientY - canvas.offsetTop < canvasH/2+100) {

            render2D.putImageData(flag, canvasW/2-100, canvasH/2-100);
            flag = null;

            play(function() {
                drawMsg(goodNum);
                callBack(goodNum);
                // canvas.addEventListener('click', initBox, false);
            });
            canvas.removeEventListener('click', initBox, false);
        }
    }

    // 返回一个范围内的随机值
    // 只传under表示取[1，under]
    // 传under, over表示取[under，over]
    function fandomNum(under, over) {
        switch(arguments.length) {
            case 1: return parseInt(Math.random() * under + 1);
            case 2: return parseInt(Math.random() * (over - under + 1) + under);
            default: return 0;
        }
    }

    // 返回一个随机的相邻值
    // 传under, over表示取[under，over, curVal]
    function fandomNums(under, over) {
        
        var newVal;
        switch("0123".charAt(Math.round(Math.random()*3))) {
            case '0': 
                newVal = curVal - 10;
                break;
            case '1': 
                newVal = curVal + 1;
                break;
            case '2':
                newVal = curVal + 10;
                break;
            case '3': 
                newVal = curVal - 1;
                break;
        }

        console.log(newVal);
        if(newVal < under || newVal > curVal) {
            return fandomNums(under, over)
        } else {
            return newVal;
        }

    }

    function changeColor() {
        var i = goods, g = [];
        while(i--) {
            var r = fandomNum(start, end), pos = getXYRC(r);
            render2D.fillStyle = randomColor();
            render2D.fillRect(pos.x, pos.y, 50, 50);
            render2D.fillStyle = "#ffffff";
            render2D.fillText(r, pos.row*(50+3)+18, pos.col*(50+3)+27);
            g.push(r);
        }
        goodNum = g;
    }

    function getXYRC(num) {
        var row = (num - start) % 10;
        var col = Math.floor((num - start) / 10); 

        return {x: row*(50+3), y: col*(50+3), row: row, col: col}
    }

    function play(callback) {
        timer = setInterval(changeColor, 50);
        setTimeout(function() {
            clearInterval(timer);
            callback(goodNum.join(' '));
        }, 3000)
    }

    return function() {
        var arg = Array.prototype.slice.call(arguments);
        canvas = typeof arg[0] === 'string' ? document.getElementById(arg[0]) : arg[0];
        if (typeof canvas == 'object') {
            render2D = canvas.getContext('2d');
            canvasW = canvas.width;
            canvasH = canvas.height;
            arg.shift();
            init(arg);
        }
    }
})()