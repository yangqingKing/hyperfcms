<?php

declare (strict_types=1);
namespace App\Models;

/**
 * @property int $id 
 * @property string $qid 
 * @property string $server_name 
 * @property string $server_addr 
 * @property string $remote_addr 
 * @property string $forwarded_for 
 * @property string $real_ip 
 * @property string $user_agent 
 * @property string $platform 
 * @property string $device 
 * @property string $browser 
 * @property string $url 
 * @property string $uri 
 * @property string $arguments 
 * @property string $method 
 * @property int $execution_time 
 * @property int $request_body_size 
 * @property int $response_body_size 
 * @property string $channel 
 * @property string $level_name 
 * @property string $message 
 * @property string $user_id 
 * @property string $referer 
 * @property int $unix_time 
 * @property string $time_day 
 * @property string $time_hour 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class Log extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = '_logs';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'qid', 'server_name', 'server_addr', 'remote_addr', 'forwarded_for', 'real_ip', 'user_agent', 'platform', 'device', 'browser', 'url', 'uri', 'arguments', 'method', 'execution_time', 'request_body_size', 'response_body_size', 'channel', 'level_name', 'message', 'user_id', 'referer', 'unix_time', 'time_day', 'time_hour', 'created_at', 'updated_at'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'execution_time' => 'integer', 'request_body_size' => 'integer', 'response_body_size' => 'integer', 'unix_time' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}