<?php

use phpDocumentor\Reflection\DocBlockFactory;

/**
 * 常量
 * Created on 2024/4/22 11:23
 * @author ClearSwitch
 */
class AbstractConstant
{
    /**
     * 获得存储的缓存
     * @return false|mixed
     * @author SwitchSwitch
     */
    protected static function getConstant()
    {
        $reflectionClass = new \ReflectionClass(static::class);;
        return $reflectionClass->getReflectionConstants();
    }

    /**
     * 调用不存在的静态方法调用
     * @param string $name 不存在静态方法的名字
     * @param array $arguments
     * @return false|mixed|void|null
     * @author SwitchSwitch
     */
    public static function __callStatic(string $name, array $arguments)
    {
        if (count($arguments) == 0) {
            throw new \Exception("缺少常量的值");
        }
        $subString = mb_substr($name, 0, 3);
        if ($subString == 'get') {
            $constName = mb_substr($name, 3);
            return self::getAnnotations($constName, current($arguments));
        }
    }

    /**
     * 或的注解的值
     * @param $constName
     * @param $constValue
     * @return false|mixed|null
     * @author SwitchSwitch
     */
    public static function getAnnotations($constName, $constValue)
    {
        $annotations = self::getConstantAnnotations($constName, $constValue);
        return $annotations;
    }

    /**
     * 解析注解
     * @param $class
     * @param $constName
     * @param $constValue
     * @return false|mixed|void
     * @throws \ReflectionException
     * @author SwitchSwitch
     */
    protected static function getConstantAnnotations($constName, $constValue)
    {
        $constants = self::getConstant();
        $factory = DocBlockFactory::createInstance();
        foreach ($constants as $constant) {
            if ($constant->getValue() == $constValue) {
                $docComment = $constant->getDocComment();
                $docBlock = $factory->create($docComment);
                if ($docBlock->hasTag($constName)) {
                    $annotations = $docBlock->getTagsByName($constName);
                    $matches = [];
                    foreach ($annotations as $annotation) {
                        preg_match('/\("([^"]+)"\)/', $annotation->getDescription(), $matches);
                        return end($matches);
                    }
                } else {
                    throw new \Exception("调用了不存在的常量注解");
                }
            }
        }
        return false;
    }

    /**
     * 获得所有的常量
     * @return array
     * @throws \ReflectionException
     * @author SwitchSwitch
     */
    public static function list()
    {
        $constants = self::getConstant();
        $result = [];
        foreach ($constants as $value) {
            $result[] = $value->getValue();
        }
        return $result;
    }


    /**
     * 获得常量
     * @return array
     * @throws \ReflectionException
     * @author SwitchSwitch
     */
    public static function messages()
    {
        $constants = self::getConstant();
        $factory = DocBlockFactory::createInstance();
        foreach ($constants as $constant) {
            $docComment = $constant->getDocComment();
            $docBlock = $factory->create($docComment);
            $matches = [];
            $resule = [];
            if ($docBlock->hasTag('Message')) {
                $annotations = $docBlock->getTagsByName('Message');
                $matches = [];
                foreach ($annotations as $annotation) {
                    preg_match('/\("([^"]+)"\)/', $annotation->getDescription(), $matches);
                    $message = end($matches);
                }
                $resule[] = [
                    'code' => $constant->getValue(),
                    'message' => $message ?? ''
                ];
            }
        }
        return $resule;
    }
}