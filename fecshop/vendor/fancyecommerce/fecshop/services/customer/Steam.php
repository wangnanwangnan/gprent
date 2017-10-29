<?php
namespace fecshop\services\customer;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use common\helper\LightOpenID;
use common\helper\Crypt\Crypt_RSA;
use common\helper\Crypt\Math\Math_BigInteger;

class Steam extends Component
{
	private $gameid = 730;
	private $cookieFile = '/tmp/steam.cookie';
	
	public function curl($url, $post=null,$refer=null,$type="0",$header=null) {//curl封装 
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, $header); 
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt ($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36 FirePHP/4Chrome');
		@curl_setopt($curl, CURLOPT_POST, 1);
		@curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
	   if(isset($refer)){
				curl_setopt($curl, CURLOPT_REFERER, $refer);
			}  
		if($type=="1"){
		curl_setopt($curl, CURLOPT_COOKIEJAR, $this->cookieFile);
		}
		curl_setopt($curl, CURLOPT_COOKIEFILE, $this->cookieFile); 
	    $rs= curl_exec($curl);
		curl_close($curl);
		
		return $rs;	
	}

	public function getinventory(){
		$session = Yii::$app->session;
		
		$gameid = $this->gameid;
		$steamid = $session['steamid'];

		return file_get_contents('http://steamcommunity.com/inventory/'.$steamid.'/'.$gameid.'/2');
	}
	
	public function getSession()//获取sessionID[返回String]
	{
        $response = $this->curl('http://steamcommunity.com/');
        $pattern = '/g_sessionID = (.*);/';
        preg_match($pattern, $response, $matches);
        if (!isset($matches[1])) {
            echo 'Unexpected response from Steam.';
        }
        $res = str_replace('"', '', $matches[1]);
		
		return $res;
	} 
	
	public function toCommunityID($id) {//格式化steamid[返回String]
		if (preg_match('/^STEAM_/', $id)) {
			$parts = explode(':', $id);
			return bcadd(bcadd(bcmul($parts[2], '2'), '76561197960265728'), $parts[1]);
		} elseif (is_numeric($id) && strlen($id) < 16) {
			return bcadd($id, '76561197960265728');
		} else {
			return $id;
		}
	}

	public function robootLogin($twofa){
		//$username = 'wangnan_0';
		//$password = 'fanshuo0108';
        $username = 'ruilifei';
        $password = 'Ruilifei910102';
		$post = array ('username' => $username);
		$url = "https://steamcommunity.com/login/getrsakey";
		$json= json_decode($this->curl($url, $post),true);
		
		$rsa = new Crypt_RSA();
		$rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);

		$key = [
			'modulus' => new Math_BigInteger($json['publickey_mod'], 16),
			'publicExponent' => new Math_BigInteger($json['publickey_exp'], 16)
		];
		$rsa->loadKey($key, CRYPT_RSA_PUBLIC_FORMAT_RAW);

		$encryptedPassword = base64_encode($rsa->encrypt($password));
		$params = [
            'username' => $username,
            'password' => $encryptedPassword,
            'twofactorcode' => $twofa,
            'captchagid' => '-1',
            'captcha_text' => '',
            'emailsteamid' => '',
            'emailauth' => '',
            'rsatimestamp' => $json['timestamp'],
            'remember_login' => true
        ];
		$loginResponse = $this->curl('https://steamcommunity.com/login/dologin/', $params,"1",1); 
		$loginJson = json_decode($loginResponse, true);

