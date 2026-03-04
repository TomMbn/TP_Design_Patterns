<?php

// ============================================
// COMPONENT (Composant Commun)
// ============================================

/**
 * L'interface commune à tous les éléments de l'arbre (Feuilles ET Nœuds).
 * Le code client utilisera uniquement cette interface.
 */
interface Playable
{
    /** Renvoie le nom de l'élément */
    public function getName(): string;

    /** Renvoie la durée totale en secondes */
    public function getDuration(): int;

    /** Affiche visuellement la structure (avec indentation) */
    public function displayStructure(string $indentation = ""): void;
}


// ============================================
// LEAF (La Feuille / L'élément simple terminal)
// ============================================

/**
 * La Chanson est une "Feuille" de l'arbre. Elle ne peut pas contenir d'enfants.
 * Elle fait le vrai travail (donner sa propre durée brute).
 */
class Song implements Playable
{
    private string $name;
    private int $durationInSeconds;

    public function __construct(string $name, int $durationInSeconds)
    {
        $this->name = $name;
        $this->durationInSeconds = $durationInSeconds;
    }

    public function getName(): string
    {
        return "🎵 " . $this->name;
    }

    public function getDuration(): int
    {
        return $this->durationInSeconds;
    }

    public function displayStructure(string $indentation = ""): void
    {
        // Affiche simplement ses infos formattées
        $min = intdiv($this->getDuration(), 60);
        $sec = $this->getDuration() % 60;
        $timestamp = sprintf("%d:%02d", $min, $sec);

        echo $indentation . $this->getName() . " [{$timestamp}]\n";
    }
}


// ============================================
// COMPOSITE (Le Nœud / Le Conteneur)
// ============================================

/**
 * La Playlist est un conteneur complexe. Elle peut contenir des Chansons (Feuilles)
 * mais aussi d'autres Playlists (Composites), car les deux implémentent Playable.
 */
class Playlist implements Playable
{
    private string $name;

    /** @var Playable[] Liste des enfants */
    private array $items = [];

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /** Méthode spécifique au Composite pour ajouter des enfants */
    public function add(Playable $item): void
    {
        $this->items[] = $item;
    }

    public function getName(): string
    {
        return "📁 Playlist: " . $this->name;
    }

    /**
     * DÉLÉGATION CRUCIALE: Le noeud parcoure tous ses enfants et leur demande
     * leur propre durée pour faire la somme totale. S'il demande à une sous-playlist,
     * la récursion se fera naturellement car elle retournera aussi un int.
     */
    public function getDuration(): int
    {
        $totalDuration = 0;
        foreach ($this->items as $item) {
            $totalDuration += $item->getDuration();
        }
        return $totalDuration;
    }

    /**
     * S'affiche elle-même, puis délègue l'affichage à ses enfants (+ d'indentation)
     */
    public function displayStructure(string $indentation = ""): void
    {
        $min = intdiv($this->getDuration(), 60);
        $sec = $this->getDuration() % 60;
        $timestamp = sprintf("%d:%02d", $min, $sec);

        echo $indentation . $this->getName() . " (Durée totale: {$timestamp})\n";

        foreach ($this->items as $item) {
            $item->displayStructure($indentation . "    "); // Ajoute 4 espaces
        }
    }
}


// ============================================
// DÉMONSTRATION / CLIENT
// ============================================

echo "========================================\n";
echo "   PATTERN COMPOSITE\n";
echo "   Système de Bibliothèque Musicale (Playlists)\n";
echo "========================================\n\n";

// 1. Création de feuilles simples (Chansons)
$song1 = new Song("Bohemian Rhapsody", 355);
$song2 = new Song("Stairway to Heaven", 482);
$song3 = new Song("Hotel California", 390);
$song4 = new Song("Smells Like Teen Spirit", 301);
$song5 = new Song("Imagine", 183);

// 2. Création de Composites (Playlists Simples)
$rockPlaylist = new Playlist("Classic Rock Essentials");
$rockPlaylist->add($song1);
$rockPlaylist->add($song2);
$rockPlaylist->add($song3);

$grungePlaylist = new Playlist("Seattle Grunge 90s");
$grungePlaylist->add($song4);

// 3. Création du GRAND Composite (La "Mega-Playlist" ou Dossier)
// On ajoute la chanson isolée ET les sous-playlists !
$myLibrary = new Playlist("Ma Grande Bibliothèque Audio");
$myLibrary->add($rockPlaylist);
$myLibrary->add($grungePlaylist);
$myLibrary->add($song5); // Une chanson directement dans la bibliothèque

// 4. Le Client interagit UNIQUEMENT avec l'interface commune, même sur l'arbre complexe
echo ">>> Demande au composant racine complexe de s'afficher :\n\n";
$myLibrary->displayStructure();

echo "\n----------------------------------------\n";
echo ">>> Le développeur client s'en fout de si c'est une liste ou une chanson.\n";
echo ">>> Il demande juste la durée :\n\n";

$target = $myLibrary; // Ou $song1, ou $rockPlaylist... Le code client reste le même.

$totalSeconds = $target->getDuration();
echo "= La cible '" . $target->getName() . "' dure " . round($totalSeconds / 60, 2) . " minutes en tout !\n";

echo "\n✅ Le pattern Composite a permis de traiter l'arbre complet ou de simples objets terminaux exactement de la même manière.\n";
