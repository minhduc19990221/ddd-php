<?php
namespace Backend\infrastructure;

require "vendor/autoload.php";

use Exception;
use Predis\Autoloader;
use Predis\Client;

Autoloader::register();


class RateLimiter
{
    private Client $redis;
    private $limit;
    private $timePeriod;

    public function __construct($limit, $timePeriod)
    {
        // I'm initializing Redis client and the rate limiting parameters inside constructor.
        // This way, we're creating a reusable, configurable object that can be used across
        // multiple requests, with different rate limits/time periods for different types of operations.
        $this->redis = new Client();
        $this->limit = $limit;
        $this->timePeriod = $timePeriod;
    }

    public function rateLimit($key)
    {
        try {
            // Leveraging Redis' atomic transactions to increment and expire the key in one operation.
            // This prevents potential race conditions where the key's count could be incremented but not
            // expired if the script execution was interrupted.
            $this->redis->multi();
            $this->redis->incr($key);
            $this->redis->expire($key, $this->timePeriod);
            $this->redis->exec();

            $count = $this->redis->get($key);

            // We're using a simple comparison to check if the rate limit has been exceeded.
            // This is efficient and easy to understand.
            if ($count > $this->limit) {
                echo "Rate limit exceeded. Try again later.";
            } else {
                echo "Request successful.";
            }
        } catch (Exception $e) {
            echo "Could not connect to Redis. Error: {$e->getMessage()}";
        }
    }
}

// Usage
$rateLimiter = new RateLimiter(10, 60);
$rateLimiter->rateLimit('user:123');
?>
