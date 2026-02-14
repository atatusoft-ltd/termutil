<?php

if (! function_exists('get_max_terminal_size') ) {
    /**
     * Get the maximum terminal size (width and height).
     *
     * @return array An associative array with 'width' and 'height' keys.
     */
    function get_max_terminal_size(): array
    {
        $size = ['width' => 80, 'height' => 24]; // Default size

        if (strncasecmp(PHP_OS, 'WIN', 3) === 0) {
            // Windows
            $output = [];
            exec('mode con', $output);
            foreach ($output as $line) {
                if (preg_match('/^\s*Columns:\s*(\d+)/i', $line, $matches)) {
                    $size['width'] = (int)$matches[1];
                } elseif (preg_match('/^\s*Lines:\s*(\d+)/i', $line, $matches)) {
                    $size['height'] = (int)$matches[1];
                }
            }
        } else {
            // Unix-like systems
            if (function_exists('posix_isatty') && posix_isatty(STDOUT)) {
                $output = [];
                exec('stty size', $output);
                if (isset($output[0]) && preg_match('/^(\d+)\s+(\d+)$/', $output[0], $matches)) {
                    $size['height'] = (int)$matches[1];
                    $size['width'] = (int)$matches[2];
                }
            } else {
                // Fallback to environment variables
                $size['width'] = getenv('COLUMNS') ? (int)getenv('COLUMNS') : $size['width'];
                $size['height'] = getenv('LINES') ? (int)getenv('LINES') : $size['height'];
            }
        }

        return $size;
    }
}