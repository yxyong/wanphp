<?php
/**
 * Created by PhpStorm.
 * User: 火子 QQ：284503866.
 * Date: 2020/9/17
 * Time: 15:00
 */

namespace App\Entities\Weixin;


use App\Entities\Traits\EntityTrait;

/**
 * Class UserRole
 * @package App\Entities\Weixin
 * @OA\Schema(
 *   title="用户角色",
 *   description="用户角色数据结构",
 *   required={"name"}
 * )
 */
class UserRoleEntity implements \JsonSerializable
{
  use EntityTrait;
  /**
   * @DBType({"key":"PRI","type":"tinyint(4) NOT NULL AUTO_INCREMENT"})
   * @OA\Property(format="int32", description="角色ID")
   * @var int|null
   */
  private $id;
  /**
   *
   * @DBType({"type":"varchar(20) NOT NULL DEFAULT ''"})
   * @OA\Property(description="角色名称")
   * @var string
   */
  private $name;
  /**
   * @DBType({"type":"tinyint(4) NOT NULL DEFAULT 0"})
   * @OA\Property(description="显示排序")
   * @var int
   */
  private $display_order;
}
