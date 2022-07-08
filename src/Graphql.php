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
        $selectors = implode(' ', $selectors);          
        $args = $this->generateArgsString($args);

        $query = <<<GQL
            {
                $name(input: $args) {
                    $selectors
                }
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
        $selectors = implode(' ', $selectors);          
        $args = $this->generateArgsString($args);

        $mutation = <<<GQL
            mutation {
                $name(input: $args) {
                    $selectors
                }
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
        $str = '{';

        foreach ($args as $key => $value) {
            $str .= $key . ': "' . $value . '"';
        }

        $str .= '}';

        return $str;
    }
}
