<?php

include "models/MessageModel.php";
include "controllers/ProfileController.php";

// Enumerator filtraci ziskavani zprav z databaze
enum getFilter {
    case Sender;
    case Recipient;
    case SenderAndRecipient;        
}

/**
 * Controller pro praci s tabulkou zprav
 * Veskerer operace s databazi se vytvari pres prepare statementy a s pouzitim parameteru, tudiz je DB chranena proti SQLInjection
 */

class MessageController
{
    // Metda prida novou zpravu do databaze
    public static function add(MessageModel $model): bool {
        $conn = DB::connect();
        $stmt = $conn->prepare(
            "INSERT INTO messages (sender_id, recipient_id, content, sent_at) 
            VALUES (?, ?, ?, ?)",
        );

        $timestamp = $model->timestamp->format('Y-m-d H:i:s');
        $stmt->bind_param(
            "iiss",
            $model->sender->id,
            $model->recipient->id,
            $model->content,
            $timestamp
        );

        return $stmt->execute();
    }

    // Metoda ziska vsechny zpravy ktere maji nejakou vazbu na id uzivatele
    // Vazba se specifikuje pres enum.
    // Bud ziskaji odeslane zpravy, prijate zpravy a nebo oboje
    public static function getAll(int $senderId, getFilter $filter = getFilter::SenderAndRecipient): array {
        $conn = DB::connect();

        switch ($filter) {
            case getFilter::Sender:
                $stmt = $conn->prepare("SELECT message_id, sender_id, recipient_id, content, sent_at FROM messages WHERE sender_id = ?");
                $stmt->bind_param("i", $senderId);
                break;
            case getFilter::Recipient:
                $stmt = $conn->prepare("SELECT message_id, sender_id, recipient_id, content, sent_at FROM messages WHERE recipient_id = ?");
                $stmt->bind_param("i", $senderId);
                break;
            default:
                $stmt = $conn->prepare("SELECT message_id,sender_id, recipient_id, content, sent_at FROM messages WHERE sender_id = ? OR recipient_id = ?");
                $stmt->bind_param("ii", $senderId, $senderId);
                break;
        }

        $stmt->execute();

        $stmt->bind_result($messageId, $senderId, $recipientID, $content, $timestamp);

        $result = [];

        while ($stmt->fetch()) {
            $currentMessage = new MessageModel();   

            $currentMessage->id = $messageId;
            $currentMessage->sender = ProfileController::Get($senderId);
            $currentMessage->recipient = ProfileController::Get($recipientID);
            $currentMessage->content = $content;
            $currentMessage->timestamp = $timestamp;
            
            $result[] = $currentMessage;
        }


        return $result;
    }

    // Metoda ziska jednu zpravu podle jeji ID
    public static function get(int $id): ?MessageModel {
        $conn = DB::connect();



        $stmt = $conn->prepare(
            "SELECT sender_id, recipient_id, content
            FROM messages
            WHERE message_id = ?",
        );

        $stmt->bind_param(
            "i",
            $id
        );

        $stmt->execute();


        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $data = $result->fetch_assoc();
            $model = new MessageModel();
            $sender = ProfileController::get($data['sender_id']);
            $recipient = ProfileController::get($data['recipient_id']);

            if ($sender == null || $recipient == null)
                return null;

            $model->recipient = $recipient;
            $model->sender = $sender;
            $model->content = $data['content'];

            return $model;
        }

        return null;
    }

}