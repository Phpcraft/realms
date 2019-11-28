<?php
namespace Phpcraft\Realms;
use Phpcraft\
{Account, Phpcraft, Versions};
abstract class Realms
{
	/**
	 * Sends an HTTP request to the Realms API.
	 *
	 * @param Account $account The account to issue the request as. The account in question has to be "online mode" ready.
	 * @param string $method The request method.
	 * @param string $path The path of the request, starting with a slash.
	 * @return bool|string The result of curl_exec.
	 */
	static function sendRequest(Account $account, string $method, string $path)
	{
		$ch = curl_init();
		echo "> $method $path";
		curl_setopt_array($ch, [
			CURLOPT_URL => "https://pc.realms.minecraft.net".$path,
			CURLOPT_CUSTOMREQUEST => $method,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER => [
				"Cache-Control: no-cache",
				"Cookie: sid=token:{$account->accessToken}:{$account->profileId};user={$account->username};version=".array_keys(Versions::releases(true))[0],
				"User-Agent: Phpcraft"
			]
		]);
		if(Phpcraft::isWindows())
		{
			curl_setopt($ch, CURLOPT_CAINFO, Phpcraft::SRC_DIR."/cacert.pem");
		}
		$res = curl_exec($ch);
		echo " ".curl_getinfo($ch, CURLINFO_HTTP_CODE)."\n< $res\n";
		curl_close($ch);
		return $res;
	}
}
