<?php
/**
 * Created by PhpStorm.
 * User: 火子 QQ：284503866.
 * Date: 2020/12/28
 * Time: 16:35
 */

namespace App\Application\Api\Manage\Weixin;


use App\Application\Api\Api;
use App\Infrastructure\Database\Redis;
use App\Infrastructure\Weixin\WeChatBase;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Class TagsApi
 * @title 用户标签
 * @route /api/manage/weixin/tag
 * @package App\Application\Api\Manage\Weixin
 */
class TagsApi extends Api
{
  private $weChatBase;
  private $redis;

  public function __construct(WeChatBase $weChatBase, Redis $redis)
  {
    $this->weChatBase = $weChatBase;
    $this->redis = $redis;
  }

  /**
   * @return Response
   * @throws \Exception
   * @OA\Post(
   *  path="/api/manage/weixin/tag",
   *  tags={"WeixinTag"},
   *  summary="添加公众号用户标签",
   *  operationId="addWeixinTag",
   *  security={{"bearerAuth":{}}},
   *   @OA\RequestBody(
   *     description="用户标签",
   *     required=true,
   *     @OA\MediaType(
   *       mediaType="application/json",
   *       @OA\Schema(type="object",@OA\Property(property="name",type="string"))
   *     )
   *   ),
   *  @OA\Response(response="201",description="添加成功",@OA\JsonContent(ref="#/components/schemas/Success")),
   *  @OA\Response(response="400",description="请求失败",@OA\JsonContent(ref="#/components/schemas/Error"))
   * )
   * @OA\Put(
   *  path="/api/manage/weixin/tag/{ID}",
   *  tags={"WeixinTag"},
   *  summary="修改公众号用户标签",
   *  operationId="editWeixinTag",
   *  security={{"bearerAuth":{}}},
   *   @OA\Parameter(
   *     name="ID",
   *     in="path",
   *     description="标签ID",
   *     required=true,
   *     @OA\Schema(format="int64",type="integer")
   *   ),
   *   @OA\RequestBody(
   *     description="用户标签",
   *     required=true,
   *     @OA\MediaType(
   *       mediaType="application/json",
   *       @OA\Schema(type="object",@OA\Property(property="name",type="string"))
   *     )
   *   ),
   *  @OA\Response(response="201",description="更新成功",@OA\JsonContent(ref="#/components/schemas/Success")),
   *  @OA\Response(response="400",description="请求失败",@OA\JsonContent(ref="#/components/schemas/Error"))
   * )
   * @OA\Delete(
   *  path="/api/manage/weixin/tag/{ID}",
   *  tags={"WeixinTag"},
   *  summary="删除公众号用户标签",
   *  operationId="delWeixinTag",
   *  security={{"bearerAuth":{}}},
   *  @OA\Parameter(
   *    name="ID",
   *    in="path",
   *    description="标签ID",
   *    required=true,
   *    @OA\Schema(format="int64",type="integer")
   *  ),
   *  @OA\Response(response="204",description="删除成功",@OA\JsonContent(ref="#/components/schemas/Success")),
   *  @OA\Response(response="400",description="请求失败",@OA\JsonContent(ref="#/components/schemas/Error"))
   * )
   * @OA\Get(
   *  path="/api/manage/weixin/tag",
   *  tags={"WeixinTag"},
   *  summary="用户角色",
   *  operationId="listWeixinTag",
   *  security={{"bearerAuth":{}}},
   *  @OA\Response(response="200",description="请求成功",@OA\JsonContent(ref="#/components/schemas/Success")),
   *  @OA\Response(response="400",description="请求失败",@OA\JsonContent(ref="#/components/schemas/Error"))
   * )
   */
  protected function action(): Response
  {
    switch ($this->request->getMethod()) {
      case 'POST':
        $data = $this->request->getParsedBody();
        if ($data['name'] != '') {
          $result = $this->weChatBase->createTag($data['name']);
          return $this->respondWithData($result, 201);
        } else {
          return $this->respondWithError('缺少标签名称');
        }
        break;
      case 'PUT':
        $id = $this->args['id'] ?? 0;
        $data = $this->request->getParsedBody();
        if ($id > 0 && $data['name'] != '') {
          $result = $this->weChatBase->updateTag($id, $data['name']);
          return $this->respondWithData($result, 201);
        } else {
          return $this->respondWithError('缺少ID或标签名称');
        }
        break;
      case 'DELETE':
        $id = $this->args['id'] ?? 0;
        if ($id > 0) {
          $result = $this->weChatBase->deleteTag($id);
          return $this->respondWithData($result, 204);
        } else {
          return $this->respondWithError('缺少ID');
        }
        break;
      case 'GET':
        //公众号粉丝数
        $user_total = $this->redis->get('wxuser_total');
        if (!$user_total) {
          $list = $this->weChatBase->getUserList();
          $user_total = $list['total'];
          $this->redis->set('wxuser_total', $user_total, 3600);
        }

        $userTags = $this->weChatBase->getTags();
        return $this->respondWithData([
          'tags' => $userTags['tags'] ?? [],
          'total' => $user_total,
        ]);
        break;
      default:
        return $this->respondWithError('禁止访问', 403);
    }
  }
}
