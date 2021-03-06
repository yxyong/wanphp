<?php
declare(strict_types=1);

namespace App\Entities\Weixin;

use App\Entities\Traits\EntityTrait;
use JsonSerializable;

/**
 * Class MsgTemplateEntity
 * @package App\Entities\Weixin
 * @OA\Schema(
 *   title="消息模板",
 *   description="微信消息模板",
 *   required={"template_id_short","template_id"}
 * )
 */
class MsgTemplateEntity implements JsonSerializable
{
  use EntityTrait;
  /**
   * @DBType({"key":"PRI","type":"tinyint NOT NULL AUTO_INCREMENT"})
   * @var integer|null
   * @OA\Property(description="用户ID")
   */
  private $id;
  /**
   * @DBType({"key":"UNI","type":"varchar(30) NOT NULL DEFAULT ''"})
   * @var string
   * @OA\Property(description="模板消息编号")
   */
  private $template_id_short;
  /**
   * "key":"UNI",
   * @DBType({"type":"varchar(50) NOT NULL DEFAULT ''"})
   * @var string
   * @OA\Property(description="模板消息ID")
   */
  private $template_id;
  /**
   * @DBType({"type":"tinyint(1) NOT NULL DEFAULT '0'"})
   * @var integer
   * @OA\Property(description="是否可用")
   */
  private $status;

  /**
   * 初始化
   * @param array $array
   */
  public function __construct(array $array)
  {
    foreach (array_intersect_key($array, $this->jsonSerialize()) as $key => $value) {
      $this->{$key} = $value;
    }
  }

}
