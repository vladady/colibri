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

use Closure;
use Opis\Events\EventTarget as OpisEventTarget;
use Opis\Colibri\EventCollectorInterface;

class EventCollector extends AbstractCollector implements EventCollectorInterface
{
    
    public function __construct()
    {
        parent::__construct(new OpisEventTarget());
    }
    
    public function handle($event, Closure $callback, $priority = 0)
    {
        return $this->dataObject->handle($event, $callback, $priority);
    }
    
}
