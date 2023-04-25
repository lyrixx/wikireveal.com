<?php

namespace App\Text;

class SolutionCheckerBuilder
{
    public function __construct(
        private readonly VariationComputer $variationComputer,
        private readonly Hasher $hasher,
        private readonly Encrypter $encrypter,
    ) {
    }

    public function buildSolutionChecker(string $language, array $tokens, array $winTokens)
    {
        $variationsMap = $this->variationComputer->computeVariationsMap($language, $tokens);

        $hashToEncrypted = [];
        $variationsMapSecured = [];

        foreach ($variationsMap as $normalized => $variations) {
            $normalizedHash = $this->hasher->hash($normalized);
            $variationsSecured = [];

            foreach ($variations as $variation) {
                $hash = $this->hasher->hash($variation);
                $hashToEncrypted["$normalizedHash:$hash"] = $this->encrypter->encrypt($variation, $normalized);

                $variationsSecured[] = $this->hasher->hash($variation);
            }

            $variationsMapSecured[$normalizedHash] = $variationsSecured;
        }

        $winTokenHashes = array_map($this->hasher->hash(...), $winTokens);
        $winTokenHashes = array_values($winTokenHashes);

        return [
            'variationsMap' => $variationsMapSecured,
            'hashToEncrypted' => $hashToEncrypted,
            'winHashes' => $winTokenHashes,
        ];
    }
}
