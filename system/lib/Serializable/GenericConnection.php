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

namespace Opis\Colibri\Serializable;

use Opis\Database\DSN\Generic;
use Opis\Closure\SerializableClosure;

class GenericConnection extends Generic
{
    
    public function serialize()
    {
        SerializableClosure::enterContext();
        $object = serialize(array(
            'compiler' => SerializableClosure::from($this->compilerConstructor),
            'username' => $this->username,
            'password' => $this->password,
            'log' => $this->log,
            'options' => $this->options,
            'queries' => $this->queries,
            'properties' => $this->properties,
            'prefix' => $this->prefix,
            'database' => $this->database,
            'dsn' => $this->dsn(),
        ));
        SerializableClosure::exitContext();
        return $object;
    }
    
    public function unserialize($data)
    {
        $object = unserialize($data);
        $this->compilerConstructor = $object['compiler'] === null ? null : $object['compiler']->getClosure();
        unset($object['compiler']);
        foreach($object as $key => $value)
        {
            $this->{$key} = $value;
        }
    }
    
}
