var bConnect=0;
onLoad();
function onLoad() {
//如果是IE10及以下浏览器，则跳过不处理
    if (navigator.userAgent.indexOf("MSIE") > 0 && !navigator.userAgent.indexOf("opera") > -1) return;
    try {
        var s_pnp = new SoftKey3W();//创建UK类
        s_pnp.Socket_UK.onopen = function () {
            bConnect = 1;//代表已经连接，用于判断是否安装了客户端服务
        }

        //在使用事件插拨时，注意，一定不要关掉Sockey，否则无法监测事件插拨
        s_pnp.Socket_UK.onmessage = function got_packet(Msg) {
            var PnpData = JSON.parse(Msg.data);
            if (PnpData.type == "PnpEvent")//如果是插拨事件处理消息
            {
                if (!PnpData.IsIn){
                    var uk_sn = getCookie('uk_sn');
                    var sn = hex_md5(s_pnp.GetProduceDate(PnpData.DevicePath));
                    if(uk_sn == sn){
                        $.post('/admin/Login/logout',function(res){
                            if(res.status == 1) {
                                layer.msg('退出成功', {
                                    icon: 1
                                },function(){
                                    location.href = 'admin/login'
                                });
                            } else {
                                layer.msg('退出失败', {
                                    icon: 2
                                });
                            }
                        });
                    }
                }
            }
        }

        s_pnp.Socket_UK.onclose = function () {

        }
    } catch (e) {
        alert(e.name + ": " + e.message);
        return false;
    }
}

function getCookie(cookie_name) {
    var allcookies = document.cookie;
    //索引长度，开始索引的位置
    var cookie_pos = allcookies.indexOf(cookie_name);

    // 如果找到了索引，就代表cookie存在,否则不存在
    if (cookie_pos != -1) {
        // 把cookie_pos放在值的开始，只要给值加1即可
        //计算取cookie值得开始索引，加的1为“=”
        cookie_pos = cookie_pos + cookie_name.length + 1;
        //计算取cookie值得结束索引
        var cookie_end = allcookies.indexOf(";", cookie_pos);

        if (cookie_end == -1) {
            cookie_end = allcookies.length;

        }
        //得到想要的cookie的值
        var value = unescape(allcookies.substring(cookie_pos, cookie_end));
    }
    return value;
}

