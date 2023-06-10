<?php

namespace Infrastructure;

use Exception;
use Predis\Autoloader;
use Predis\Client;

Autoloader::register();


class RateLimiter
{
    private Client $redis;
    private int $limit;
    private int $timePeriod;

    public function __construct($limit, $timePeriod)
    {
        // I'm initializing Redis client and the rate limiting parameters inside constructor.
        // This way, we're creating a reusable, configurable object that can be used across
        // multiple requests, with different rate limits/time periods for different types of operations.
        $this->redis = new Client([
            'scheme' => $_ENV['REDIS_SCHEME'],
            'host' => $_ENV['REDIS_HOST'],
            'port' => $_ENV['REDIS_PORT'],
        ]);
        $this->limit = $limit;
        $this->timePeriod = $timePeriod;
    }

    public function rateLimit(string $ip_address): void
    {
        try {
            // Leveraging Redis' atomic transactions to increment and expire the ip_address in one operation.
            // This prevents potential race conditions where the ip_address's count could be incremented but not
            // expired if the script execution was interrupted.
            $this->redis->multi();
            $this->redis->incr($ip_address);
            $this->redis->expire($ip_address, $this->timePeriod);
            $this->redis->exec();

            $count = $this->redis->get($ip_address);

            // We're using a simple comparison to check if the rate limit has been exceeded.
            // This is efficient and easy to understand.
            if ($count > $this->limit) {
                $error_response = [
                    'is_rate_exceeded' => true,
                    'message' => 'Rate limit exceeded. Try again later.'
                ];
                echo json_encode($error_response, JSON_THROW_ON_ERROR);
                exit();
            }
        } catch (Exception $e) {
            echo "Could not connect to Redis. Error: {$e->getMessage()}";
        }
    }
}


