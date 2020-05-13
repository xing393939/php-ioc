<?php

namespace MyFramework;

class Container implements \ArrayAccess
{
    /**
     *  容器绑定，用来装提供的实例或者 提供实例的回调函数
     * @var array
     */
    protected $building = [];
    protected $instances = [];

    /**
     * 注册一个绑定到容器
     * @param $abstract
     * @param null $concrete
     * @param bool $shared
     */
    public function bind($abstract, $concrete = null, $shared = false)
    {
        if (is_null($concrete)) {
            $concrete = $abstract;
        }

        if (!$concrete instanceOf \Closure) {
            $concrete = $this->getClosure($abstract, $concrete);
        }

        $this->building[$abstract] = compact("concrete", "shared");
    }

    //注册一个共享的绑定 单例
    public function singleton($abstract, $concrete, $shared = true)
    {
        $this->bind($abstract, $concrete, $shared);
    }

    /**
     * 默认生成实例的回调闭包
     *
     * @param $abstract
     * @param $concrete
     * @return Closure
     */
    public function getClosure($abstract, $concrete)
    {
        return function ($c) use ($abstract, $concrete) {
            $method = ($abstract == $concrete) ? 'build' : 'make';

            return $c->$method($concrete);
        };
    }

    /**
     * 生成实例
     * @param $abstract
     * @return mixed|object
     * @throws Exception
     */
    public function make($abstract)
    {
        $concrete = $this->getConcrete($abstract);

        if ($this->isBuildable($concrete, $abstract)) {
            $object = $this->build($concrete);
        } elseif (is_object($concrete)) {
            $object = $concrete;
        } else {
            $object = $this->make($concrete);
        }

        if (empty($this->instances[$abstract])) {
            $this->instances[$abstract] = $object;
        }

        return $object;
    }

    /**
     * 获取绑定的回调函数
     * @param $abstract
     * @return mixed
     */
    public function getConcrete($abstract)
    {
        if (!isset($this->building[$abstract])) {
            return $abstract;
        }
        if ($this->building[$abstract]["shared"]
            && !empty($this->instances[$abstract])) {
            return $this->instances[$abstract];
        } else {
            return $this->building[$abstract]['concrete'];
        }
    }

    /**
     * 判断 是否 可以创建服务实体
     * @param $concrete
     * @param $abstract
     * @return bool
     */
    public function isBuildable($concrete, $abstract)
    {
        return $concrete === $abstract || $concrete instanceof \Closure;
    }

    /**
     * 根据实例具体名称实例具体对象
     * @param $concrete
     * @return mixed|object
     * @throws \ReflectionException
     */
    public function build($concrete)
    {
        if ($concrete instanceof \Closure) {
            return $concrete($this);
        }

        //创建反射对象
        $reflector = new \ReflectionClass($concrete);

        if (!$reflector->isInstantiable()) {
            //抛出异常
            throw new \Exception('无法实例化');
        }

        $constructor = $reflector->getConstructor();
        if (is_null($constructor)) {
            return new $concrete;
        }

        $dependencies = $constructor->getParameters();
        $instance = $this->getDependencies($dependencies);

        return $reflector->newInstanceArgs($instance);

    }

    //通过反射解决参数依赖
    public function getDependencies(array $dependencies)
    {
        $results = [];
        foreach ($dependencies as $dependency) {
            $results[] = is_null($dependency->getClass())
                ? $this->resolvedNonClass($dependency)
                : $this->resolvedClass($dependency);
        }

        return $results;
    }

    //解决一个没有类型提示依赖
    public function resolvedNonClass(\ReflectionParameter $parameter)
    {
        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }
        throw new \Exception('出错');

    }

    //通过容器解决依赖
    public function resolvedClass(\ReflectionParameter $parameter)
    {
        return $this->make($parameter->getClass()->name);

    }

    public function extend($abstract, $func)
    {

    }
    public function instance($abstract, $instance)
    {
        $this->instances[$abstract] = $instance;
    }
    public function bound($abstract)
    {

    }
    /**
     * Determine if a given offset exists.
     *
     * @param string $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return $this->bound($key);
    }

    /**
     * Get the value at a given offset.
     *
     * @param string $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->make($key);
    }

    /**
     * Set the value at a given offset.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function offsetSet($key, $value)
    {
        $this->bind($key, $value instanceof Closure ? $value : function () use ($value) {
            return $value;
        });
    }

    /**
     * Unset the value at a given offset.
     *
     * @param string $key
     * @return void
     */
    public function offsetUnset($key)
    {
        unset($this->bindings[$key], $this->instances[$key], $this->resolved[$key]);
    }
}