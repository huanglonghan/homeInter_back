
TCP
	客户端上传：
		{
			opt:heart,
			tCode:dwasdwadw,
			lon:1000,
			lat:10.00,
			time:123123123,
		}

		{
		    "opt": "connect",
		    "aID": "1234567890",
		    "tCode":"1234567890",
		    "time":"1234567890",
		    "lat": 123.01,
		    "lon": 123.01
		}

	服务端回传：
		{
			"opt":"updatelocation",
			"online":3,
			"uData":[
						{"id":"","lon":"","lat":"","bear":""},
						{"id":"","lon":"","lat":"","bear":""},
						{"id":"","lon":"","lat":"","bear":""},
					]
		}

HTTP
	登录：
		account
		passwd
		time
	return：
		tCode
		sPort
	
	注册：
		account
		passwd
		time
	return:
		yes/no



