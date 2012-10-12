<?php
namespace Respect;

use \SplObjectStorage;
use \ReflectionClass;
use \ReflectionMethod;
use \ReflectionProperty;

/**
 * Generates docs that rocks your socks off.
 *
 * Why it rock your socks off:
 *
 *   1. Tested code rocks! So it uses your PHPUnit tests as examples.
 *   2. Doc comments, like this, are really optional.
 *   3. This very documentation was generated by the project itself from the code itself.
 *
 * @author  Alexandre Gaigalas <alexandre@gaigalas.net>
 * @author  Augusto Pascutti <augusto@phpsp.org.br>
 */
class Doc
{
    public $path;
    protected $reflections=array();
    protected $sections=array();

    /** Receives the namespace or class to be documented */
    public function __construct($classOrNamespace)
    {
        $this->path = $classOrNamespace;
    }

    /** Returns the documentation in markdown */
    public function __toString()
    {
        return $this->getMarkdown($this->getContents($this->path));

    }

    protected function getContents($path)
    {
        if (!class_exists($path))
            return $this->getNamespaceContents($path);
        else
            return $this->getClassContents($path);
    }

    protected function getNamespaceContents($path)
    {
        $sections = array();
        $declaredClasses = get_declared_classes();
        natsort($declaredClasses);
        foreach ($declaredClasses as $class)
            if (0 === stripos($class, $path) && false === strripos($class, 'Test'))
                $sections += $this->getClassContents($class);

        return $sections;
    }

    //TODO: extract a lot of methods
    protected function getClassContents($path)
    {
        if (!class_exists($path))
            return array();

        $sections = array();
        $classes  = array($path);
        foreach ($classes as $class) {
            $reflection       = new ReflectionClass($class);
            $class            = $reflection->getName();
            $sections[$class] = $reflection->getDocComment();

            $reflectors = $this->getSections($reflection);
            foreach ($reflectors as $sub) {

                $tests = $reflectors[$sub];

                if ($sub->isStatic())
                    $subName = 'static ';
                else
                    $subName = '';

                $subName .= $sub->getName();
                $name     = $class.'::'.$subName;

                if ($sub instanceof ReflectionMethod)
                    if ($sub->getNumberOfRequiredParameters() <= 0)
                        $name .= '()';
                    else {
                        $params = $sub->getParameters();
                        $tmp    = array();
                        foreach ($params as $param) {
                            if ($param->isArray())
                                $tmp[] = 'array ';
                            if ($param->isOptional())
                                $tmp[] = '$'.$param->getName().'=null';
                            elseif ($param->isDefaultValueAvailable())
                                $tmp[] = '$'.$param->getName().'='.$param->getDefaultValue();
                            else
                                $tmp[] = '$'.$param->getName();
                        }
                        $name .= '('.implode(', ', $tmp).')';
                    }

                $sections[$name] = $sub->getDocComment();
                // Fetch method content for examples
                foreach ($tests as $n => $test) {
                    $testCaseContents = file($test->getFilename());
                    $testSectionName  = "Example ".($n+1).":";
                    $testCaseLines    = array_slice($testCaseContents, 1+$test->getStartLine(), -2+$test->getEndLine()-$test->getStartLine());
                    $testCaseLines    = array_map(
                        function($line) {
                            if ($line{0} == "\t")
                                return substr($line, 1);
                            if ($line{0} == ' ')
                                return substr($line, 4);
                            else
                                return '    ' . $line;
                        },
                        $testCaseLines
                    );
                    $sections[$name] .= PHP_EOL.PHP_EOL.$testSectionName.PHP_EOL.PHP_EOL.implode($testCaseLines);
                }

            }

        }
        return $sections;
    }

    protected function getSections(ReflectionClass $reflection)
    {
        $testCaseClass = $reflection->getName().'Test';

        if (class_exists($testCaseClass)) {
            $testCaseReflection = new ReflectionClass($testCaseClass);
            $testCaseMethods = $testCaseReflection->getMethods();
        } else {
            $testCaseMethods= array();
        }
        $sections = new SplObjectStorage;
        $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC ^ ReflectionMethod::IS_STATIC);
        $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC ^ ReflectionProperty::IS_STATIC);
        $staticMethods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC & ReflectionMethod::IS_STATIC);
        $staticProperties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC & ReflectionProperty::IS_STATIC);

        $nameSort = function($a, $b) {
            return strnatcasecmp($a->getName(), $b->getName());
        };

        usort($methods, $nameSort);
        usort($properties, $nameSort);
        usort($staticMethods, $nameSort);
        usort($staticProperties, $nameSort);
        foreach (array_merge($staticProperties, $properties, $staticMethods, $methods) as $method)
            $sections[$method] = array_values(array_filter($testCaseMethods, function($test) use($method) {
                                    return 0 === stripos($test->getName(), 'test_as_example_for_'.$method->getName().'_method');
                                 }));
        return $sections;
    }

    protected function getMarkdown(array $sections)
    {
        $string = array();
        foreach ($sections as $name=>$content) {

            if (preg_match_all('/[\:]{1,2}(.*)/', $name, $matches))
                $name = $matches[1][0];
            else
                $matches = 1;

            $content  = trim(preg_replace('#^(\s*[*]|[/][*]{2}|[\][*])[/*]*(.*?)[ /*]*$#m', '$2', $content));
            $content  = preg_replace("#\\n\\n[ ]*@#", "\n\nMore Info:\n\n@", $content);
            $content  = preg_replace_callback('#^[ ]*[@](\w+)[ ]+(.*)#mi',
                function($matches){
                    $matches[1] = ucfirst($matches[1]);
                    return "   - **{$matches[1]}:** {$matches[2]} ";
                },
            $content);
            $char   = count($matches) == 1 ? '=' : '-';
            $string[] = trim($name . "\n" .  str_repeat($char, strlen($name)) . "\n\n" . $content);
        }
        return implode("\n\n", $string);
    }
}