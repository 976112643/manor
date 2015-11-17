var ua = navigator.userAgent.toLowerCase();
var s = null;
var browser = {
    msie: (ua.indexOf("msie") > 0) ? true : false,
    firefox: (ua.indexOf("firefox") > 0) ? true : false,
    safari: (ua.indexOf("safari") > 0) ? true : false,
	chrome: (ua.indexOf("chrome") > 0) ? true : false
};


    /*Sim System */
    var System = new Object();
    System.ifTwowayUIPreferred = 1;
    System.AuthIPLoginStatus = 1;
    System.webServerIP = "";
    System.Pgt = "";
    System.AuthIPLoginPassword = "111111";
    System.CATVN = "123456";
    System.CAUserLevel = 105;
    System.CAIndustryType = 0;
    System.isHDPlatform = 7;
    System.newMessageNumber = 1;
	System.getWeatherInfo = "多云转晴" ;
	System.specialService = 0 ;
	System.getLoadMode = function(){return 0;};
	System.advCacheExist = function(){return 0};
    System.AuthIPmanualLogin = function(){
    };
    System.CWMPInformServiceChange = function(){
    };
    System.GetNetworkID = function(){
        return 20481;
    };
    System.enableAppAlertNotify = function(e){
    };
    System.getDefaultFreq = function(){
        return 38700;
    };
    System.getDefaultAssistantFreq = function(){
        return 63400;
    };
    System.getDefaultSymbol = function(){
        return 6875;
    };
    System.getDefaultQAM = function(){
        return 1;
    };
    System.GetOutputPort = function(){
        return 1;
    };
    System.GetOutputResolution = function(){
        return 1;
    };
    System.screenFormat = 0;
    System.screenContrary = 50;
    System.screenLight = 50;
    System.screenSaturation = 50;
    System.SetOutputPort = function(){
    };
	System.netmode = 2;
    System.SetOutputResolution = function(){
    };
    System.GetCARegionID = function(){
        return 20481;
    };
    System.SmartCardPaired = function(){
    };
    System.getTunerFrequency = function(){
        return 38700;
    };
    System.getTunerSymbolRate = function(){
        return 6875;
    };
    System.getTunerQamSize = function(){
        return 3;
    };
    System.getTunerSignalQuality = function(){
    };
    System.getTunerBitErrorRate = function(){
        return 3;
    };
    System.getTunerSignalstrength = function(){
        return 30;
    };
    System.getTunerSignalLevel = function(){
        return 30;
    };
    System.getICParentChildProperty = function(){
        return 1;
    };
    System.getChildICRemainingDays = function(){
    };
    System.getParentICIdentifier = function(){
    };
    System.smartcardId = 1234567890123456;
    System.GetPowerOffDurationTime = function(){
        return "01:00"
    };
    System.SetPowerOffDurationTime = function(a){
    };
    System.IPAddress = "10.133.201.135";
    System.IPMask = "255.255.255.128";
    System.IPGateway = "10.133.201.129";
    System.IPDNS = "172.16.64.2";
    System.IPDNS2 = "172.20.241.48";
    System.isDHCP = 1;
    System.wifiIPAddress = "10.63.67.162";
    System.wifiIPMask = "255.255.255.224";
    System.wifiIPGateway = "10.63.67.161";
    System.wifiIPDNS = "172.16.64.2";
    System.wifiIPDNS2 = "172.30.64.2";
    System.isWifiDHCP = 1;
    System.isNeedForceSearch = function(){
        return 0;
    };
    System.channelNormalSearch = function(){
    };
    System.stbID = "123456789123456789";
    System.softwareVersion = "henan-96266";
    System.hardwareVersion = "C5";
    System.loaderVersion = "12";
    System.appVersion = "HN-215";
    System.manufactureName = "stb-30";
    System.caVersion = "4.1";
    System.IPMacAddress = "00196852";
    System.manufactureCode = "010001";
    System.setTheDefaultAssistantFreqSymMode = function(){
    };
    System.setAudioMode = function(a){
        System.getAudioMode = a;
    };
    System.getAudioMode = 0;
	System.wifiEnable = 1 ;
	System.wifiListUpdate = true ;
	System.getLastWifiConected = function(){
		lastArray = ["11","DVB","zzdvn"];
		return lastArray;};
	System.wifiConnect = function(a,b){return 2;};
	System.getWifiInfo = function(){
		var wifiInfo = [
			["","DVB1","","",5,1,true,true],	
			["","wifiConnectge tLastWifiConected","","",3,0,false,false],	
			["","ggg3","","",2,2,true,false],	
			["","te4","","",1,1,false,false],	
			["","aa5","","",3,0,false,false],	
			["","ejjjee6","","",2,2,false,false],	
			["","luu7","","",1,1,false,false],	
			["","ba8","","",1,2,true,false]
		];
		return wifiInfo ;
	};
    var MP3 = new Object();
    MP3.stop = function(){
    };
    
    /*Sim CAAuth*/
    var CAAuth = new Object();
    CAAuth.getAuthList = function(){
        return 4;
    };
    CAAuth.getIdByCount = function(i){
        return i;
    };
    CAAuth.getTypeByCount = function(){
        return 1;
    };
    CAAuth.getNoByCount = function(){
        return 3;
    };
    CAAuth.getStartTimeByCount = function(i){
        if (i == 1) 
            return "11/11/11 00:00:00";
        else 
            return "11/12/11 00:00:00";
    };
    CAAuth.getEndTimeByCount = function(i){
        if (i == 1) 
            return "00:00:01";
        else 
            return "11:11:12";
    };
    /*Sim Dialog*/
    var Dialog = new Object();
    Dialog.setButtonNum = function(a){
    };
    Dialog.setTimeout = function(a){
    };
    Dialog.open = function(a, b){
        alert(b)
    };
    /*Sim movieWnd*/
    var movieWnd = new Object();
    movieWnd.serviceID = 1;
    movieWnd.play = function(){
    };
    movieWnd.fullScreened = true;
    
    /*Sim Media */
    var Media = new Object();
    Media.muteValue = 0;
    Media.volume = 12;
    Media.lockSta = 1;
	Media.audio = 5 ;
	Media.volumeMode = 0 ;
	Media.isFavorite = function(a){return (a<150)?1:0;}
	Media.setUNFavorite = function(a){return 0;}
	Media.setFavorite = function(a){return 0;}
    Media.isValidService = function(){
        return 1;
    };
	Media.setLock = function(a){return 0;}
	Media.setUnLock = function(a){return 0;}
    Media.setBrowserPlayerState = function(){
    };
    Media.isMute = function(){
        return Media.muteValue;
    };
    Media.setMute = function(val){
        Media.muteValue = val;
    };
    Media.isLock = function(ser){
		if(ser > 900 && ser < 915)
			return 0;
		else 
			return 1;
    };
    Media.setBrowserPlayerState = function(){
    };
    Media.getVolume = function(){
        return Media.volume;
    };
    Media.volumeUp = function(){
        Media.volume++;
    };
    Media.volumeDown = function(){
        Media.volume--;
    };
    Media.getVolumeByServiceID = function(){
        return Media.volume;
    };
    Media.volumeUpByServiceID = function(){
        Media.volume++;
    };
    Media.volumeDownByServiceID = function(){
        Media.volume--;
    };
    Media.stop = function(){
    };
    Media.getBookService = function(){
        return 0;
    };
    Media.setBookService = function(){
    };
    Media.setLockStatus = function(str){
        Media.lockSta = 0;
    };
    Media.getVolumeMode = function(){
        return Media.volumeMode;
    };
    Media.setVolumeMode = function(str){
		Media.volumeMode = str ;
    };
	System.setAudioMode = function(str){
		Media.audio = str ;
	};
	System.getAudioMode = function (){
		return Media.audio ;
	}
    Media.getAudioTrack = function(){
        return 1;
    }
    Media.setAudioTrack = function(){
    };
    Media.getTimeShiftingURL = function(){
        return "www.baidu.com"
    };
    Media.playAudioPID = function(){
    };
    Media.isEventBooked = function(){
        return false;
    };
    Media.getLastService = function(){
    };
    /*Sim Mosaic*/
    var Mosaic = new Object();
    Mosaic.getMosaicLogicalCellServiceIdByIndex = function(){
        return 1;
    };
    
    /*Sim ServiceList*/
    function ServiceList(){
    }
    ServiceList.prototype.isServiceListEmpty = function(){
    };
    ServiceList.prototype.GetServiceList = function(){
    };
    ServiceList.prototype.getCurrentServiceInfo = function(){
        return serInfo;
    };
    ServiceList.prototype.serviceCount = function(){
        return 0;
    };
    ServiceList.prototype.isServiceListEmpty = function(){
    };
    ServiceList.prototype.getServiceInfoByServiceID = function(){
        return serInfo;
    };
    ServiceList.prototype.serviceCount = 9;
    ServiceList.prototype.list = new Array("1", "2", "2", "2", "5", "6", "6", "6", "6");
    ServiceList.prototype.getServiceScheduleByDay = function(){
        return serInfo;
    }
    
    /*Sim serInfo*/
    var serInfo = new Object();
    serInfo.eventPresentName = "里约大冒险";
    serInfo.eventPresentStartTime = "16:30";
    serInfo.eventPresentEndTime = "17:50";
    serInfo.eventFollowingName = "下个测试节目下个测试节目下个测试";
    serInfo.eventFollowingStartTime = "17:30";
    serInfo.eventFollowingEndTime = "18:30";
	serInfo.isTimeshift = 1 ;
    
    /*Sim Global*/
    function Global(){
        this.value = "default";
    }
    
    /* Sim CAMail*/
    var CAMail = new Object();
    CAMail.getAllMail = function(){
        return 9;
    };
    CAMail.getTypeByCount = function(){
        return 2;
    };
    CAMail.getMsgByCount = function(){
        return "里约大冒险";
    };
    CAMail.getTimeByCount = function(){
        return "00:00:00";
    };
    CAMail.getIsReadByCount = function(){
        return 1;
    };
    
    /*Sim vod*/
    var VODMedia = new Object();
    var vodTrack = 0;
    var vodCurPos = 123;
    var vodIsMute = 0;
    var vodCurVolume = 6;
    VODMedia.clearIframe = function(){
    };
    VODMedia.getUrl = function(){
        return "dsmcc://192.168.16.210:13819/802?billingId=Billing001&starttime=0&name=绝望的主妇第六季影片&duration=2700000&bookmarkip=192.168.12.253&bookmarkport=9001&bm";
    };
    VODMedia.play = function(){
    };
    VODMedia.exit = function(){
    };
    VODMedia.getPlayInfo = function(){
        var playInfo = [{
            duration: 1234,
            programName: "vod测试1"
        }, {
            duration: 4321,
            programName: "vod测试2"
        }];
        return playInfo[0];
    };
    VODMedia.pausePlay = function(){
    };
    VODMedia.getCurrentPos = function(){
        return vodCurPos;
    };
    VODMedia.resumePlay = function(){
    };
    VODMedia.backward = function(){
    };
    VODMedia.forward = function(){
    };
	VODMedia.showIframe = function(str){};
    VODMedia.getAudioTrack = function(){
        return vodTrack;
    };
    VODMedia.setAudioTrack = function(num){
        vodTrack = num;
    };
    VODMedia.jumpTo = function(num){
        vodCurPos = num;
    };
    VODMedia.isMute = function(){
        return vodIsMute;
    }
    VODMedia.setMute = function(num){
        vodIsMute = num;
    };
    VODMedia.getVolume = function(){
        return vodCurVolume;
    };
    VODMedia.volumeDown = function(){
        vodCurVolume--;
    };
    VODMedia.volumeUp = function(){
        vodCurVolume++;
    };
    
    /*Sim fileSystem*/
    var FileSystem = new Object();
    var fileFocus = 0;
    var folder = new Object;
    var folder1 = new Object;
    FileSystem.drives = [{
        path: "A",
        driveType: 1,
        totalSize: 12345,
        availableSpace: 123456
    }, {
        path: "B",
        driveType: 2,
        totalSize: 54321,
        availableSpace: 4321
    }];
    FileSystem.getDrive = function(a){
        var file_a = [{
            name: "文件1",
            path: "A/a",
            rootFolder: "png",
            size: 1234
        }, {
            name: "文件2",
            path: "A/a",
            rootFolder: "png",
            size: 1234
        }, {
            name: "文件3",
            path: "A/a",
            rootFolder: "mp3",
            size: 1234
        }];
        return file_a;
    };
    FileSystem.getFile = function(){
        var file_a = [{
            name: "文件1",
            path: "A/a",
            extensionName: "png",
            size: 1234
        }, {
            name: "文件2",
            path: "A/a",
            extensionName: "png",
            size: 1234
        }, {
            name: "文件3",
            path: "A/a",
            extensionName: "mp3",
            size: 1234
        }];
        return file_a;
    }
    FileSystem.driveExists = function(){
        return true;
    };
    FileSystem.getFolder = function(){
        return folder1;
    };
    folder1.fileObjects = folder;
    folder1.getSubFolders = function(){
        return folder;
    };
    folder.moveFirst = function(){
        fileFocus = 0
    };
    folder.getFolder = function(){
        var f = [{
            name: "f1",
            path: "A/a1"
        }, {
            name: "f2",
            path: "A/a1"
        }, {
            name: "f3",
            path: "A/a1"
        }, {
            name: "f4",
            path: "A/a1"
        }, {
            name: "f5",
            path: "A/a1"
        }];
        return f[fileFocus];
    };
    folder.moveNext = function(){
        fileFocus++
    };
    folder.atEnd = function(){
        if (fileFocus < 3) 
            return false;
        else 
            return true;
    };
    folder.getFile = function(){
        var f = [{
            name: "e1",
            path: "B/a1"
        }, {
            name: "e2",
            path: "A/a1"
        }, {
            name: "e3",
            path: "A/a1"
        }, {
            name: "e4",
            path: "A/a1"
        }, {
            name: "f5",
            path: "A/a1"
        }];
        return f[fileFocus];
    };
    
    /*Sim mosaic*/
    var Mosaic = new Object();
    var arrayMosaicTop = new Array(90, 90, 90, 186, 186, 282, 282, 378, 378, 378, 378, 378);
    var arrayMosaicLeft = new Array(60, 180, 540, 60, 540, 60, 540, 60, 180, 300, 420, 540);
    var arrayMosaicHeight = new Array(91, 283, 91, 91, 91, 91, 91, 91, 91, 91, 91, 91);
    var arrayMosaicWidth = new Array(115, 355, 115, 115, 115, 115, 115, 115, 115, 115, 115, 115);
    Mosaic.getMosaicCountByServiceId = function(){
        return 12;
    };
    Mosaic.getMosaicLogicalCellServiceIdByIndex = function(){
    };
    Mosaic.getMosaicLogicalCellTopByIndex = function(i){
        return arrayMosaicTop[i];
    };
    Mosaic.getMosaicLogicalCellLeftByIndex = function(i){
        return arrayMosaicLeft[i];
    };
    Mosaic.getMosaicLogicalCellHeightByIndex = function(i){
        return arrayMosaicHeight[i];
    };
    Mosaic.getMosaicLogicalCellWidthByIndex = function(i){
        return arrayMosaicWidth[i];
    };
    Mosaic.getMosaicLogicalCellTypeByIndex = function(){
        return 2;
    };
    Mosaic.getMosaicLogicalCellAudioPIDByIndex = function(){
    };
    /*Sim win*/
    function hasNetwork(){
        return 0;
    }
    
    function setAlpha(num){
    }
    
    function enableBrowserBackKey(){
    }
    
    function clearAllDialog(){
    
    }
    function siLock(a, b){
    };
    function writeFrontPanel(str){
    }
    function debugwin(str){
        alert(str);
    }
