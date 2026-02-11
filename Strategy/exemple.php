<?php

// ============================================
// INTERFACE STRATEGY : CompressionStrategy
// ============================================

/**
 * Interface définissant le contrat pour toutes les stratégies de compression
 */
interface CompressionStrategy
{
    public function compress(string $data): array;
}

// ============================================
// STRATÉGIES CONCRÈTES
// ============================================

/**
 * Stratégie de compression ZIP - Bon compromis vitesse/taux
 */
class ZipCompression implements CompressionStrategy
{
    public function compress(string $data): array
    {
        $originalSize = strlen($data);
        $compressed = gzcompress($data, 6);
        $compressedSize = strlen($compressed);
        $ratio = round((1 - $compressedSize / $originalSize) * 100, 2);
        
        echo "📦 ZIP : {$originalSize} octets → {$compressedSize} octets ({$ratio}%)\n";
        
        return ['data' => $compressed, 'size' => $compressedSize, 'ratio' => $ratio];
    }
}

/**
 * Stratégie de compression GZIP - Maximum de compression
 */
class GzipCompression implements CompressionStrategy
{
    public function compress(string $data): array
    {
        $originalSize = strlen($data);
        $compressed = gzencode($data, 9);
        $compressedSize = strlen($compressed);
        $ratio = round((1 - $compressedSize / $originalSize) * 100, 2);
        
        echo "🌐 GZIP : {$originalSize} octets → {$compressedSize} octets ({$ratio}%)\n";
        
        return ['data' => $compressed, 'size' => $compressedSize, 'ratio' => $ratio];
    }
}

/**
 * Stratégie de compression rapide - Privilégie la vitesse
 */
class FastCompression implements CompressionStrategy
{
    public function compress(string $data): array
    {
        $originalSize = strlen($data);
        $compressed = gzcompress($data, 1);
        $compressedSize = strlen($compressed);
        $ratio = round((1 - $compressedSize / $originalSize) * 100, 2);
        
        echo "⚡ FAST : {$originalSize} octets → {$compressedSize} octets ({$ratio}%)\n";
        
        return ['data' => $compressed, 'size' => $compressedSize, 'ratio' => $ratio];
    }
}

// ============================================
// CONTEXTE : FileCompressor
// ============================================

/**
 * Le contexte utilise une stratégie sans connaître ses détails
 * Il peut changer de stratégie dynamiquement
 */
class FileCompressor
{
    private ?CompressionStrategy $strategy = null;

    /**
     * Définit la stratégie de compression à utiliser
     */
    public function setStrategy(CompressionStrategy $strategy): void
    {
        $this->strategy = $strategy;
    }

    /**
     * Effectue la compression en déléguant à la stratégie
     */
    public function compress(string $data): array
    {
        if ($this->strategy === null) {
            throw new Exception("Aucune stratégie définie");
        }
        
        return $this->strategy->compress($data);
    }
}

// ============================================
// DÉMONSTRATION
// ============================================

echo "========================================\n";
echo "   PATTERN STRATEGY - Compression\n";
echo "========================================\n\n";

$data = str_repeat("Lorem ipsum dolor sit amet. ", 100);
$compressor = new FileCompressor();

echo "Fichier de " . strlen($data) . " octets\n\n";

// Test avec différentes stratégies
echo "--- Comparaison des stratégies ---\n";
$compressor->setStrategy(new ZipCompression());
$compressor->compress($data);

$compressor->setStrategy(new GzipCompression());
$compressor->compress($data);

$compressor->setStrategy(new FastCompression());
$compressor->compress($data);
