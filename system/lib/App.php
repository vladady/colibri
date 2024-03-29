<?php
/* ===========================================================================
 * Opis Project
 * http://opis.io
 * ===========================================================================
 * Copyright 2013 Marius Sarca
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ============================================================================ */

namespace Opis\Colibri;

use Opis\HttpRouting\Path;
use Opis\Http\Request as HttpRequest;
use Opis\Http\Error\NotFound as NotFoundError;
use Opis\Cache\Cache as OpisCache;
use Opis\Cache\StorageInterface as OpisCacheStorage;
use Opis\Config\Config as OpisConfig;
use Opis\Config\StorageInterface as OpisConfigStorage;
use Opis\Session\SessionStorage as OpisSessionStorage;
use Opis\Session\Session as OpisSession;
use Opis\Database\Connection as OpisConnection;
use Opis\Database\Database as OpisDatabase;
use Opis\Database\Schema as OpisSchema;
use Opis\View\ViewRouter;
use Opis\Colibri\Collectors\EventTarget as CollectorEventTarget;

class App
{
    
    protected static $cache = array();
    
    protected static $instances = array();
    
    protected function __construct()
    {
        
    }
    
    
    public static function loadFromSystemCache($entry)
    {
        if(!isset(static::$cache[$entry]))
        {
            static::$cache[$entry] = static::systemCache()->load($entry, function() use ($entry){
                static::includeModules();
                return static::collector()->collect($entry)->data();
            });
        }
        
        return static::$cache[$entry];
    }
    
    public static function includeModules()
    {
        if(!isset(static::$instances['includeModules']))
        {
            foreach(App::systemConfig()->read('modules.enabled') as $module => $status)
            {
                if($status && null !== $collector = App::systemConfig()->read("modules.list.$module.collector"))
                {
                    require_once $collector;
                }
            }
            
            static::$instances['includeModules'] = 1;
        }
    }
    
    public static function init()
    {
        ClassLoader::init();
        
        $info = App::systemConfig()->read('modules.list');
        
        foreach(App::systemConfig()->read('modules.enabled') as $module => $status)
        {
            if($status)
            {
                ClassLoader::registerNamespace($info[$module]['namespace'], $info[$module]['source']);
                
                if($info[$module]['include'] !== null)
                {
                    include_once($info[$module]['include']);
                }
            }
        }
        
        Emit('system.init');
    }
    
    public static function version()
    {
        return '0.1.0';
    }
    
    public static function run(HttpRequest $request = null)
    {
        
        $request = static::systemRequest($request);
        
        $path = new Path($request->path(),
                         $request->host(),
                         $request->method(),
                         $request->isSecure(),
                         $request);
        
        return static::router()->route($path);
    }
    
    public static function router()
    {
        if(!isset(static::$instances['router']))
        {   
            static::$instances['router'] = new Router();
        }
        
        return static::$instances['router'];
    }
    
    public static function collector()
    {
        if(!isset(static::$instances['collector']))
        {
            static::$instances['collector'] = new CollectorEventTarget();
        }
        
        return static::$instances['collector'];
    }
    
    public static function systemCache(OpisCacheStorage $storage = null)
    {
        
        if(!isset(static::$instances['systemCache']))
        {
            if($storage === null)
            {
                $storage = new \Opis\Cache\Storage\Memory();
            }
            
            static::$instances['systemCache'] = new OpisCache($storage);
        }
        
        return static::$instances['systemCache'];
    }
    
    public static function systemSession(OpisSessionStorage $storage = null)
    {
        if(!isset(static::$instances['systemSession']))
        {
            if($storage === null)
            {
                $storage = new \Opis\Session\Storage\Native();
            }
            
            static::$instances['systemSession'] = new OpisSession($storage);
        }
        
        return static::$instances['systemSession'];
    }
    
    public static function systemConfig(OpisConfigStorage $storage = null)
    {
        if(!isset(static::$instances['systemConfig']))
        {
            if($storage === null)
            {
                $storage = new \Opis\Config\Storage\Memory();
            }
            
            static::$instances['systemConfig'] = new OpisConfig($storage);
        }
        
        return static::$instances['systemConfig'];
    }
    
    public static function systemRequest(HttpRequest $request = null)
    {
        if(!isset(static::$instances['systemRequest']))
        {
            if($request === null)
            {
                $request = HttpRequest::fromGlobals();
            }
            
            static::$instances['systemRequest'] = $request;
        }
        
        return static::$instances['systemRequest'];
    }
    
    public static function systemConnection(OpisConnection $connection = null)
    {
        if(!isset(static::$instances['systemConnection']))
        {
            if($connection === null)
            {
                $connection = static::connections()->get();
            }
            
            static::$instances['systemConnection'] = $connection;
        }
        
        return static::$instances['systemConnection'];
    }
    
    public static function systemDatabase()
    {
        if(!isset(static::$instances['systemDatabase']))
        {   
            static::$instances['systemDatabase'] = new OpisDatabase(static::systemConnection());
        }
        
        return static::$instances['systemDatabase'];
    }
    
    public static function systemSchema()
    {
        if(!isset(static::$instances['systemSchema']))
        {   
            static::$instances['systemSchema'] = new OpisSchema(static::systemConnection());
        }
        
        return static::$instances['systemSchema'];
    }
    
    public static function view()
    {
        if(!isset(static::$instances['view']))
        {
            static::$instances['view'] = new ViewRouter(static::viewCollection(), static::viewEngineResolvers());
        }
        
        return static::$instances['view'];
    }
    
    public static function httpRoutes()
    {
        return static::loadFromSystemCache('routes');
    }
    
    public static function httpDispatchers()
    {
        return static::loadFromSystemCache('dispatchers');
    }
    
    public static function httpRouteAliases()
    {
        return static::loadFromSystemCache('aliases');
    }
    
    public static function contracts()
    {
        return static::loadFromSystemCache('contracts');
    }
    
    public static function cache()
    {
        return static::loadFromSystemCache('cacheStorages');
    }
    
    public static function session()
    {
        return static::loadFromSystemCache('sessionStorages');
    }
    
    public static function events()
    {
        return static::loadFromSystemCache('events');
    }
    
    public static function configs()
    {
        return static::loadFromSystemCache('configStorages');
    }
    
    public static function viewCollection()
    {
        return static::loadFromSystemCache('views');
    }
    
    public static function viewEngineResolvers()
    {
        return static::loadFromSystemCache('viewEngines');
    }
    
    public static function connections()
    {
        return static::loadFromSystemCache('connections');
    }
    
}
