<?php
declare(strict_types=1);

namespace App\Entities\Weixin;

use App\Entities\Traits\EntityTrait;
use JsonSerializable;

/**
 * Class UserEntity
 * @package App\Entities\Weixin
 * @OA\Schema(
 *   title="用户",
 *   description="用户数据结构",
 *   required={"nickname","headimgurl","sex"}
 * )
 */
class UserEntity implements JsonSerializable
{
  use EntityTrait;
  /**
   * @DBType({"key":"PRI","type":"int NOT NULL AUTO_INCREMENT"})
   * @var integer|null
   * @OA\Property(format="int64", description="用户ID")
   */
  private $id;
  /**
   * @DBType({"key":"UNI","type":"varchar(29) NULL DEFAULT NULL"})
   * @var string
   * @OA\Property(description="微信unionid")
   */
  private $unionid;
  /**
   * @DBType({"type":"varchar(80) NOT NULL DEFAULT ''"})
   * @var string
   * @OA\Property(description="微信昵称")
   */
  private $nickname;
  /**
   * @DBType({"type":"varchar(300) NOT NULL DEFAULT ''"})
   * @var string
   * @OA\Property(description="微信头像")
   */
  private $headimgurl;
  /**
   * @DBType({"type":"tinyint(1) NOT NULL DEFAULT '0'"})
   * @var integer
   * @OA\Property(enum={0, 1, 2},description="姓别（1男，2女，0保密）")
   */
  private $sex;
  /**
   * @DBType({"type":"tinyint(4) NOT NULL DEFAULT '0'"})
   * @var integer
   * @OA\Property(description="用户角色ID")
   */
  private $role_id;
  /**
   * @DBType({"key":"MUL","type":"varchar(30) NOT NULL DEFAULT ''"})
   * @var string
   * @OA\Property(description="用户姓名")
   */
  private $name;
  /**
   * @DBType({"key":"UNI","type":"varchar(30) NULL DEFAULT NULL"})
   * @var string
   * @OA\Property(description="用户联系电话")
   */
  private $tel;
  /**
   * @DBType({"type":"varchar(200) NOT NULL DEFAULT ''"})
   * @var string
   * @OA\Property(description="用户默认地址")
   */
  private $address;
  /**
   * @DBType({"type":"int NOT NULL DEFAULT '0'"})
   * @var integer
   * @OA\Property(description="用户当前可用积分")
   */
  private $integral;
  /**
   * @DBType({"type":"decimal(15,2) NOT NULL DEFAULT '0'"})
   * @var  float
   * @OA\Property(description="用户当前可提现金额")
   */
  private $cash_back;
  /**
   * @DBType({"type":"decimal(15,2) NOT NULL DEFAULT '0'"})
   * @var  float
   * @OA\Property(description="用户充值余额")
   */
  private $money;
}
