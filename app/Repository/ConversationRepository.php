<?php

namespace App\Repository;

use App\User;
use App\Message;
use Carbon\Carbon;

class ConversationRepository {

    /**
     * @var User
     */
    private $user;

    /**
     * @var Message
     */
    private $message;

    public function __construct(User $user, Message $message)
    {
        $this->user = $user;
        $this->message = $message;
    }

    public function getConversations($userId) {
        $conversations =  $this->user->newQuery()
            ->select('name', 'id')
            ->where('id', '!=', $userId)
            ->get();



        return $conversations;
    }

    public function createMessage($content, $from, $to) {

        return $this->message->newQuery()->create([
            'content' => $content,
            'from_id' => $from,
            'to_id' => $to,
            'created_at' => Carbon::now()
        ]);
    }

    public function getMessagesFor($from_id, $to_id) {
        return $this->message->newQuery()
            ->whereRaw("((from_id = $from_id AND to_id = $to_id) OR (from_id = $to_id AND to_id = $from_id))")
            ->orderBy('created_at', 'DESC')
            ->with([
                'from' => function ($query) { return $query->select('name', 'id'); }
            ]);

    }

    /**
     * Récupère le nombre de messages non lus pour chaque conversations
     * @param $userId
     * @return \Illuminate\Support\Collection|static
     */
    public function unreadCount($userId) {
        return $this->message->newQuery()
            ->where('to_id', $userId)
            ->groupBy('from_id')
            ->selectRaw('from_id, COUNT(id) as count')
            ->whereRaw('read_at IS NULL')
            ->get()
            ->pluck('count', 'from_id');
    }

    /**
     * Marque tous les messages de cet utilisateur comme lu
     * @param $from_id
     * @param $to_id
     */
    public function readAllFrom($from_id, $to_id) {
        $this->message->where('from_id', $from_id)->where('to_id', $to_id)->update([
            'read_at' => Carbon::now()
        ]);
    }
}