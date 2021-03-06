<?php
/**
 * Created by PhpStorm.
 * User: 火子 QQ：284503866.
 * Date: 2021/1/26
 * Time: 13:51
 */

namespace App\Application\Api\Manage\Admin;


use App\Application\Api\Api;
use App\Domain\Admin\AdminInterface;
use App\Domain\DomainException\DomainException;
use Psr\Http\Message\ResponseInterface as Response;

class BindUserApi extends Api
{
  private $admin;

  public function __construct(AdminInterface $admin)
  {
    $this->admin = $admin;
  }

  /**
   * @return Response
   * @throws DomainException
   * @OA\Get(
   *  path="/api/manage/admin/binduser/{UID}",
   *  tags={"Admin"},
   *  summary="用户绑定管理员",
   *  operationId="adminBindUser",
   *  security={{"bearerAuth":{}}},
   *  @OA\Parameter(
   *    name="uid",
   *    in="path",
   *    description="用户ID",
   *    required=true,
   *    @OA\Schema(format="int64",type="integer")
   *  ),
   *  @OA\Response(
   *    response="200",
   *    description="请求成功",
   *    @OA\JsonContent(
   *      allOf={
   *       @OA\Schema(ref="#/components/schemas/Success"),
   *       @OA\Schema(
   *         @OA\Property(property="res",example={"id": 0})
   *       )
   *      }
   *    )
   *  ),
   *  @OA\Response(response="400",description="请求失败",@OA\JsonContent(ref="#/components/schemas/Error"))
   * )
   */
  protected function action(): Response
  {
    return $this->respondWithData(['id' => $this->admin->get('id', ['uid' => $this->args['uid']])],);
  }
}
