<?php

// ============================================
// PRODUCT
// ============================================

/**
 * L'objet complexe que l'on cherche à construire.
 * Ici, une requête HTTP personnalisée.
 */
class HttpRequest
{
    private string $method = 'GET';
    private string $url = '';
    private array $headers = [];
    private string $body = '';
    private int $timeout = 30;

    public function setMethod(string $method): void
    {
        $this->method = strtoupper($method);
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function addHeader(string $key, string $value): void
    {
        $this->headers[$key] = $value;
    }

    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    public function setTimeout(int $timeout): void
    {
        $this->timeout = $timeout;
    }

    /**
     * Méthode d'affichage pour simuler l'envoi de la requête
     */
    public function send(): void
    {
        echo "🌐 EXÉCUTION DE LA REQUÊTE HTTP\n";
        echo "-------------------------------\n";
        echo "{$this->method} {$this->url}\n";
        echo "Timeout: {$this->timeout}s\n";

        if (!empty($this->headers)) {
            echo "Headers:\n";
            foreach ($this->headers as $k => $v) {
                echo "  - {$k}: {$v}\n";
            }
        }

        if (!empty($this->body)) {
            echo "Body:\n{$this->body}\n";
        }
        echo "-------------------------------\n\n";
    }
}

// ============================================
// BUILDER INTERFACE
// ============================================

/**
 * Interface Builder spécifiant les méthodes pour créer
 * les différentes parties de l'objet Produit.
 */
interface HttpRequestBuilder
{
    public function setMethod(string $method): self;
    public function setUrl(string $url): self;
    public function withBearerToken(string $token): self;
    public function withJsonBody(array $data): self;
    public function withTimeout(int $seconds): self;
    public function build(): HttpRequest;
}

// ============================================
// CONCRETE BUILDER
// ============================================

/**
 * Implémentation concrète du constructeur de requêtes.
 * L'utilisation du "return $this" (Interface Fluide) permet
 * de chainer les appels (ex: builder->setUrl()->setMethod()->build())
 */
class ApiRequestBuilder implements HttpRequestBuilder
{
    private HttpRequest $request;

    public function __construct()
    {
        $this->reset();
    }

    /**
     * Réinitialise le builder pour une nouvelle construction
     */
    public function reset(): void
    {
        $this->request = new HttpRequest();
    }

    public function setMethod(string $method): self
    {
        $this->request->setMethod($method);
        return $this;
    }

    public function setUrl(string $url): self
    {
        $this->request->setUrl($url);
        return $this;
    }

    public function withBearerToken(string $token): self
    {
        $this->request->addHeader('Authorization', "Bearer {$token}");
        return $this;
    }

    public function withJsonBody(array $data): self
    {
        $this->request->addHeader('Content-Type', 'application/json');
        $this->request->setBody(json_encode($data, JSON_PRETTY_PRINT));
        return $this;
    }

    public function withTimeout(int $seconds): self
    {
        $this->request->setTimeout($seconds);
        return $this;
    }

    /**
     * Retourne le produit final et réinitialise le builder
     */
    public function build(): HttpRequest
    {
        $result = $this->request;
        $this->reset(); // Prêt pour la prochaine construction
        return $result;
    }
}

// ============================================
// DIRECTOR (Optionnel)
// ============================================

/**
 * Le Directeur sait dans quel ordre appeler les méthodes du Builder
 * pour créer des configurations standardisées/réutilisables.
 */
class ApiRequestDirector
{
    private HttpRequestBuilder $builder;

    public function __construct(HttpRequestBuilder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * Recette pour une requête GET standard (ex: Fetch Data)
     */
    public function makeStandardGetRequest(string $endpoint, string $token): HttpRequest
    {
        return $this->builder
            ->setMethod('GET')
            ->setUrl("https://api.monservice.com/v1/{$endpoint}")
            ->withBearerToken($token)
            ->withTimeout(15)
            ->build();
    }

    /**
     * Recette pour une requête POST complexe (ex: Send Data)
     */
    public function makeSecuredPostRequest(string $endpoint, string $token, array $payload): HttpRequest
    {
        return $this->builder
            ->setMethod('POST')
            ->setUrl("https://api.monservice.com/v1/{$endpoint}")
            ->withBearerToken($token)
            ->withJsonBody($payload)
            ->withTimeout(60) // Opération plus longue
            ->build();
    }
}

// ============================================
// DÉMONSTRATION
// ============================================

echo "========================================\n";
echo "   PATTERN BUILDER\n";
echo "   Construction de Requêtes HTTP / API\n";
echo "========================================\n\n";

$builder = new ApiRequestBuilder();

echo ">>> 1. Utilisation du Builder étape par étape (Interface Fluide)\n\n";

$customRequest = $builder
    ->setMethod('PUT')
    ->setUrl('https://api.exemple.com/users/123')
    ->withJsonBody(['status' => 'active', 'role' => 'admin'])
    ->build();

$customRequest->send();


echo ">>> 2. Utilisation du Director pour des requêtes standardisées\n\n";

$director = new ApiRequestDirector($builder);
$token = "jwt_token_abcdef123456";

// Création via une "recette" du Director
$getRequest = $director->makeStandardGetRequest("dashboard/stats", $token);
$getRequest->send();

// Autre création via le Director
$postRequest = $director->makeSecuredPostRequest(
    "users/register",
    $token,
    ['email' => 'jean@dupont.fr', 'password' => 'secret123']
);
$postRequest->send();
