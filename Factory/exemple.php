<?php

// ============================================
// INTERFACE PRODUCT : Notification
// ============================================

/**
 * Interface commune pour tous les types de notifications
 */
interface Notification
{
    public function send(string $message, string $recipient): void;
}

// ============================================
// CONCRETE PRODUCTS : Types de notifications
// ============================================

/**
 * Notification par Email
 */
class EmailNotification implements Notification
{
    public function send(string $message, string $recipient): void
    {
        echo "✉️ EMAIL\n";
        echo "   À: {$recipient}\n";
        echo "   Message: {$message}\n";
        echo "   ✅ Email envoyé via SMTP\n\n";
    }
}

/**
 * Notification par SMS
 */
class SmsNotification implements Notification
{
    public function send(string $message, string $recipient): void
    {
        echo "📱 SMS\n";
        echo "   Numéro: {$recipient}\n";
        echo "   Message: {$message}\n";
        echo "   ✅ SMS envoyé via opérateur\n\n";
    }
}

/**
 * Notification Push (mobile)
 */
class PushNotification implements Notification
{
    public function send(string $message, string $recipient): void
    {
        echo "🔔 PUSH\n";
        echo "   Device: {$recipient}\n";
        echo "   Message: {$message}\n";
        echo "   ✅ Notification push envoyée\n\n";
    }
}

// ============================================
// CREATOR : NotificationService (abstrait)
// ============================================

/**
 * Classe abstraite qui définit la Factory Method
 */
abstract class NotificationService
{
    /**
     * Factory Method - à implémenter par les sous-classes
     */
    abstract protected function createNotification(): Notification;

    /**
     * Méthode qui utilise le produit créé par la Factory Method
     */
    public function notify(string $message, string $recipient): void
    {
        $notification = $this->createNotification();
        $notification->send($message, $recipient);
    }
}

// ============================================
// CONCRETE CREATORS : Services spécifiques
// ============================================

/**
 * Service de notification par Email
 */
class EmailNotificationService extends NotificationService
{
    protected function createNotification(): Notification
    {
        return new EmailNotification();
    }
}

/**
 * Service de notification par SMS
 */
class SmsNotificationService extends NotificationService
{
    protected function createNotification(): Notification
    {
        return new SmsNotification();
    }
}

/**
 * Service de notification Push
 */
class PushNotificationService extends NotificationService
{
    protected function createNotification(): Notification
    {
        return new PushNotification();
    }
}

// ============================================
// DÉMONSTRATION
// ============================================

echo "========================================\n";
echo "   PATTERN FACTORY METHOD\n";
echo "   Système de notifications\n";
echo "========================================\n\n";

$message = "Votre commande a été expédiée !";

// Utilisation de différents services de notification
$services = [
    new EmailNotificationService(),
    new SmsNotificationService(),
    new PushNotificationService()
];

$recipients = [
    "tom@example.com",
    "+33612345678",
    "device_token_abc123"
];

foreach ($services as $index => $service) {
    $service->notify($message, $recipients[$index]);
}