		return $loginJson;
	}

	//发送交易请求[token:卖家的trade_url上的token,json:交易参数,accountid:家的steamID][返回JSON]
	public function send($token, $json, $accountid)
	{
        $url = 'https://steamcommunity.com/tradeoffer/new/send';
        $referer = 'https://steamcommunity.com/tradeoffer/new/?partner='.$accountid.'&token='.$token;
		
		$params = [
            'sessionid' => $this->getSession(),//身份验证用
            'serverid' => '1',
            'partner' => $this->toCommunityID($accountid),//目标steamID
            'tradeoffermessage' => time(),//交易留言
            'json_tradeoffer' => $json,//交易传参,type:json
            'trade_offer_create_params' => (empty($token) ? "{}" : json_encode([
                'trade_offer_access_token' => $token//目标第三方交易Token
            ]))
		];
		print_r($params);

        $response = $this->curl($url, $params,$referer, 1, 1);
		print_r($response);
		$json = json_decode($response, true);
        if (is_null($json)) {
            echo 'Empty response';
        } else {
            if (isset($json['tradeofferid'])) {
                return  $json['tradeofferid'];
            } else {
                echo $json['strError'];
        
            }
        }     
	}

	public function getoffer($key,$tradeOfferId) {//获取交易细节[key:API秘钥,tradeOfferId:交易ID][返回JSON]
			return apirequest($key,
				array(
					'method' => 'GetTradeOffer/v1',
					'params' => array('tradeofferid' => $tradeOfferId,'language'=>'CN'),
				)
			);
	}

	public function canceloffer($key,$tradeOfferId) {//取消交易[key:API秘钥,tradeOfferId:交易ID][返回BOOLEAN]
			return apirequest($key,
				array(
					'method' => 'CancelTradeOffer/v1',
					'params' => array('tradeofferid' => $tradeOfferId),
				)
			);
	}

	public function declineoffer($key,$tradeOfferId) {//拒绝交易[key:API秘钥,tradeOfferId:交易ID][返回BOOLEAN]
			 return apirequest($key,
				array(
					'method' => 'DeclineTradeOffer/v1',
					'param' => array('tradeofferid' => $tradeOfferId),
					'post' => 1
				)
			);
	}

	public function acceptoffer($option) {//接受交易[option:交易ID][返回BOOLEAN]
			$form = array(
				'sessionid' => getSession(),
				'serverid' => 1,
				'tradeofferid' => $option,
				'partner' => '76561198218431108'
				);
			$referer = 'https://steamcommunity.com/tradeoffer/'.$option.'/';
			$response = curl('https://steamcommunity.com/tradeoffer/'.$option.'/accept',$form,$referer);
			 print_r($response);
	}

	public function apirequest($key,$option){//发起API类请求[key:API秘钥,option:请求参数][返回JSON]
		$url = 'https://api.steampowered.com/IEconService/'.$option['method'].'/?key='.$key.($option['post'] ? '' : ('&'.http_build_query($option['params'])));
		$res=curl($url,$option['param']);
		
		return $res;
	}
	
	public function getgamelist($nickname){//获取用户的游戏列表[nickname:玩家昵称][返回JSON]
		$content=file_get_contents('http://steamcommunity.com/id/$nickname/inventory/');
		$content=preg_replace("/[\t\n\r]+/","",$content);
		preg_match_all('/<option data-appid="([\S\s]*?)" value="([\S\s]*?)">([\S\s]*?)<\/option>/',$content,$rs);
		
		return json_encode($rs[1]);
	}


	public function login()
	{
        $steamKey = Yii::$app->params['steam']['key'];

		$steamauth['apikey'] = $steamKey; // Your Steam WebAPI-Key found at http://steamcommunity.com/dev/apikey
		//$steamauth['domainname'] = ""; // The main URL of your website displayed in the login page
		$steamauth['logoutpage'] = ""; // Page to redirect to after a successfull logout (from the directory the SteamAuth-folder is located in) - NO slash at the beginning!
		$steamauth['loginpage'] = "http://wap-test.gprent.cn/customer/account/registerbysteam"; // Page to redirect to after a successfull login (from the directory the SteamAuth-folder is located in) - NO slash at the beginning!
	// System stuff
		//if (empty($steamauth['domainname'])) {$steamauth['domainname'] = $_SERVER['SERVER_NAME'];}
		if (empty($steamauth['logoutpage'])) {$steamauth['logoutpage'] = $_SERVER['PHP_SELF'];}
		if (empty($steamauth['loginpage'])) {$steamauth['loginpage'] = $_SERVER['PHP_SELF'];}
	
		$session = Yii::$app->session;
		
		$openid = new LightOpenID($_SERVER['SERVER_NAME']);
		if(!$openid->mode) {
			$openid->identity = 'http://steamcommunity.com/openid'; 
			header('Location: ' . $openid->authUrl()); 
            exit;
		} elseif ($openid->mode == 'cancel') {
			echo 'User has canceled authentication!';
		} else {
			if($openid->validate()) {
				$id = $openid->identity;
				$ptn = "/^http:\/\/steamcommunity\.com\/openid\/id\/(7[0-9]{15,25}+)$/";
				preg_match($ptn, $id, $matches);

				$session['steamid'] = $matches[1];
				if (!headers_sent()) {
					header('Location: '.$steamauth['loginpage']);
                    exit;
                } else {
					echo '<script type="text/javascript">';
                    echo 'window.location.href="'.$steamauth['loginpage'].'";';
                    echo '</script>';
                    echo '<noscript>';
                    echo '<meta http-equiv="refresh" content="0;url='.$steamauth['loginpage'].'" />';
                    echo '</noscript>'; exit;
                }
						
			} else {
				echo "User is not logged in.\n";
			}		
		}
	}
}