function SoftKey3W()
{
    var isIE11 = navigator.userAgent.indexOf('Trident') > -1 && navigator.userAgent.indexOf("rv:11.0") > -1;
    var isEDGE= navigator.userAgent.indexOf("Edge") > -1;
    var u = document.URL;
    var url;
    if (u.substring(0, 5) == "https") {
        if(isIE11 || isEDGE)
        {
            url = "wss://127.0.0.1:4006/xxx";
        }
        else
        {
            url = "wss://localhost:4006/xxx";
        }
    } else {
        url = "ws://127.0.0.1:4006/xxx";
    }

    var Socket_UK;

    if (typeof MozWebSocket != "undefined") {
        Socket_UK = new MozWebSocket(url,"usbkey-protocol");
    } else {
        this.Socket_UK = new WebSocket(url,"usbkey-protocol");
    }

    this.FindPort = function(start)
    {
        var msg =
            {
                FunName: "FindPort",
                start: start
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.FindPort_2 = function(start, in_data , verf_data)
    {
        var msg =
            {
                FunName: "FindPort_2",
                start: start,
                in_data: in_data,
                verf_data:verf_data
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.FindPort_3= function(start,in_data,verf_data)
    {
        var msg =
            {
                FunName: "FindPort_3",
                start: start,
                in_data: in_data,
                verf_data:verf_data
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.GetVersion= function(Path)
    {
        var msg =
            {
                FunName: "GetVersion",
                Path: Path
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.GetVersionEx= function(Path)
    {
        var msg =
            {
                FunName: "GetVersionEx",
                Path: Path
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.GetID_1= function(Path)
    {
        var msg =
            {
                FunName: "GetID_1",
                Path: Path
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.GetID_2= function(Path)
    {
        var msg =
            {
                FunName: "GetID_2",
                Path: Path
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };


    this.sRead= function(Path)
    {
        var msg =
            {
                FunName: "sRead",
                Path: Path
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.sWrite = function(InData, Path)
    {
        var msg =
            {
                FunName: "sWrite",
                InData: InData,
                Path:Path
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.sWrite_2= function(InData, Path)
    {
        var msg =
            {
                FunName: "sWrite_2",
                InData: InData,
                Path:Path
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.sWrite_2Ex= function(InData,Path)
    {
        var msg =
            {
                FunName: "sWrite_2Ex",
                InData: InData,
                Path:Path
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.sWriteEx= function(InData,Path)
    {
        var msg =
            {
                FunName: "sWriteEx",
                InData: InData,
                Path:Path
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.sWriteEx_New= function(InData,Path)
    {
        var msg =
            {
                FunName: "sWriteEx_New",
                InData: InData,
                Path:Path
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.sWrite_2Ex_New= function(InData,Path)
    {
        var msg =
            {
                FunName: "sWrite_2Ex_New",
                InData: InData,
                Path:Path
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.SetCal= function(Hkey,Lkey,new_Hkey,new_Lkey,Path)
    {
        var msg =
            {
                FunName: "SetCal",
                Hkey:   Hkey,
                Lkey:   Lkey,
                new_Hkey:new_Hkey,
                new_Lkey:new_Lkey,
                Path:Path

            };
        this.Socket_UK.send(JSON.stringify(msg));
    };


    this.SetBuf= function(InData,pos)
    {
        var msg =
            {
                FunName: "SetBuf",
                InData: InData,
                pos:pos
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.GetBuf= function(pos)
    {
        var msg =
            {
                FunName: "GetBuf",
                pos: pos
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.YRead= function(Address,HKey,LKey,Path)
    {
        var msg =
            {
                FunName: "YRead",
                Address:Address,
                HKey:HKey,
                LKey:LKey,
                Path:Path
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.YWrite= function(InData,Address,HKey,LKey,Path)
    {
        var msg =
            {
                FunName: "YWrite",
                InData:InData,
                Address:Address,
                HKey:HKey,
                LKey:LKey,
                Path:Path
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.YReadEx= function(Address,len, HKey,LKey,Path)
    {
        var msg =
            {
                FunName: "YReadEx",
                Address:Address,
                len:len,
                HKey:HKey,
                LKey:LKey,
                Path:Path
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.YWriteEx= function(Address,len,HKey,LKey,Path)
    {
        var msg =
            {
                FunName: "YWriteEx",
                Address:Address,
                len:len,
                HKey:HKey,
                LKey:LKey,
                Path:Path
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.YReadString= function(Address,len,HKey,LKey,Path)
    {
        var msg =
            {
                FunName: "YReadString",
                Address:Address,
                len:len,
                HKey:HKey,
                LKey:LKey,
                Path:Path
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.YWriteString= function(InString,Address,HKey,LKey,Path)
    {
        var msg =
            {
                FunName: "YWriteString",
                InString:InString,
                Address:Address,
                HKey:HKey,
                LKey:LKey,
                Path:Path
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.SetWritePassword= function(W_Hkey,W_Lkey,new_Hkey,new_Lkey,Path)
    {
        var msg =
            {
                FunName: "SetWritePassword",
                W_Hkey:W_Hkey,
                W_Lkey:W_Lkey,
                new_Hkey:new_Hkey,
                new_Lkey:new_Lkey,
                Path:Path
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.SetReadPassword= function(W_Hkey,W_Lkey,new_Hkey,new_Lkey,Path)
    {
        var msg =
            {
                FunName: "SetReadPassword",
                W_Hkey:W_Hkey,
                W_Lkey:W_Lkey,
                new_Hkey:new_Hkey,
                new_Lkey:new_Lkey,
                Path:Path
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };


    this.DecString= function(InString,Key)
    {
        var msg =
            {
                FunName: "DecString",
                InString:InString,
                Key:Key
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.EncString= function(InString,Path)
    {
        var msg =
            {
                FunName: "EncString",
                InString:InString,
                Path:Path
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.EncString_New= function(InString,Path)
    {
        var msg =
            {
                FunName: "EncString_New",
                InString:InString,
                Path:Path
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.Cal= function(Path)
    {
        var msg =
            {
                FunName: "Cal",
                Path:Path
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.Cal_New= function(Path)
    {
        var msg =
            {
                FunName: "Cal_New",
                Path:Path
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.SetCal_2= function(Key,Path)
    {
        var msg =
            {
                FunName: "SetCal_2",
                Key:Key,
                Path:Path
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.SetCal_New= function(Key,Path)
    {
        var msg =
            {
                FunName: "SetCal_New",
                Key:Key,
                Path:Path
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.SetEncBuf= function(InData,pos)
    {
        var msg =
            {
                FunName: "SetEncBuf",
                InData:InData,
                pos: pos
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.GetEncBuf= function(pos)
    {
        var msg =
            {
                FunName: "GetEncBuf",
                pos: pos
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };


    this.ReSet= function(Path)
    {
        var msg =
            {
                FunName: "ReSet",
                Path:Path
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.SetID= function(Seed,Path)
    {
        var msg =
            {
                FunName: "SetID",
                Seed:Seed,
                Path:Path
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.GetProduceDate= function(Path)
    {
        var msg =
            {
                FunName: "GetProduceDate",
                Path:Path
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.MacAddr= function()
    {
        var msg =
            {
                FunName: "MacAddr"
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };


    this.GetChipID= function(Path)
    {
        var msg =
            {
                FunName: "GetChipID",
                Path:Path
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.StarGenKeyPair= function(Path)
    {
        var msg =
            {
                FunName: "StarGenKeyPair",
                Path:Path
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.GenPubKeyY= function()
    {
        var msg =
            {
                FunName: "GenPubKeyY"
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.GenPubKeyX= function()
    {
        var msg =
            {
                FunName: "GenPubKeyX"
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.GenPriKey= function()
    {
        var msg =
            {
                FunName: "GenPriKey"
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.GetPubKeyY= function(Path)
    {
        var msg =
            {
                FunName: "GetPubKeyY",
                Path:Path
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.GetPubKeyX= function(Path)
    {
        var msg =
            {
                FunName: "GetPubKeyX",
                Path:Path
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.GetSm2UserName= function(Path)
    {
        var msg =
            {
                FunName: "GetSm2UserName",
                Path:Path
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.Set_SM2_KeyPair= function(PriKey,PubKeyX,PubKeyY,sm2UserName,Path )
    {
        var msg =
            {
                FunName: "Set_SM2_KeyPair",
                PriKey:PriKey,
                PubKeyX:PubKeyX,
                PubKeyY:PubKeyY,
                sm2UserName:sm2UserName,
                Path:Path
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.YtSign= function(SignMsg,Pin,Path)
    {
        var msg =
            {
                FunName: "YtSign",
                SignMsg:SignMsg,
                Pin:Pin,
                Path:Path
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.YtSign_2= function(SignMsg,Pin,Path)
    {
        var msg =
            {
                FunName: "YtSign_2",
                SignMsg:SignMsg,
                Pin:Pin,
                Path:Path
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.YtVerfiy= function(id,SignMsg,PubKeyX, PubKeyY,VerfiySign,Path)
    {
        var msg =
            {
                FunName: "YtVerfiy",
                id:id,
                SignMsg:SignMsg,
                PubKeyX:PubKeyX,
                PubKeyY:PubKeyY,
                VerfiySign:VerfiySign,
                Path:Path
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.SM2_DecString= function(InString,Pin,Path)
    {
        var msg =
            {
                FunName: "SM2_DecString",
                InString:InString,
                Pin:Pin,
                Path:Path
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.SM2_EncString= function(InString,Path)
    {
        var msg =
            {
                FunName: "SM2_EncString",
                InString:InString,
                Path:Path
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.YtSetPin= function(OldPin,NewPin,Path)
    {
        var msg =
            {
                FunName: "YtSetPin",
                OldPin:OldPin,
                NewPin:NewPin,
                Path:Path
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.FindU= function(start)
    {
        var msg =
            {
                FunName: "FindU",
                start: start
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.FindU_2= function(start,in_data,verf_data)
    {
        var msg =
            {
                FunName: "FindU_2",
                start: start,
                in_data: in_data,
                verf_data:verf_data
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.FindU_3= function(start,in_data,verf_data)
    {
        var msg =
            {
                FunName: "FindU_3",
                start: start,
                in_data: in_data,
                verf_data:verf_data
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.IsUReadOnly= function(Path)
    {
        var msg =
            {
                FunName: "IsUReadOnly",
                Path:Path
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.SetUReadOnly= function(Path)
    {
        var msg =
            {
                FunName: "SetUReadOnly",
                Path:Path
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.SetHidOnly= function(IsHidOnly,Path)
    {
        var msg =
            {
                FunName: "SetHidOnly",
                IsHidOnly:IsHidOnly,
                Path:Path
            };
        this.Socket_UK.send(JSON.stringify(msg));
    };

    this.ResetOrder = function()
    {
        var msg =
            {
                FunName: "ResetOrder"
            };
        this.Socket_UK.send(JSON.stringify(msg));
    }

    this.ContinueOrder = function()
    {
        var msg =
            {
                FunName: "ContinueOrder"
            };
        this.Socket_UK.send(JSON.stringify(msg));
    }

}