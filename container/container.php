<?php
class a{
    public function __construct()
    {
        echo 'a begin';
    }

    public function say(){
        echo 'a say';
    }
}

class b {
    public $a;
    public function __construct(a $a)
    {
        $this->a = $a;
    }
}

class c {
    public $b;
    public function __construct(b $b)
    {
        $this->b = $b;
    }

    public function test(){
        echo 'AAA  test';
    }
}

class container{

     public function get($class){
        $dependencies =[];
        $reflection = new ReflectionClass($class);
        $constructor = $reflection->getConstructor();
        if ($constructor !== null) {
            foreach ($constructor->getParameters() as $param) {
                if (version_compare(PHP_VERSION, '5.6.0', '>=') && $param->isVariadic()) {
                    break;
                } elseif ($param->isDefaultValueAvailable()) {
                    $dependencies[] = $param->getDefaultValue();
                } else {
                    $c = $param->getClass();
                    $dependencies[] = $c->getName();
                }
            }
        }
        $objects = $this->real($dependencies);

        $obj = $reflection->newInstanceArgs($objects);
        return $obj;
    }

    public function real($dependencies){
        $return = [];
        foreach ($dependencies as $index => $dependency) {
            $return[] = $this->get($dependency);
        }
        return $return;
    }

}

$container = new container();
$c = $container->get('c');
$c->b->a->say();










