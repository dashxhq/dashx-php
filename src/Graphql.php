<?php

namespace Dashx\Php;

trait Graphql {
    /**
     * Generate the graphql query.
     * 
     * @param string $name
     * @param array $args
     * @param array $selectors
     * 
     * @return string
     */
    private function query(string $name, array $args, array $selectors) {
        $selectors = $this->generateSelectorsString($selectors);

        $args = $this->generateArgsString($args);

        $query = <<<GQL
            {
                $name(input: $args) $selectors
            }
        GQL;

        return $query;
    }

    /**
     * Generate the graphql mutation.
     * 
     * @param string $name
     * @param array $args
     * @param array $selectors
     * 
     * @return string
     */
    private function mutation(string $name, array $args, array $selectors) {
        $selectors = $this->generateSelectorsString($selectors);

        $args = $this->generateArgsString($args);

        $mutation = <<<GQL
            mutation {
                $name(input: $args) $selectors
            }
        GQL;

        return $mutation;
    }

    /**
     * Generate the arguments string.
     *
     * @param array $args
     * 
     * @return string
     */
    private function generateArgsString(array $args) {
        if(count(array_filter(array_keys($args), 'is_string')) > 0) {

            $str = '{ ';

            foreach ($args as $key => $value) {
                if(is_array($value)) {
                    $value = $this->generateArgsString($value);
                    $str .= $key . ': ' . $value . ' ';
    
                    continue;
                }
    
                $str .= $key . ': "' . $value . '" ';
            }
    
            $str .= ' }';
    
            return $str;
        }else {
            $str = '[ ';

            foreach ($args as $key => $value) {
                if(is_array($value)) {
                    $value = $this->generateArgsString($value);
                    $str .= $value . ', ';
    
                    continue;
                }
    
                $str .= '"' . $value . '", ';
            }
    
            $str .= ' ]';
    
            return $str;
        }
    }

    /**
     * Generate the selectors string.
     *
     * @param array $selectors
     *
     * @return string
     */
    private function generateSelectorsString(array $selectors) {
        if(!count($selectors)) {
            return '';
        }

        foreach($selectors as $index => $value) {
            if(is_array($value)) {
                $selectors[$index] = $index  . $this->generateSelectorsString($value);
            }
        }

        $selectors = implode(' ', $selectors);

        $string = <<<GQL
        {
            $selectors
        }
        GQL;

        return $string;
    }
}
