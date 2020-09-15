<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 *​
 * context.php
 *
 * 协程向下文
 *
 * User：YM
 * Date：2020/4/18
 * Time：9:02 PM
 */

return [
    // 需要复制的协程上下文
    'copy' => [
        Psr\Http\Message\ServerRequestInterface::class,
        Hyperf\Contract\SessionInterface::class
    ]
];
