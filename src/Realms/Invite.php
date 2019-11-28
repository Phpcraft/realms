<?php
namespace Phpcraft\Realms;
use hellsh\UUID;
use Phpcraft\Account;
class Invite
{
	/**
	 * @var Account $account
	 */
	public $account;
	/**
	 * @var int $id
	 */
	public $id;
	/**
	 * @var string $server_name
	 */
	public $server_name;
	/**
	 * @var string $server_description
	 */
	public $server_description;
	/**
	 * @var string $server_owner_name
	 */
	public $server_owner_name;
	/**
	 * @var UUID $server_owner_uuid
	 */
	public $server_owner_uuid;
	/**
	 * @var int $invite_time
	 */
	public $invite_time;

	/**
	 * @param Account $account
	 * @param array $data
	 */
	function __construct(Account $account, array $data)
	{
		$this->account = $account;
		$this->id = $data["invitationId"];
		$this->server_name = $data["worldName"];
		$this->server_description = $data["worldDescription"];
		$this->server_owner_name = $data["worldOwnerName"];
		$this->server_owner_uuid = new UUID($data["worldOwnerUuid"]);
		$this->invite_time = round($data["date"] / 1000);
	}

	/**
	 * @return void
	 */
	function accept(): void
	{
		Realms::sendRequest($this->account, "PUT", "/invites/accept/".$this->id);
	}

	/**
	 * @return void
	 */
	function reject(): void
	{
		Realms::sendRequest($this->account, "PUT", "/invites/reject/".$this->id);
	}

	/**
	 * Returns all invites the given account currently has pending.
	 *
	 * @param Account $account
	 * @return array<Invite>
	 */
	static function getRealmsInvites(Account $account): array
	{
		$invites = [];
		foreach(json_decode(Realms::sendRequest($account, "GET", "/invites/pending"), true)["invites"] as $invite)
		{
			array_push($invites, new Invite($account, $invite));
		}
		return $invites;
	}
}
