<?php
/**
 * Created by PhpStorm.
 * User: 火子 QQ：284503866.
 * Date: 2020/8/26
 * Time: 15:52
 */

namespace App\Domain\Common;


use App\Domain\BaseInterface;
use App\Domain\DomainException\MedooException;
use App\Domain\DomainException\NotFoundException;
use App\Entities\Common\RouterEntity;

interface RouterInterface extends BaseInterface
{
  const TABLENAME = "routers";

  /**
   * @param int $id
   * @return RouterEntity
   * @throws NotFoundException
   * @throws MedooException
   */
  public function findActionOfId(int $id): RouterEntity;

}
