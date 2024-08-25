export default {
    cript(data) {
        data = this.b64(data);
        var mensx = "";
        var l;
        var i;
        var j = 0;
        var ch;
        ch = process.env.MIX_CRIPTO_KEY;

        for (i = 0; i < data.length; i++) {
            j++;
            l = (this.Asc(data.substr(i, 1)) + (this.Asc(ch.substr(j, 1))));
            if (j == 50) {
                j = 1;
            }
            if (l > 255) {
                l -= 256;
            }
            mensx += (this.Chr(l));
        }

        return mensx;
    },

    decript(data) {
        var mensx = "";
        var l;
        var i;
        var j = 0;
        var ch;
        ch = process.env.MIX_CRIPTO_KEY;
        for (i = 0; i < data.length; i++) {
            j++;
            l = (this.Asc(data.substr(i, 1)) - (this.Asc(ch.substr(j, 1))));
            if (j == 50) {
                j = 1;
            }
            if (l < 0) {
                l += 256;
            }
            mensx += (this.Chr(l));
        }

        mensx = this.b64D(mensx);
        return mensx;
    },

    Asc(String) {
        return String.charCodeAt(0);
    },

    Chr(AsciiNum) {
        return String.fromCharCode(AsciiNum);
    },

    b64: function (str) {
        return btoa(encodeURIComponent(str).replace(/%([0-9A-F]{2})/g,
            function toSolidBytes(match, p1) {
                return String.fromCharCode('0x' + p1);
            }));
    },
    b64D: function (str) {
        return decodeURIComponent(atob(str).split('').map(function (c) {
            return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
        }).join(''));
    },
}