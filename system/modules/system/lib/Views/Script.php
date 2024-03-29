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

namespace Colibri\Module\System\Views;

use Opis\Colibri\View;

class Script extends View
{
    
    public function __construct()
    {
        parent::__construct('script', array(
            'content' => null,
            'attributes' => new Attributes(),
        ));
    }
    
    public function content($content)
    {
        return $this->set('content', $content);
    }
    
    public function src($value)
    {
        return $this->attribute('src', $value);
    }
    
    public function attribute($name, $value = null)
    {
        $this->arguments['attributes']->add($name, $value);
        return $this;
    }
    
    public function attributes(array $attributes)
    {
        foreach($attributes as $name => $value)
        {
            $this->attribute($name, $value);
        }
        return $this;
    }
    
}
