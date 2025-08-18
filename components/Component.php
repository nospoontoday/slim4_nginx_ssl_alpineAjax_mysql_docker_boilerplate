<?php

declare(strict_types=1);

namespace Components;

/**
 * Base component class
 * 
 * @ai-purpose Foundation for all UI components
 * @ai-usage Extend this class for new components
 */
abstract class Component
{
    protected array $props;
    
    public function __construct(array $props = [])
    {
        $this->props = $props;
    }
    
    abstract public function render(): string;
    
    protected function e(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Get prop value with fallback
     */
    protected function getProp(string $key, mixed $default = null): mixed
    {
        return $this->props[$key] ?? $default;
    }
    
    /**
     * Check if prop exists
     */
    protected function hasProp(string $key): bool
    {
        return isset($this->props[$key]);
    }
    
    /**
     * Build HTML attributes string
     */
    protected function buildAttributes(array $attributes): string
    {
        $parts = [];
        
        foreach ($attributes as $key => $value) {
            if ($value === null || $value === false) {
                continue;
            }
            
            if ($value === true) {
                $parts[] = $this->e($key);
            } else {
                $parts[] = sprintf('%s="%s"', $this->e($key), $this->e((string) $value));
            }
        }
        
        return implode(' ', $parts);
    }
    
    /**
     * Build CSS classes string
     */
    protected function buildClasses(array $classes): string
    {
        $validClasses = array_filter($classes, function($class) {
            return is_string($class) && !empty($class);
        });
        
        return implode(' ', $validClasses);
    }
}
