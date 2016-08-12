/**
 * Created by ellipsis on 15/12/1.
 */
function fm_encode(content) {
    //var content = obj.val();
    var res = content;

    var imgReg = /<img[^>]*>/gi;
    var expressReg = /expression\/(.+?)\./;
    var imgSrcReg = /src=["|'](.+?)["|']/;
    var heightReg = /height=["|'](\d+?)px["|']/;
    var widthReg = /width=["|'](\d+?)px["|']/;

    var imgs = content.match(imgReg);
    var tmp;
    if (imgs) {
        for (var i = 0; i < imgs.length; i++) {
            if (imgs[i].search(/expression/) > -1) {
                tmp = imgs[i].match(expressReg);
                res = res.replace(imgs[i], "[:" + tmp[1] + "]");
            } else {
                tmp = imgs[i].match(imgSrcReg);
                var heigth = imgs[i].match(heightReg);
                var width = imgs[i].match(widthReg);
                res = res.replace(imgs[i], "[img=" + width[1] + "," + heigth[1] + "]" + tmp[1] + "[/img]");
            }
        }
    }
    return res.replace(/<[^>]+>/g, "");
}

function fm_decode(val) {
    var res = val;
    var tmp;
    var imgReg = /\[img=\d+,\d+\]http:\/\/.+?\[\/img\]/gi;
    var imgResExt = /\[img=(\d+),(\d+)\](http:\/\/.+?)\[\/img\]/i;
    var expressReg = /\[:\w+\]/g;
    var expressRegExt = /\[:(\w+)\]/;
    var imgs = val.match(imgReg);
    if (imgs) {
        for (var i = 0; i < imgs.length; i++) {
            tmp = imgResExt.exec(imgs[i]);
            res = res.replace(imgs[i], '<img src="' + tmp[3] + '" width="' + tmp[1] + 'px" height="' + tmp[2] + 'px">');
        }
    }
    var expresses = val.match(expressReg);
    if (expresses) {
        for (var i = 0; i < expresses.length; i++) {
            tmp = expressRegExt.exec(expresses[i]);
            res = res.replace(expresses[i], '<img src="/upload/expression/' + tmp[1] + '.jpg">');
        }
    }
    return res;
}