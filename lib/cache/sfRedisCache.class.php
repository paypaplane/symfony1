<?php

class sfRedisCache extends sfCache
{
	const TIME_OUT = 3;
	const DEFAULT_TTL = 7200; // 2 hours

	/**
	 * @var Redis $redis
	 */
	protected $redis = null;
	protected $ttl;

	/**
	 * Initializes this sfCache instance.
	 *
	 * Available options:
	 *
	 * * redis: A Redis object (optional)
	 *
	 * * host:       The default host (default to localhost)
	 * * port:       The port for the default server (default to 6379)
	 * * select:     Database number to connect to 
	 *
	 * * servers:    An array of additional servers (keys: host, port, select)
	 *
	 * * see sfCache for options available for all drivers
	 *
	 * @see sfCache
	 */
	public function initialize($options = array())
	{
		parent::initialize($options);

		if (!class_exists('Redis')) {
			throw new sfInitializationException('You must have PhpRedis extension installed and enabled to use sfRedisCache class.');
		}

		if ($this->getOption('redis')) {
			$this->redis = $this->getOption('redis');
		} else {
			$this->redis = new Redis();
			$server = sfConfig::get("app_redis_server", []); // Pull config from app.yml
			if ($server) {
				$port = isset($server['port']) ? $server['port'] : 6379;
				if (!$this->redis->connect($server['host'], $port, self::TIME_OUT))
				{
					throw new sfInitializationException(sprintf('Unable to connect to the redis server (%s:%s).', $server['host'], $port));
				}
			}

			if ($this->getOption('auth')) {
				if (!$this->redis->auth($this->getOption('auth'))) {
					throw new sfInitializationException('Unable to authenticate to the redis server');
				}
			}

			if ($this->getOption('select')) {
				if (!$this->redis->select($this->getOption('select'))) {
					throw new sfInitializationException('Unable to select db');
				}
			}
		}
	}

	/**
	 * @see sfCache
	 */
	public function getBackend()
	{
		return $this->redis;
	}

	/**
	 * @see sfCache
	 */
	public function get($key, $default = null)
	{
		$value = $this->redis->get($this->getOption('prefix').$key);

		return false === $value ? $default : $value;
	}

	/**
	 * @see sfCache
	 */
	public function has($key)
	{
		return !(false === $this->redis->get($this->getOption('prefix').$key));
	}

	/**
	 * @see sfCache
	 */
	public function set($key, $data, $lifetime = null)
	{
		$lifetime = $this->getOption('lifetime') ? $this->getOption('lifetime') : self::DEFAULT_TTL;

		// save metadata
		$this->setMetadata($key, $lifetime);

		// save key for removePattern()
		if ($this->getOption('storeCacheInfo', false)) {
			$this->setCacheInfo($key);
		}

		return  $this->redis->setEx($this->getOption('prefix').$key, $lifetime, $data);
	}

	/**
	 * @see sfCache
	 */
	public function remove($key)
	{
		// delete metadata
		$this->redis->delete($this->getOption('prefix').'_metadata'.self::SEPARATOR.$key);
		if ($this->getOption('storeCacheInfo', false))
		{
			$this->setCacheInfo($key, true);
		}
		return $this->redis->delete($this->getOption('prefix').$key);
	}

	/**
	 * This will empty the entire selected db, so its imporant that redis is configured to use its owns db
	 * @see sfCache
	 */
	public function clean($mode = sfCache::ALL)
	{
		if (sfCache::ALL === $mode) {
			return $this->redis->flushDb();
		}
	}

	/**
	 * @see sfCache
	 */
	public function getLastModified($key)
	{
		if (false === ($retval = $this->getMetadata($key)))
		{
			return 0;
		}

		return $retval['lastModified'];
	}

	/**
	 * @see sfCache
	 */
	public function getTimeout($key)
	{
		if (false === ($retval = $this->getMetadata($key)))
		{
			return 0;
		}

		return $retval['timeout'];
	}

	/**
	 * @see sfCache
	 */
	public function removePattern($pattern)
	{
		throw new \Exception("'removePattern' is not implemented");
	}

	/**
	 * @see sfCache
	 */
	public function getMany($keys)
	{
		throw new \Exception("'getMany' is not implemented");
	}

	/**
	 * Gets metadata about a key in the cache.
	 *
	 * @param string $key A cache key
	 *
	 * @return array An array of metadata information
	 */
	protected function getMetadata($key)
	{
		return $this->redis->get($this->getOption('prefix').'_metadata'.self::SEPARATOR.$key);
	}

	/**
	 * Stores metadata about a key in the cache.
	 *
	 * @param string $key      A cache key
	 * @param string $lifetime The lifetime
	 */
	protected function setMetadata($key, $lifetime)
	{
		$this->redis->setEx($this->getOption('prefix').'_metadata'.self::SEPARATOR.$key, self::DEFAULT_TTL, ['lastModified' => time(), 'timeout' => time() + $lifetime]);
	}

	/**
	 * Updates the cache information for the given cache key.
	 *
	 * @param string $key The cache key
	 * @param boolean $delete Delete key or not
	 */
	protected function setCacheInfo($key, $delete = false)
	{
		$keys = $this->redis->get($this->getOption('prefix').'_metadata');
		if (!is_array($keys))
		{
			$keys = array();
		}

		if ($delete) {
			if (($k = array_search($this->getOption('prefix').$key, $keys)) !== false)
			{
				unset($keys[$k]);
			}
		} else {
			if (!in_array($this->getOption('prefix').$key, $keys))
			{
				$keys[] = $this->getOption('prefix').$key;
			}
		}

		$this->redis->set($this->getOption('prefix').'_metadata', $keys, 0);
	}

	/**
	 * Gets cache information.
	 */
	protected function getCacheInfo()
	{
		$keys = $this->redis->get($this->getOption('prefix').'_metadata');
		if (!is_array($keys))
		{
			return [];
		}

		return $keys;
	}
}
