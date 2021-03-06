<?php
/**
 * Created by PhpStorm.
 * User: 火子 QQ：284503866.
 * Date: 2020/12/28
 * Time: 15:48
 */

namespace App\Application\Api\Manage\Users;


use App\Application\Api\Api;
use App\Domain\DomainException\DomainException;
use App\Domain\Weixin\UserInterface;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Class UserApi
 * @title 用户管理
 * @route /api/manage/users
 * @package App\Application\Api\Manage\Users
 */
class UserApi extends Api
{
  private $user;

  public function __construct(UserInterface $user)
  {
    $this->user = $user;
  }

  /**
   * @return Response
   * @throws DomainException
   * @OA\Patch(
   *  path="/api/manage/users/{ID}",
   *  tags={"User"},
   *  summary="更新用户，管理员操作",
   *  operationId="editUser",
   *  security={{"bearerAuth":{}}},
   *   @OA\Parameter(
   *     name="ID",
   *     in="path",
   *     description="用户ID",
   *     required=true,
   *     @OA\Schema(format="int64",type="integer")
   *   ),
   *  @OA\RequestBody(
   *    description="指定更新用户数据",
   *    required=true,
   *    @OA\MediaType(
   *      mediaType="application/json",
   *      @OA\Schema(ref="#/components/schemas/UserEntity"),
   *      example={"name": "", "tel": null, "address": "", "integral": "0", "cash_back": "0.00", "money": "0.00"}
   *    )
   *  ),
   *  @OA\Response(
   *    response="201",
   *    description="用户更新成功",
   *  @OA\JsonContent(
   *     allOf={
   *      @OA\Schema(ref="#/components/schemas/Success"),
   *      @OA\Schema(
   *        @OA\Property(property="res", @OA\Property(property="up_num",type="integer",description="更新数量"))
   *      )
   *     }
   *   )
   *  ),
   *  @OA\Response(response="400",description="请求失败",@OA\JsonContent(ref="#/components/schemas/Error"))
   * )
   * @OA\Get(
   *   path="/api/manage/users/{ID}",
   *   tags={"User"},
   *   summary="查看用户信息，管理员查看",
   *   operationId="loadUser",
   *   security={{"bearerAuth":{}}},
   *   @OA\Parameter(
   *     name="ID",
   *     in="path",
   *     description="用户ID",
   *     required=true,
   *     @OA\Schema(format="int64",type="integer")
   *   ),
   *   @OA\Response(
   *    response="200",
   *    description="用户信息",
   *    @OA\JsonContent(
   *      allOf={
   *       @OA\Schema(ref="#/components/schemas/Success"),
   *       @OA\Schema(
   *         @OA\Property(property="res",ref="#/components/schemas/UserEntity")
   *       )
   *      }
   *    )
   *  ),
   *  @OA\Response(response="400",description="请求失败",@OA\JsonContent(ref="#/components/schemas/Error"))
   * )
   * @OA\Get(
   *   path="/api/manage/users",
   *   tags={"User"},
   *   summary="用户信息列表，管理获取",
   *   operationId="ListUsers",
   *   security={{"bearerAuth":{}}},
   *  @OA\Parameter(
   *    name="size",
   *    in="query",
   *    description="每页返回数量",
   *    @OA\Schema(format="int64",type="integer",default=10)
   *  ),
   *  @OA\Parameter(
   *    name="page",
   *    in="query",
   *    description="页码",
   *    @OA\Schema(format="int64",type="integer",default=1)
   *  ),
   *  @OA\Parameter(
   *    name="keyword",
   *    in="query",
   *    description="关键词",
   *    required=false,
   *    @OA\Schema(type="string")
   *  ),
   *   @OA\Response(
   *    response="200",
   *    description="用户信息",
   *    @OA\JsonContent(
   *      allOf={
   *       @OA\Schema(ref="#/components/schemas/Success"),
   *       @OA\Schema(
   *         @OA\Property(property="res",example={
  "id": "",
  "headimgurl": "用户头像","nickname":"用户昵称",
  "role": "用色",
  "name": "Name",
  "tel": "Tel"
  })
   *       )
   *      }
   *    )
   *  ),
   *  @OA\Response(response="400",description="请求失败",@OA\JsonContent(ref="#/components/schemas/Error"))
   * )
   */
  protected function action(): Response
  {
    switch ($this->request->getMethod()) {
      case 'PATCH':
        $data = $this->request->getParsedBody();
        if (empty($data)) return $this->respondWithError('无用户数据');
        $num = $this->user->update($data, ['id' => $this->args['id']]);
        return $this->respondWithData(['up_num' => $num], 201);
        break;
      case 'GET':
        $id = $this->args['id'] ?? 0;
        if ($id > 0) {
          $user = $this->user->get('id,nickname,headimgurl,name,tel,email,status,role_id', ['id' => $id]);
          return $this->respondWithData($user);
        }

        $where = [];
        $params = $this->request->getQueryParams();
        if (!empty($params['keyword'])) {
          $keyword = trim($params['keyword']);
          $where['OR'] = [
            'name[~]' => $keyword,
            'nickname[~]' => $keyword,
            'tel[~]' => $keyword
          ];
        }
        if (isset($params['page'])) {
          $cur_page = $params['page'] > 0 ? $params['page'] : 1;
          $pageSize = isset($params['size']) && $params['size'] > 0 ? $params['size'] : 10;
          $where['LIMIT'] = [($cur_page - 1) * $pageSize, $pageSize];
          if ($cur_page == 1) $total = $this->user->count('id', $where);
        } else {
          $where['LIMIT'] = 10;
        }

        $where['ORDER'] = ["id" => "DESC"];
        $users = $this->user->select('id,nickname,headimgurl,name,tel,email,status,role_id', $where);
        return $this->respondWithData(['users' => $users, 'total' => $total ?? null]);
        break;
      default:
        return $this->respondWithError('禁止访问', 403);
    }
  }
}
