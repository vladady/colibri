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

namespace Opis\Colibri\Collectors;

use Opis\Colibri\Serializable\ConnectionList;
use Opis\Colibri\Serializable\DSNConnection;
use Opis\Colibri\ConnectionCollectorInterface;

class ConnectionCollector extends AbstractCollector implements ConnectionCollectorInterface
{
   
    public function __construct()
    {
        parent::__construct(new ConnectionList());
    }
    
    public function create($name, $default = false)
    {
        $connection = new DSNConnection();
        $this->dataObject->set($name, $connection, $default);
        return $connection;
    }
}
