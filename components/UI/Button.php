<?php

declare(strict_types=1);

namespace Components\UI;

use Components\Component;

/**
 * @ai-component Button
 * @ai-props {
 *   text: string,
 *   type?: 'primary'|'secondary'|'ghost',
 *   htmx?: array
 * }
 */
class Button extends Component
{
    public function render(): string
    {
        $type = $this->getProp('type', 'primary');
        $text = $this->e($this->getProp('text'));
        
        $classes = ['btn', "btn-{$type}"];
        $attrs = ['class' => $this->buildClasses($classes)];
        
        // Add HTMX attributes if provided
        if ($this->hasProp('htmx')) {
            $htmxProps = $this->getProp('htmx', []);
            foreach ($htmxProps as $key => $value) {
                $attrs["hx-{$key}"] = $value;
            }
        }
        
        // Add other HTML attributes
        if ($this->hasProp('id')) {
            $attrs['id'] = $this->getProp('id');
        }
        
        if ($this->hasProp('disabled')) {
            $attrs['disabled'] = $this->getProp('disabled');
        }
        
        $attributes = $this->buildAttributes($attrs);
        
        return sprintf(
            '<button %s>%s</button>',
            $attributes,
            $text
        );
    }
}
