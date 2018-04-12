<?php

declare(strict_types=1);

namespace AppBundle\System\Naming;

class CamelCaseConverter
{
    public function convertCamelCaseToUnderscores(string $input): string
    {
        return $this->convertCamelCaseToSymbolNotation($input, '_');
    }

    public function convertCamelCaseToDashes(string $input): string
    {
        return $this->convertCamelCaseToSymbolNotation($input, '-');
    }

    private function convertCamelCaseToSymbolNotation(string $input, string $chunkGlue): string
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $chunks = $matches[0];
        foreach ($chunks as &$match) {
            if ($match === mb_strtoupper($match)) {
                $match = mb_strtolower($match);
            } else {
                $match = lcfirst($match);
            }
        }

        return implode($chunkGlue, $chunks);
    }
}
